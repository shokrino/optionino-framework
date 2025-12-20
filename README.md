# Optionino Framework

**A Simple, Secure, and Powerful Settings Framework for WordPress**

Build professional admin settings pages for your WordPress plugins and themes in minutes. No complicated setup, just copy and use.

---

## 🎯 Why Optionino?

✅ **Super Simple** - Create settings pages with just a few lines of code  
✅ **100% Secure** - Built-in XSS and CSRF protection  
✅ **Rich Fields** - Text, color, image, editor, repeater, and more  
✅ **Multi-Plugin Safe** - Use in multiple plugins without conflicts  
✅ **Smart Loader** - Automatically uses the latest version  
✅ **Translation Ready** - Fully localized and i18n compatible  

---

## 🚀 Quick Start (5 Minutes)

### Step 1: Copy Files
Copy the `optionino-framework` folder into your plugin or theme:
```
your-plugin/
  ├── inc/
  │   └── optionino-framework/  ← Here
  └── your-plugin.php
```

### Step 2: Load Framework
Add this to your main plugin file:

```php
<?php
// Load Optionino Framework
require_once plugin_dir_path( __FILE__ ) . 'inc/optionino-framework/optionino-framework.php';
```

### Step 3: Create Your First Settings Page
Create a new file for settings (e.g., `inc/admin/settings.php`):

```php
<?php
// Configure the settings page
OPTNNO::set_config('my_plugin_settings', array(
    'menu_title'      => 'My Settings',
    'page_title'      => 'Plugin Settings',
    'menu_type'       => 'menu',
    'page_slug'       => 'my-plugin-settings',
    'icon_url'        => 'dashicons-admin-generic',
));

// Create a tab with fields
OPTNNO::set_tab('my_plugin_settings', array(
    'id'     => 'general',
    'title'  => 'General',
    'icon'   => 'dashicons-admin-home',
    'fields' => array(
        
        // Text field
        array(
            'id'      => 'site_name',
            'type'    => 'text',
            'title'   => 'Site Name',
            'default' => 'My Website',
        ),
        
        // Color picker
        array(
            'id'      => 'primary_color',
            'type'    => 'color',
            'title'   => 'Primary Color',
            'default' => '#0073aa',
        ),
        
        // Switcher (on/off)
        array(
            'id'      => 'enable_feature',
            'type'    => 'switcher',
            'title'   => 'Enable Feature',
            'default' => true,
        ),
        
    ),
));
```

### Step 4: Load Settings File
In your main plugin file, include the settings:

```php
<?php
require_once plugin_dir_path( __FILE__ ) . 'inc/optionino-framework/optionino-framework.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/admin/settings.php';
```

### Step 5: Done! 🎉
You'll now see a new menu item "My Settings" in WordPress admin.

---

## 📖 Getting Saved Values

Use the helper function to retrieve saved values:

```php
// Get values
$site_name = optionino_get('my_plugin_settings', 'site_name');
$color = optionino_get('my_plugin_settings', 'primary_color');
$is_enabled = optionino_get('my_plugin_settings', 'enable_feature');

// Use in your code
if ( $is_enabled ) {
    echo '<h1 style="color: ' . esc_attr($color) . '">' . esc_html($site_name) . '</h1>';
}
```

---

## 🎨 Available Field Types

| Field Type | Description | Code |
|-----------|-------------|------|
| `text` | Simple text input | `'type' => 'text'` |
| `textarea` | Multi-line text | `'type' => 'textarea'` |
| `number` | Number input | `'type' => 'number'` |
| `color` | Color picker | `'type' => 'color'` |
| `image` | Image uploader | `'type' => 'image'` |
| `switcher` | On/off toggle | `'type' => 'switcher'` |
| `select` | Dropdown menu | `'type' => 'select'` |
| `buttonset` | Button group | `'type' => 'buttonset'` |
| `tinymce` | WYSIWYG editor | `'type' => 'tinymce'` |
| `repeater` | Repeatable fields | `'type' => 'repeater'` |

### 🔸 Example: Select Dropdown

