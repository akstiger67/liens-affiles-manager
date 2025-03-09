# Affiliate Links Manager

**Affiliate Links Manager** is a WordPress plugin that allows you to easily manage your affiliate links using a custom post type. The plugin automatically generates a rewritten URL based on a customizable intermediate slug and redirects your visitors to the specified affiliate URL. In the admin area, a dedicated column displays the rewritten URL along with a "Copy" button for quick access.

## Features

- **Custom Post Type "Affiliate Links"**  
  Manage your affiliate links directly from the WordPress admin.

- **Affiliate URL Meta Box**  
  Enter the affiliate URL when creating or editing an affiliate link.

- **Front-end Redirection**  
  When a visitor accesses a URL of the form `domain.com/[slug]/[post-name]`, they are automatically redirected to the entered affiliate URL.

- **Customizable Intermediate Slug**  
  Choose from a variety of options (e.g., `go`, `link`, `see`, `check`, `click`, `aller`, `lien`, `voir`, `regarder`, `cliquer`, `acceder`, `visiter`) via a settings page.

- **Custom Admin Column**  
  Displays the rewritten URL in the "Affiliate Links" admin list with a "Copy" button for quick copying.

## Installation

1. **Download or clone the repository**  
   Clone or download this repository to your computer.

2. **Copy the plugin folder**  
   Place the `liens-affiles-manager` folder in the `/wp-content/plugins/` directory of your WordPress installation.

3. **Installation via WordPress Admin (Optional)**  
   Alternatively, zip the folder and install it via **Plugins > Add New > Upload Plugin**.

4. **Activation**  
   Activate the plugin from the **Plugins** menu in the WordPress admin.

5. **Configuration**  
   Go to **Settings > Affiliate Links Manager** to choose the intermediate slug.  
   **Note:** After changing this setting, refresh your permalinks by navigating to **Settings > Permalinks** and clicking "Save Changes".

## Usage

### Creating an Affiliate Link

1. In the WordPress admin area, navigate to the **Affiliate Links** menu.
2. Click **Add New** to create a new affiliate link.
3. Enter the title (which will be used to generate the rewritten URL) and the affiliate URL in the meta box.
4. Save the link.
   
For example, if you choose `go` as the slug and the title is `my-affiliate`, the URL will be:  https://domain.com/go/my-affiliate


### Admin Area

In the "Affiliate Links" list, a **Rewritten URL** column displays the generated URL. A **Copy** button next to the URL allows you to quickly copy it to your clipboard.

## Configuration

- **Customize the Intermediate Slug**  
Go to **Settings > Affiliate Links Manager** to select the intermediate slug from the available options.  
After making changes, remember to refresh your permalinks via **Settings > Permalinks**.

## Contributing

Contributions are welcome!  
If you have suggestions, bug reports, or improvements, please open an [issue](https://github.com/akstiger67/liens-affiles-manager/issues) or submit a pull request.

## Author

**Akstiger67**  
[https://julienweb.com](https://julienweb.com)

