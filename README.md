# Affiliate Links Manager

**Affiliate Links Manager** is a WordPress plugin that allows you to easily manage your affiliate links using a custom post type. The plugin automatically generates a rewritten URL based on a customizable intermediate slug and redirects your visitors to the specified affiliate URL. In addition, the plugin features a modern, dedicated admin page where you can view, copy, and even edit each affiliate link's settings.

## Features

- **Custom Post Type "Affiliate Links"**  
  Manage your affiliate links directly from the WordPress admin. (Internally registered as `aff_link` to avoid conflicts.)

- **Affiliate URL Meta Box**  
  Enter the affiliate URL when creating or editing an affiliate link. The meta box is styled with a modern table layout for a consistent look.

- **Front-end Redirection**  
  When a visitor accesses a URL of the form `domain.com/[slug]/[post-name]`, they are automatically redirected to the entered affiliate URL.

- **Customizable Intermediate Slug**  
  Set a global intermediate slug via a settings page. Choose from a variety of options (e.g., `go`, `link`, `see`, `check`, `click`, `aller`, `lien`, `voir`, `regarder`, `cliquer`). Additionally, you can override the global slug for each link individually through the dedicated admin page.

- **Dedicated Admin Page "Tous mes liens"**  
  A modern, responsive admin interface displays all your affiliate links in a beautifully styled table. Each row includes:
  - A **Copy** button to quickly copy the rewritten URL.
  - An **Edit** button that opens an inline form to modify the intermediate slug (for that specific link) and the redirection URL.

- **Better Design and UX**  
  The plugin now features improved styling throughout the admin interface, including the settings page, meta box, and dedicated admin page, ensuring a smooth and visually appealing user experience.

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
   Go to **Settings > Affiliate Links Manager** to choose the global intermediate slug.  
   **Note:** After changing this setting, refresh your permalinks by navigating to **Settings > Permalinks** and clicking "Save Changes".

## Usage

### Creating an Affiliate Link

1. In the WordPress admin area, navigate to **Liens affiliés**.
2. Click **Add New** to create a new affiliate link.
3. Enter the title (which will be used to generate the rewritten URL) and the affiliate URL in the meta box.
4. Save the link.  
   For example, if the global intermediate slug is `go` and the title is `my-affiliate`, the generated URL will be:
   
https://domain.com/go/my-affiliate


### Managing Your Affiliate Links

1. Navigate to the custom admin page **Tous mes liens** under the **Liens affiliés** menu.
2. The page displays a modern, responsive table listing all your affiliate links with their rewritten URLs.
3. Use the **Copy** button to quickly copy a URL.
4. Click the **Edit** button to reveal an inline form that lets you:
- Override the global intermediate slug (choose from a list of 10 options).
- Modify the redirection URL.
5. Submit the form to update the link. The page will automatically refresh to show the changes.

## Contributing

Contributions are welcome!  
If you have suggestions, bug reports, or improvements, please open an [issue](https://github.com/akstiger67/liens-affiles-manager/issues) or submit a pull request.

## Author

**Akstiger67**  
[https://julienweb.com](https://julienweb.com)

