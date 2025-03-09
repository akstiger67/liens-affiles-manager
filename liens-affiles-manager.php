<?php
/*
Plugin Name: Liens Affiliés Manager
Plugin URI: https://julienweb.com
Description: Gère vos liens d'affiliation via un type de contenu personnalisé "Liens affiliés". Vous pouvez choisir l'intermédiaire du slug (globalement ou individuellement) et consulter/modifier vos liens sur une page dédiée avec les boutons "Copier" et "Modifier".
Version: 1.4
Author: Votre Nom
Author URI: https://julienweb.com
Text Domain: liens-affiles-manager
*/

/* -------------------------------------
   0. Options & Réglages du plugin
------------------------------------- */

// Enregistrer la réglage pour le slug intermédiaire global
function laf_register_settings() {
    register_setting('laf_settings_group', 'laf_slug_option');
}
add_action('admin_init', 'laf_register_settings');

// Ajouter la page de réglages dans le menu "Réglages"
function laf_add_settings_menu() {
    add_options_page('Liens Affiliés Manager Settings', 'Liens Affiliés Manager', 'manage_options', 'laf-settings', 'laf_settings_page');
}
add_action('admin_menu', 'laf_add_settings_menu');

function laf_settings_page() {
    $current_slug = get_option('laf_slug_option', 'go');
    $allowed_options = array(
        'go'       => 'Go',
        'link'     => 'Link',
        'see'      => 'See',
        'check'    => 'Check',
        'click'    => 'Click',
        'aller'    => 'Aller',
        'lien'     => 'Lien',
        'voir'     => 'Voir',
        'regarder' => 'Regarder',
        'cliquer'  => 'Cliquer'
    );
    ?>
    <div class="wrap">
        <h1>Liens Affiliés Manager Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('laf_settings_group'); ?>
            <?php do_settings_sections('laf_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Slug intermédiaire global</th>
                    <td>
                        <select name="laf_slug_option">
                            <?php foreach ($allowed_options as $key => $label): ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php selected($current_slug, $key); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">Choisissez le slug intermédiaire global pour vos liens (ex: /go/, /link/, /voir/, etc.). Après modification, pensez à rafraîchir vos permaliens (Réglages > Permaliens).</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


/* -------------------------------------
   1. Enregistrer le type de contenu personnalisé "Liens affiliés"
------------------------------------- */
function register_aff_link_post_type() {
    $labels = array(
        'name'               => 'Liens affiliés',
        'singular_name'      => 'Lien affilié',
        'menu_name'          => 'Liens affiliés',
        'name_admin_bar'     => 'Lien affilié',
        'add_new'            => 'Ajouter Nouveau',
        'add_new_item'       => 'Ajouter un nouveau lien affilié',
        'new_item'           => 'Nouveau lien affilié',
        'edit_item'          => 'Modifier le lien affilié',
        'view_item'          => 'Voir le lien affilié',
        'all_items'          => 'Tous les liens affiliés',
        'search_items'       => 'Rechercher des liens affiliés',
        'not_found'          => 'Aucun lien affilié trouvé',
        'not_found_in_trash' => 'Aucun lien affilié dans la corbeille'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false, // Pas affiché directement en front-end
        'publicly_queryable' => true,  // Accessible via son URL
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => get_option('laf_slug_option', 'go'), 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 100,
        'supports'           => array( 'title' )
    );

    register_post_type( 'aff_link', $args );
}
add_action( 'init', 'register_aff_link_post_type' );

/* Flush rewrite rules on plugin activation */
function laf_plugin_activation() {
    register_aff_link_post_type();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'laf_plugin_activation' );


/* -------------------------------------
   2. Meta box pour l'URL d'affiliation
------------------------------------- */
function add_aff_link_meta_box() {
    add_meta_box( 'aff_link_meta_box', 'Lien d\'affiliation', 'display_aff_link_meta_box', 'aff_link', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'add_aff_link_meta_box' );

function display_aff_link_meta_box( $post ) {
    $affiliate_url = get_post_meta( $post->ID, 'affiliate_url', true );
    ?>
    <table class="aff-link-meta-table">
        <tr>
            <th>URL d'affiliation</th>
            <td>
                <input type="text" name="affiliate_url" id="affiliate_url" value="<?php echo esc_attr( $affiliate_url ); ?>" placeholder="https://exemple.com/affiliation" style="width:100%;">
            </td>
        </tr>
    </table>
    <?php
}

function save_aff_link_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( isset( $_POST['affiliate_url'] ) ) {
        update_post_meta( $post_id, 'affiliate_url', sanitize_text_field( $_POST['affiliate_url'] ) );
    }
}
add_action( 'save_post', 'save_aff_link_meta_box' );


/* -------------------------------------
   3. Rediriger l'utilisateur lorsqu'il visite une URL du type domaine.com/{slug}/{post-slug}
------------------------------------- */
function redirect_aff_link_post() {
    if ( is_singular( 'aff_link' ) ) {
        global $post;
        $affiliate_url = get_post_meta( $post->ID, 'affiliate_url', true );
        if ( $affiliate_url ) {
            wp_redirect( esc_url_raw( $affiliate_url ), 301 );
            exit;
        } else {
            wp_die( 'Aucun lien d\'affiliation défini pour ce lien affilié.' );
        }
    }
}
add_action( 'template_redirect', 'redirect_aff_link_post' );


/* -------------------------------------
   4. Supprimer le sous-menu "Tous les liens affiliés" par défaut
------------------------------------- */
function remove_aff_link_submenus() {
    remove_submenu_page( 'edit.php?post_type=aff_link', 'edit.php?post_type=aff_link' );
}
add_action( 'admin_menu', 'remove_aff_link_submenus', 999 );


/* -------------------------------------
   5. Créer une page d'administration personnalisée "Tous mes liens"
------------------------------------- */
function aff_link_add_admin_page() {
    add_submenu_page(
        'edit.php?post_type=aff_link',
        'Tous mes liens',         // Titre de la page
        'Tous mes liens',         // Libellé du menu
        'manage_options',
        'aff_link_rewritten_links',
        'aff_link_rewritten_links_page'
    );
}
add_action('admin_menu', 'aff_link_add_admin_page');

function aff_link_rewritten_links_page() {
    $global_slug = get_option('laf_slug_option', 'go');
    $args = array(
       'post_type'      => 'aff_link',
       'posts_per_page' => -1,
       'post_status'    => 'publish'
    );
    $query = new WP_Query($args);
    ?>
    <div class="wrap">
        <h1 class="aff-link-header">Tous mes liens</h1>
        <style>
            /* Style général du tableau */
            .aff-links-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                font-family: Arial, sans-serif;
            }
            .aff-links-table th, .aff-links-table td {
                padding: 12px 15px;
                border: 1px solid #e1e1e1;
            }
            .aff-links-table th {
                background: linear-gradient(45deg, #3498db, #2980b9);
                color: #fff;
                text-align: left;
            }
            .aff-links-table tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            .aff-links-table tr:hover {
                background-color: #f1f1f1;
            }
            /* Header personnalisé */
            .aff-link-header {
                margin-bottom: 20px;
                font-size: 24px;
                font-weight: bold;
                color: #333;
                border-bottom: 2px solid #3498db;
                padding-bottom: 10px;
            }
            /* Bouton Copier */
            .copy-btn {
                padding: 6px 12px;
                font-size: 14px;
                background: linear-gradient(45deg, #e74c3c, #c0392b);
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: background 0.3s, transform 0.2s;
            }
            .copy-btn:hover {
                background: linear-gradient(45deg, #c0392b, #a93226);
                transform: scale(1.05);
            }
            /* Bouton Modifier */
            .edit-btn {
                padding: 6px 12px;
                font-size: 14px;
                background: linear-gradient(45deg, #2ecc71, #27ae60);
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: background 0.3s, transform 0.2s;
                margin-right: 5px;
            }
            .edit-btn:hover {
                background: linear-gradient(45deg, #27ae60, #1e8449);
                transform: scale(1.05);
            }
            /* Formulaire d'édition */
            .edit-form {
                display: none;
                margin-top: 10px;
                padding: 10px;
                background-color: #f7f7f7;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .edit-form select,
            .edit-form input[type="text"] {
                padding: 6px;
                margin-right: 10px;
            }
            .edit-form .save-btn {
                padding: 6px 12px;
                font-size: 14px;
                background: linear-gradient(45deg, #3498db, #2980b9);
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
            /* Encadré explicatif sous le tableau */
            .aff-link-info-bottom {
                background: #f1f1f1;
                padding: 10px;
                border-left: 4px solid #3498db;
                margin-top: 20px;
            }
        </style>
        <table class="aff-links-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Rewritten Link</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($query->have_posts()) : $query->the_post(); 
                $post_id = get_the_ID();
                $post_slug = get_post_field('post_name', $post_id);
                if (empty($post_slug)) {
                    $post_slug = sanitize_title(get_the_title($post_id));
                }
                // Vérifier s'il existe une override pour ce post, sinon utiliser le slug global
                $slug_override = get_post_meta($post_id, 'aff_link_intermediate_slug', true);
                $intermediate = !empty($slug_override) ? $slug_override : $global_slug;
                $link = home_url( '/' . $intermediate . '/' . $post_slug );
                // Récupérer l'URL de redirection actuelle
                $current_redirect = get_post_meta($post_id, 'affiliate_url', true);
            ?>
                <tr>
                    <td><?php the_title(); ?></td>
                    <td>
                        <a href="<?php echo esc_url($link); ?>" target="_blank"><?php echo esc_html($link); ?></a>
                    </td>
                    <td>
                        <button class="edit-btn" data-postid="<?php echo esc_attr($post_id); ?>">Modifier</button>
                        <button class="copy-btn" data-link="<?php echo esc_attr($link); ?>">Copier</button>
                    </td>
                </tr>
                <tr class="edit-form-row" id="edit-form-<?php echo esc_attr($post_id); ?>" style="display:none;">
                    <td colspan="3">
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <?php wp_nonce_field('aff_link_update_'.$post_id, 'aff_link_nonce'); ?>
                            <input type="hidden" name="action" value="aff_link_update">
                            <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
                            <label>Slug intermédiaire&nbsp;:
                                <select name="new_intermediate_slug">
                                    <?php
                                    $options = array(
                                        'go'       => 'Go',
                                        'link'     => 'Link',
                                        'see'      => 'See',
                                        'check'    => 'Check',
                                        'click'    => 'Click',
                                        'aller'    => 'Aller',
                                        'lien'     => 'Lien',
                                        'voir'     => 'Voir',
                                        'regarder' => 'Regarder',
                                        'cliquer'  => 'Cliquer'
                                    );
                                    foreach($options as $key => $label) {
                                        $selected = ($key == $intermediate) ? 'selected' : '';
                                        echo '<option value="'.esc_attr($key).'" '.$selected.'>'.esc_html($label).'</option>';
                                    }
                                    ?>
                                </select>
                            </label>
                            &nbsp;&nbsp;
                            <label>Lien de redirection&nbsp;:
                                <input type="text" name="new_redirect_link" value="<?php echo esc_attr($current_redirect); ?>" style="width:300px;">
                            </label>
                            &nbsp;&nbsp;
                            <input type="submit" class="save-btn" value="Sauvegarder">
                            <button type="button" class="cancel-btn" data-postid="<?php echo esc_attr($post_id); ?>">Annuler</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; wp_reset_postdata(); ?>
            </tbody>
        </table>
        <!-- Encadré explicatif sous le tableau -->
        <div class="aff-link-info-bottom">
            <p>Cette page liste tous vos liens affiliés. Vous pouvez copier un lien en cliquant sur "Copier". Pour personnaliser un lien, cliquez sur "Modifier" afin d'ajuster individuellement le slug intermédiaire ou le lien de redirection.</p>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function(){
        // Bouton Copier
        var copyButtons = document.querySelectorAll(".copy-btn");
        copyButtons.forEach(function(btn) {
            btn.addEventListener("click", function(){
                var link = btn.getAttribute("data-link");
                navigator.clipboard.writeText(link).then(function(){
                    btn.textContent = "Copié!";
                    setTimeout(function(){
                        btn.textContent = "Copier";
                    }, 2000);
                });
            });
        });
        // Bouton Modifier : toggle affichage du formulaire
        var editButtons = document.querySelectorAll(".edit-btn");
        editButtons.forEach(function(btn) {
            btn.addEventListener("click", function(){
                var postId = btn.getAttribute("data-postid");
                var formRow = document.getElementById("edit-form-" + postId);
                if(formRow.style.display === "none") {
                    formRow.style.display = "table-row";
                } else {
                    formRow.style.display = "none";
                }
            });
        });
        // Bouton Annuler : cache le formulaire
        var cancelButtons = document.querySelectorAll(".cancel-btn");
        cancelButtons.forEach(function(btn) {
            btn.addEventListener("click", function(){
                var postId = btn.getAttribute("data-postid");
                var formRow = document.getElementById("edit-form-" + postId);
                formRow.style.display = "none";
            });
        });
    });
    </script>
    <?php
}


/* -------------------------------------
   6. Traitement du formulaire de modification via admin-post
------------------------------------- */
function aff_link_handle_update() {
    if ( ! current_user_can('manage_options') ) {
        wp_die('Permission refusée.');
    }
    if ( ! isset($_POST['post_id']) || ! isset($_POST['aff_link_nonce']) ) {
        wp_die('Données manquantes.');
    }
    $post_id = intval($_POST['post_id']);
    if ( ! wp_verify_nonce($_POST['aff_link_nonce'], 'aff_link_update_'.$post_id) ) {
        wp_die('Nonce invalide.');
    }
    if ( isset($_POST['new_intermediate_slug']) ) {
        $new_slug = sanitize_text_field($_POST['new_intermediate_slug']);
        update_post_meta($post_id, 'aff_link_intermediate_slug', $new_slug);
    }
    if ( isset($_POST['new_redirect_link']) ) {
        $new_redirect = esc_url_raw($_POST['new_redirect_link']);
        update_post_meta($post_id, 'affiliate_url', $new_redirect);
    }
    $redirect = add_query_arg('aff_link_updated', 'true', admin_url('edit.php?post_type=aff_link&page=aff_link_rewritten_links'));
    wp_redirect($redirect);
    exit;
}
add_action('admin_post_aff_link_update', 'aff_link_handle_update');


/* -------------------------------------
   7. Styles et script pour le bouton "Copier" dans le backoffice (pour la page par défaut)
------------------------------------- */
function aff_link_admin_styles() {
    echo '<style>
    .affiliate-link {
         display: flex;
         align-items: center;
         gap: 8px;
    }
    .affiliate-link .copy-btn {
         padding: 4px 8px;
         font-size: 12px;
         background: #e74c3c;
         color: #fff;
         border: none;
         border-radius: 3px;
         cursor: pointer;
    }
    .affiliate-link .copy-btn:hover {
         background: #c0392b;
    }
    </style>';
}
add_action('admin_head', 'aff_link_admin_styles');

function aff_link_meta_box_styles() {
    global $post_type;
    if ( 'aff_link' === $post_type ) {
        echo '<style>
            .aff-link-meta-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 15px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                font-family: Arial, sans-serif;
            }
            .aff-link-meta-table th, 
            .aff-link-meta-table td {
                padding: 10px;
                border: 1px solid #e1e1e1;
            }
            .aff-link-meta-table th {
                background: linear-gradient(45deg, #3498db, #2980b9);
                color: #fff;
                text-align: left;
            }
        </style>';
    }
}
add_action('admin_head', 'aff_link_meta_box_styles');


function aff_link_admin_script() {
    ?>
    <script>
    // Les scripts spécifiques à la page "Tous mes liens" sont déjà inclus dans la fonction aff_link_rewritten_links_page().
    </script>
    <?php
}
add_action('admin_footer', 'aff_link_admin_script');


/* -------------------------------------
   8. Message d'information sur l'écran "Ajouter/Modifier un lien affilié"
------------------------------------- */
function aff_link_admin_notice() {
    $screen = get_current_screen();
    if ( isset($screen->post_type) && $screen->post_type == 'aff_link' && in_array($screen->base, array('post', 'edit')) ) {
        echo '<div class="notice notice-info is-dismissible" style="margin-top:10px;">
                <p>Dans cet écran, vous pouvez ajouter ou modifier un lien affilié. Saisissez l\'URL de redirection dans la méta box. Le slug intermédiaire global est défini dans les réglages, mais vous pouvez le personnaliser pour chaque lien via la page "Tous mes liens".</p>
              </div>';
    }
}
add_action('admin_notices', 'aff_link_admin_notice');
?>
