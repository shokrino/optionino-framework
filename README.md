# Optionino Framework (OPTNNO) 🎛️

Optionino is a powerful, flexible settings framework for WordPress plugins and themes. It gives you a clean API to create admin pages, tabs, and fields — plus handy extras like conditional fields and repeaters. 🌟

## Table of Contents 📚

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [File-Backed Tabs](#file-backed-tabs)
- [Field Types](#field-types)
- [Conditionals (`require`)](#conditionals-require)
- [Examples](#examples)
- [Troubleshooting](#troubleshooting)
- [License](#license)

## Features ✨

- Simple, fluent API for creating options pages and tabs. 🔧  
- **Multi-instance safe loader**: bundle Optionino inside multiple plugins/themes without conflicts (highest version wins, constants/functions/classes are guarded). 🧩  
- Correct **asset URLs** for enqueues. 🌐  
- **File-backed tabs**: render any PHP/HTML file inside a tab. 🗂️  
- Rich field set: `text`, `textarea`, `tinymce`, `image`, `color`, `buttonset`, `switcher`, `select`, `repeater`. 🛠️  
- **Conditional fields via `require`** for showing/hiding fields based on other values. ✔️  
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
   > The loader is multi-instance safe. If multiple copies exist across plugins/themes, the **newest** version activates; global constants remain defined to avoid breaks.

## Usage 📝

Define your settings with `OPTNNO::set_config()` and your tabs with `OPTNNO::set_tab()`.

- `set_config($dev_name, $settings)` registers the options page and top-level config.  
- `set_tab($dev_name, $tab_settings)` adds tabs. Each tab can contain:
  - a `fields` array (rendered by Optionino), and/or
  - a `file` path (Optionino will include that PHP/HTML file inside the tab).

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

Attach a file to any tab so its content appears inside that tab. Useful for reports, dashboards, help pages, or complex UIs.

```php
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
> • Use absolute paths (`plugin_dir_path(__FILE__) . 'views/...'`).  
> • Relative paths resolve against `ABSPATH`.

## Field Types ⚙️

- **text** – Single-line input.  
- **textarea** – Multi-line input.  
- **tinymce** – Rich text editor.  
- **image** – Media upload/select.  
- **color** – Color picker.  
- **buttonset** – Labeled radio group.  
- **switcher** – On/Off toggle.  
- **select** – Dropdown.  
- **repeater** – Repeatable sub-fields.

> Every field **must have an `id`**. If a non-input field (like a note) is needed, prefer `textarea` or `text` with `desc`, still with an `id`.

## Conditionals (`require`) ✅

Optionino displays fields conditionally using the `require` key on a field. A `require` value is an **array of rules**, optionally with a top-level `'relation' => 'AND'|'OR'`.

### Basic forms

- **Equality / Inequality**
  ```php
  'require' => array(
    array('provider', '==', 'farazsms'),
    // or
    array('provider', '!=', 'farazsms'),
  )
  ```

- **Membership (OR / IN)**
  ```php
  'require' => array(
    array('provider', 'in', array('farazsms','maxsms','modirpayamak','panelsmspro','rangine')),
    // Alias also seen in some configs:
    // array('provider', 'or', array('farazsms','maxsms', ...)),
  )
  ```

- **NOT IN**
  ```php
  'require' => array(
    array('provider', 'not_in', array('legacyA','legacyB')),
  )
  ```

- **Boolean (switcher)**
  ```php
  'require' => array(
    array('enable_feature', '==', true),
  )
  ```

- **Empty / Not Empty**
  ```php
  'require' => array(
    array('api_key', 'not_empty'), // or 'empty'
  )
  ```

- **Numeric comparisons** (for `type:number`)
  ```php
  'require' => array(
    array('retry_count', '>=', 3), // also >, <, <=
  )
  ```

### Multiple rules with relation

- **AND**
  ```php
  'require' => array(
    'relation' => 'AND',
    array('captcha_provider', '==', 'turnstile'),
    array('captcha_on_login', '==', true),
  )
  ```

- **OR**
  ```php
  'require' => array(
    'relation' => 'OR',
    array('mode', '==', 'sandbox'),
    array('api_key', 'empty'),
  )
  ```

> Use field **`id` values** in rules (not labels). For `select/buttonset`, compare against the **option keys**.

## Examples 💡

### Helper + General Tab

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
            'id'      => 'enable_custom_style',
            'type'    => 'switcher',
            'title'   => 'Enable Custom Styles',
            'default' => true,
        ),
        array(
            'id'      => 'style_provider',
            'type'    => 'select',
            'title'   => 'Style Provider',
            'options' => array(
                'classic' => 'Classic',
                'modern'  => 'Modern',
            ),
            'default' => 'classic',
            'require' => array(
                array('enable_custom_style', '==', true),
            ),
        ),
        array(
            'id'      => 'advanced_color',
            'type'    => 'color',
            'title'   => 'Advanced Accent',
            'default' => '#ff5733',
            'require' => array(
                'relation' => 'AND',
                array('enable_custom_style', '==', true),
                array('style_provider', 'in', array('modern')),
            ),
        ),
    ),
));
```

### Real-world conditional (like your SMS example)

```php
array(
  'id'      => 'sms_credentials',
  'type'    => 'textarea',
  'title'   => 'SMS Credentials (JSON)',
  'desc'    => 'Provide credentials for the selected provider.',
  'require' => array(
    array('sms_provider', 'in', array('farazsms','maxsms','modirpayamak','panelsmspro','rangine')),
  ),
),
```

## Troubleshooting 🛠️

- **Undefined array key "id" warnings:** Ensure **every field has an `id`** (even “note”-like fields).  
- **Condition not working:** Double-check the controlling field’s **`id`** and compare to its **value** (option key), not label. For booleans (`switcher`), use `true/false`.  
- **Assets 404:** Confirm the loader path and URLs. Clear caches / hard refresh.  
- **Multiple copies:** The highest framework version should win; avoid double-including loaders manually.

## License 📄

Optionino Framework is licensed under the **MIT License**. Use it freely in your projects. 💙
