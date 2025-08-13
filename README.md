# Optionino Framework (OPTNNO) 🎛️

Optionino is a powerful, flexible settings framework for WordPress plugins and themes. It gives you a clean API to create admin pages, tabs, and fields — plus handy extras like conditional fields and repeaters. 🌟

## Table of Contents 📚

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [File-Backed Tabs](#file-backed-tabs)
- [Field Types](#field-types)
- [Examples](#examples)
- [Troubleshooting](#troubleshooting)
- [License](#license)

## Features ✨

- Simple, fluent API for creating options pages and tabs. 🔧  
- **Multi-instance safe loader**: bundle Optionino inside multiple plugins/themes without conflicts (highest version wins, constants/functions/classes are guarded). 🧩  
- Correct **asset URLs** for enqueues (no more 404s). 🌐  
- **File-backed tabs**: render any PHP/HTML file inside a tab. 🗂️  
- Rich field set: `text`, `textarea`, `tinymce`, `image`, `color`, `buttonset`, `switcher`, `select`, `repeater`. 🛠️  
- `checkCondition` for showing optional fields based on other values. ✔️  
- Fully customizable options page UI in WP Admin. 🖥️

## Installation 🚀

1. **Download** the Optionino Framework.  
2. **Copy** the `optionino-framework` directory into your plugin/theme.  
3. **Include** the framework in your main plugin/theme file:

   ```php
   // In your plugin or theme bootstrap:
   require_once __DIR__ . '/optionino-framework/optionino-framework.php';

   // Your own config file (optional, but recommended to keep things tidy)
   require_once __DIR__ . '/optionino-config.php';
   ```

   > The loader is multi-instance safe. If multiple copies exist across plugins/themes, the **newest** version activates; all constants are still defined so nothing breaks.

## Usage 📝

Define your settings with `OPTNNO::set_config()` and your tabs with `OPTNNO::set_tab()`.

- `set_config($dev_name, $settings)` registers the options page and top-level config.  
- `set_tab($dev_name, $tab_settings)` adds tabs. Each tab can contain:
  - a `fields` array (rendered by Optionino), and/or
  - a `file` path (Optionino will include and render that PHP/HTML file inside the tab).

### Minimal Config

```php
OPTNNO::set_config('custom_settings', array(
    'dev_title'      => 'Custom Plugin Settings',
    'dev_version'    => '1.0.0',
    'logo_url'       => plugins_url('/assets/img/plugin-logo.png', __FILE__),
    'dev_textdomain' => 'my_plugin_textdomain',
    'menu_type'      => 'menu', // or 'submenu'
    'menu_title'     => 'Plugin Settings',
    'page_title'     => 'Plugin Options',
    'page_capability'=> 'manage_options',
    'page_slug'      => 'custom_settings_options',
    'icon_url'       => plugins_url('/assets/img/icon.png', __FILE__),
    'menu_priority'  => 60,
    'admin_bar'      => false,
));
```

## File-Backed Tabs

You can now attach a file to any tab so its content appears inside that tab. This is useful for custom reports, dashboards, help pages, or complex UIs.

```php
// A tab that only renders a PHP/HTML file:
OPTNNO::set_tab('custom_settings', array(
    'id'    => 'report_tab',
    'title' => 'Report',
    'icon'  => 'dashicons-media-document',
    'file'  => plugin_dir_path(__FILE__) . 'views/report-tab.php', // absolute path is safest
));
```

You can also **combine** a file with fields; the file is rendered first, then fields:

```php
OPTNNO::set_tab('custom_settings', array(
    'id'    => 'advanced_tab',
    'title' => 'Advanced',
    'icon'  => 'dashicons-admin-tools',
    'file'  => plugin_dir_path(__FILE__) . 'views/advanced-top.php',
    'fields'=> array(
        array(
            'id'      => 'enable_foo',
            'type'    => 'switcher',
            'title'   => 'Enable Foo',
            'default' => false,
        ),
    ),
));
```

> **Path tips:**  
> • Use absolute paths (e.g., `plugin_dir_path(__FILE__) . 'views/...'`).  
> • Relative paths will be resolved against `ABSPATH`. Ensure the file exists and is readable.

## Field Types ⚙️

- **Text** – Single-line input. ✏️  
- **Textarea** – Multi-line input. 📝  
- **TinyMCE** – Rich text editor. 🖋️  
- **Image** – Media upload/select. 🖼️  
- **Color** – Color picker. 🎨  
- **Buttonset** – Labeled radio group. 🔘  
- **Switcher** – On/Off toggle. 🔄  
- **Select** – Dropdown. ⬇️  
- **Repeater** – Repeatable sub-fields. ➕  
- **checkCondition** – Conditional display:  
  ```php
  'checkCondition' => array('field' => 'other_field_id', 'value' => true)
  ```

## Examples 💡

### Example: Helper + Tab with Fields

```php
function get_custom_options($field) {
    return optionino_get('custom_settings', $field);
}

OPTNNO::set_tab('custom_settings', array(
    'id'     => 'basic_settings',
    'title'  => 'General Settings',
    'desc'   => 'Basic options for the plugin configuration',
    'fields' => array(
        array(
            'id'      => 'site_title',
            'type'    => 'text',
            'title'   => 'Site Title',
            'default' => 'My WordPress Site',
        ),
        array(
            'id'      => 'site_description',
            'type'    => 'textarea',
            'title'   => 'Site Description',
            'default' => 'This is my WordPress website.',
        ),
        array(
            'id'      => 'editor_content',
            'type'    => 'tinymce',
            'title'   => 'Custom Content',
            'default' => '<p>Welcome to my site!</p>',
        ),
        array(
            'id'      => 'header_logo',
            'type'    => 'image',
            'title'   => 'Header Logo',
            'default' => plugins_url('/assets/img/logo.png', __FILE__),
        ),
        array(
            'id'      => 'primary_color',
            'type'    => 'color',
            'title'   => 'Primary Color',
            'default' => '#3498db',
        ),
        array(
            'id'      => 'layout_option',
            'type'    => 'buttonset',
            'title'   => 'Layout Type',
            'options' => array(
                'boxed'     => 'Boxed',
                'fullwidth' => 'Full Width',
            ),
            'default' => 'boxed',
        ),
        array(
            'id'      => 'enable_custom_style',
            'type'    => 'switcher',
            'title'   => 'Enable Custom Styles',
            'default' => true,
        ),
        array(
            'id'      => 'footer_style',
            'type'    => 'select',
            'title'   => 'Footer Style',
            'options' => array(
                'simple'   => 'Simple',
                'extended' => 'Extended',
            ),
            'default' => 'simple',
        ),
        array(
            'id'      => 'social_links',
            'type'    => 'repeater',
            'title'   => 'Social Media Links',
            'fields'  => array(
                array(
                    'id'    => 'social_platform',
                    'type'  => 'text',
                    'title' => 'Platform',
                ),
                array(
                    'id'    => 'social_url',
                    'type'  => 'text',
                    'title' => 'URL',
                ),
            ),
        ),
        array(
            'id'      => 'show_extra_options',
            'type'    => 'switcher',
            'title'   => 'Show Extra Options',
            'default' => false,
        ),
        array(
            'id'      => 'extra_setting',
            'type'    => 'color',
            'title'   => 'Extra Setting Color',
            'default' => '#ff5733',
            'checkCondition' => array(
                'field' => 'show_extra_options',
                'value' => true,
            ),
        ),
    ),
));
```

## Troubleshooting 🛠️

- **404 on CSS/JS:** The loader defines `OPTNNO_ASSETS` as a **URL** based on `plugins_url()`. Make sure you included the framework via its actual location, and clear the browser cache (hard refresh).  
- **Undefined constants (e.g., `OPTNNO_TMPL`):** The loader defines all constants before it decides to skip loading files. If you’re including Optionino from multiple places, ensure **only one copy** is actively loaded — the new loader will keep the highest version active and still define constants globally.  
- **Textdomain/Translations:** The framework loads textdomain from `OPTNNO_LANG`. If your structure is non-standard, consider manually calling `load_textdomain()` with the absolute path to your `.mo` file.

## License 📄

Optionino Framework is licensed under the **MIT License**. Use it freely in your projects. 💙