```php
array(
    'id'      => 'user_role',
    'type'    => 'select',
    'title'   => 'User Role',
    'options' => array(
        'admin'  => 'Administrator',
        'editor' => 'Editor',
        'author' => 'Author',
    ),
    'default' => 'author',
),
```

### 🔸 Example: Image Upload

```php
array(
    'id'    => 'logo',
    'type'  => 'image',
    'title' => 'Site Logo',
),
```

**Get image URL:**
```php
$logo_url = optionino_get('my_plugin_settings', 'logo');
echo '<img src="' . esc_url($logo_url) . '" alt="Logo">';
```

### 🔸 Example: WYSIWYG Editor

```php
array(
    'id'    => 'about_text',
    'type'  => 'tinymce',
    'title' => 'About Us',
),
```

### 🔸 Example: Repeater Field

Build lists of similar items (like social networks):

```php
array(
    'id'     => 'social_networks',
    'type'   => 'repeater',
    'title'  => 'Social Networks',
    'fields' => array(
        array(
            'id'    => 'network_name',
            'type'  => 'text',
            'title' => 'Network Name',
        ),
        array(
            'id'    => 'network_url',
            'type'  => 'text',
            'title' => 'URL',
        ),
        array(
            'id'    => 'network_icon',
            'type'  => 'image',
            'title' => 'Icon',
        ),
    ),
),
```

**Get repeater data:**
```php
$socials = optionino_get('my_plugin_settings', 'social_networks');

if ( is_array($socials) ) {
    foreach ( $socials as $social ) {
        echo '<a href="' . esc_url($social['network_url']) . '">';
        echo esc_html($social['network_name']);
        echo '</a>';
    }
}
```

---

## 🔀 Conditional Fields

Show/hide fields based on other field values:

### Simple Condition:
Show field only when switcher is enabled:

```php
array(
    'id'      => 'enable_advanced',
    'type'    => 'switcher',
    'title'   => 'Enable Advanced Settings',
    'default' => false,
),
array(
    'id'      => 'advanced_option',
    'type'    => 'text',
    'title'   => 'Advanced Option',
    'require' => array(
        array('enable_advanced', '==', true)
    ),
),
```

### Advanced (Multiple Conditions):
```php
'require' => array(
    'relation' => 'AND', // or 'OR'
    array('enable_advanced', '==', true),
    array('user_role', '==', 'admin'),
),
```

---

## 🗂️ Multiple Tabs

Create multiple tabs to organize settings:

```php
// Tab 1: General
OPTNNO::set_tab('my_plugin_settings', array(
    'id'     => 'general',
    'title'  => 'General',
    'icon'   => 'dashicons-admin-home',
    'fields' => array(
        // Fields...
    ),
));

// Tab 2: Appearance
OPTNNO::set_tab('my_plugin_settings', array(
    'id'     => 'appearance',
    'title'  => 'Appearance',
    'icon'   => 'dashicons-admin-appearance',
    'fields' => array(
        // Fields...
    ),
));

// Tab 3: Advanced
OPTNNO::set_tab('my_plugin_settings', array(
    'id'     => 'advanced',
    'title'  => 'Advanced',
    'icon'   => 'dashicons-admin-tools',
    'fields' => array(
        // Fields...
    ),
));
```

---

## 🎨 Custom Tab with PHP File

Create a tab with completely custom content (dashboard, reports, etc.):

```php
OPTNNO::set_tab('my_plugin_settings', array(
    'id'    => 'dashboard',
    'title' => 'Dashboard',
    'icon'  => 'dashicons-dashboard',
    'file'  => plugin_dir_path(__FILE__) . 'inc/views/dashboard.php',
));
```

Then create `inc/views/dashboard.php`:

```php
<div class="wrap">
    <h2>Plugin Dashboard</h2>
    <p>Your custom content here...</p>
    
    <?php
    // Use WordPress functions
    $count = wp_count_posts('post');
    echo '<p>Total Posts: ' . $count->publish . '</p>';
    ?>
</div>
```

---

## ⚙️ Advanced Configuration

### Create Submenu
Add settings page under an existing menu:

