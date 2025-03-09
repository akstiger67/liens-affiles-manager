<?php
/*
Plugin Name: Liens Affiliés Manager
Plugin URI: https://julienweb.com
Description: Gère vos liens d'affiliation via un type de contenu personnalisé "Liens affiliés". En plus de la redirection front-end et de l'affichage dans le backoffice, vous pouvez choisir l'intermédiaire du slug via une option (ex: /go/, /link/, /voir/, etc.).  
Version: 1.1  
Author: Julien Web
Author URI: https://julienweb.com
Text Domain: liens-affiles-manager
*/

/* -------------------------------------
   0. Options & Réglages du plugin
------------------------------------- */

// Enregistrer la réglage pour le slug intermédiaire
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
        'cliquer'  => 'Cliquer',
        'acceder'  => 'Accéder',
        'visiter'  => 'Visiter'
    );
    ?>
    <div class="wrap">
        <h1>Liens Affiliés Manager Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('laf_settings_group'); ?>
            <?php do_settings_sections('laf_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Slug intermédiaire</th>
                    <td>
                        <select name="laf_slug_option">
                            <?php foreach ($allowed_options as $key => $label): ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php selected($current_slug, $key); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">Choisissez le slug intermédiaire pour vos liens (ex: /go/, /link/, /voir/, etc.). Après modification, pensez à rafraîchir vos permaliens (Réglages > Permaliens).</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


/* -------------------------------------
   1. Type de contenu personnalisé "Liens affiliés"
------------------------------------- */
function register_affiliate_post_type() {
    // Récupère le slug choisi dans les réglages (par défaut "go")
    $slug = get_option('laf_slug_option', 'go');
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
        'public'             => false,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => $slug, 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 100,
        'supports'           => array( 'title' )
    );

    register_post_type( 'affiliate', $args );
}
add_action( 'init', 'register_affiliate_post_type' );


/* -------------------------------------
   2. Meta box pour l'URL d'affiliation
------------------------------------- */
function add_affiliate_meta_box() {
    add_meta_box( 'affiliate_meta_box', 'Lien d\'affiliation', 'display_affiliate_meta_box', 'affiliate', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'add_affiliate_meta_box' );

function display_affiliate_meta_box( $post ) {
    $affiliate_url = get_post_meta( $post->ID, 'affiliate_url', true );
    ?>
    <label for="affiliate_url">URL d'affiliation :</label>
    <input type="text" name="affiliate_url" id="affiliate_url" value="<?php echo esc_attr( $affiliate_url ); ?>" style="width:100%;" placeholder="https://exemple.com/affiliation" />
    <?php
}

function save_affiliate_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( isset( $_POST['affiliate_url'] ) ) {
        update_post_meta( $post_id, 'affiliate_url', sanitize_text_field( $_POST['affiliate_url'] ) );
    }
}
add_action( 'save_post', 'save_affiliate_meta_box' );


/* -------------------------------------
   3. Redirection front-end
------------------------------------- */
function redirect_affiliate_post() {
    if ( is_singular( 'affiliate' ) ) {
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
add_action( 'template_redirect', 'redirect_affiliate_post' );


/* -------------------------------------
   4. Colonne personnalisée "Lien réécrit" dans le backoffice
------------------------------------- */
function add_affiliate_custom_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') { 
            $new_columns['rewritten_link'] = 'Lien réécrit';
        }
    }
    return $new_columns;
}
add_filter('manage_affiliate_posts_columns', 'add_affiliate_custom_columns');

function show_affiliate_custom_column($column, $post_id) {
    if ($column === 'rewritten_link') {
        $link = get_permalink($post_id);
        echo '<div class="affiliate-link">';
        echo '<a href="'. esc_url($link) .'" target="_blank">'. esc_html($link) .'</a>';
        echo ' <button class="copy-btn" data-link="'. esc_attr($link) .'">Copier</button>';
        echo '</div>';
    }
}
add_action('manage_affiliate_posts_custom_column', 'show_affiliate_custom_column', 10, 2);


/* -------------------------------------
   5. Styles et script pour le bouton "Copier" dans le backoffice
------------------------------------- */
function affiliate_admin_styles() {
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
add_action('admin_head', 'affiliate_admin_styles');

function affiliate_admin_script() {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function(){
        const copyButtons = document.querySelectorAll(".copy-btn");
        copyButtons.forEach(function(button) {
            button.addEventListener("click", function(){
                const link = this.getAttribute("data-link");
                navigator.clipboard.writeText(link).then(() => {
                    this.textContent = "Copié!";
                    setTimeout(() => {
                        this.textContent = "Copier";
                    }, 2000);
                });
            });
        });
    });
    </script>
    <?php
}
add_action('admin_footer', 'affiliate_admin_script');