```php
OPTNNO::set_config('my_plugin_settings', array(
    'menu_type'       => 'submenu',
    'parent_slug'     => 'options-general.php',
    'menu_title'      => 'My Plugin',
    'page_title'      => 'My Plugin Settings',
    'page_slug'       => 'my-plugin-settings',
    'page_capability' => 'manage_options',
));
```

**Common parent slugs:**
- `options-general.php` - Settings
- `themes.php` - Appearance
- `plugins.php` - Plugins
- `tools.php` - Tools
- `users.php` - Users

### Add Logo

```php
OPTNNO::set_config('my_plugin_settings', array(
    'menu_title' => 'My Plugin',
    'page_title' => 'Settings',
    'logo_url'   => plugins_url('/assets/logo.svg', __FILE__),
));
```

---

## 🔌 Use in Multiple Plugins

If multiple plugins use Optionino, **no conflicts occur**. The framework automatically uses the latest version.

### Disable Demo Config

If you're bundling the framework and don't want the demo to show:

```php
// Before loading optionino-framework.php
define( 'OPTNNO_DISABLE_CONFIG', true );

require_once plugin_dir_path( __FILE__ ) . 'inc/optionino-framework/optionino-framework.php';
```

---

## 🔒 Security

Built with enterprise-grade security:

✅ **Nonce Verification** - CSRF attack prevention  
✅ **Capability Check** - User permission verification  
✅ **Data Sanitization** - Clean all inputs  
✅ **Output Escaping** - XSS attack prevention  
✅ **Directory Protection** - Secure file access  

---

## 🌐 Translation Support

Fully translation-ready. Place your `.po` and `.mo` files in the `languages/` folder:

```
languages/
  ├── optionino-fa_IR.po
  └── optionino-fa_IR.mo
```

---

## ❓ FAQ

### How to reset all values?
```php
delete_option('my_plugin_settings');
```

### How to make a field optional?
All fields are optional by default. Just set a default value:
```php
'default' => 'default value',
```

### How to disable a field?
```php
'attributes' => array(
    'disabled' => 'disabled',
),
```

### How to add placeholder text?
```php
'attributes' => array(
    'placeholder' => 'Enter text here...',
),
```

---

## 📦 Complete Example

```php
<?php
/**
 * Plugin Name: Example Plugin
 */

// 1. Load Framework
require_once plugin_dir_path( __FILE__ ) . 'inc/optionino-framework/optionino-framework.php';

// 2. Configure Page
OPTNNO::set_config('example_plugin', array(
    'menu_title' => 'Example Plugin',
    'page_title' => 'Settings',
    'page_slug'  => 'example-plugin',
    'logo_url'   => plugins_url('/logo.svg', __FILE__),
));

// 3. General Tab
OPTNNO::set_tab('example_plugin', array(
    'id'     => 'general',
    'title'  => 'General',
    'fields' => array(
        array(
            'id'      => 'enable',
            'type'    => 'switcher',
            'title'   => 'Enable',
            'default' => true,
        ),
        array(
            'id'      => 'api_key',
            'type'    => 'text',
            'title'   => 'API Key',
        ),
    ),
));

// 4. Appearance Tab
OPTNNO::set_tab('example_plugin', array(
    'id'     => 'style',
    'title'  => 'Appearance',
    'fields' => array(
        array(
            'id'      => 'color',
            'type'    => 'color',
            'title'   => 'Primary Color',
            'default' => '#ff5722',
        ),
        array(
            'id'      => 'logo',
            'type'    => 'image',
            'title'   => 'Logo',
        ),
    ),
));

// 5. Use in Code
function my_plugin_init() {
    $is_enabled = optionino_get('example_plugin', 'enable');
    
    if ( $is_enabled ) {
        $api_key = optionino_get('example_plugin', 'api_key');
        // Your code...
    }
}
add_action('init', 'my_plugin_init');
```

---

## 📄 License

MIT License

## 🤝 Contributing

Report bugs or suggest improvements in the Issues section.

---

**Made with ❤️ for WordPress Developers**
