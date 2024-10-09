# Optionino Framework (OPTNNO) 🎛️

Optionino is a powerful and flexible settings framework for WordPress plugins and themes. It provides an easy way to create, manage, and store custom settings using a simple API. With support for various field types and options, Optionino allows developers to customize the admin experience effortlessly. 🌟

## Table of Contents 📚

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Field Types](#field-types)
- [Example](#example)
- [License](#license)

## Features ✨

- Easy-to-use API for managing plugin settings. 🔧
- Support for multiple field types including text, textarea, tinymce, image, color, buttonset, switcher, select, and repeater. 🛠️
- CheckCondition feature for optional fields based on other settings. ✔️
- Fully customizable options page in the WordPress admin. 🖥️

## Installation 🚀

1. **Download** the Optionino Framework files.
2. **Add** the `optionino-framework` directory to your plugin or theme directory.
3. **Include** the framework in your plugin or theme by adding the following lines to your main plugin or theme file:

   ```php
   require_once 'optionino-framework/optionino-framework.php';
   require_once 'optionino-config.php';
   ```

4. **Configure** your settings in `optionino-config.php`.

## Usage 📝

To use the Optionino Framework, you need to define your settings using the `set_config` and `set_tab` methods. Here’s an example configuration:

### Example Configuration 💡

```php
function get_custom_options($field) {
    return optionino_get('custom_settings', $field);
}
define('OPTNNO_TEXTDOMAIN', 'my_plugin_textdomain');

OPTNNO::set_config('custom_settings', array(
    'dev_title'      => 'Custom Plugin Settings',
    'dev_version'    => '1.0.0',
    'logo_url'       => plugins_url('/assets/img/plugin-logo.png', __FILE__),
    'dev_textdomain' => 'my_plugin_textdomain',
    'menu_type'      => 'menu',
    'menu_title'     => 'Plugin Settings',
    'page_title'     => 'Plugin Options',
    'page_capability'=> 'manage_options',
    'page_slug'      => 'custom_settings_options',
    'icon_url'       => get_custom_options('enable_custom_style') !== "off" ? plugins_url('/assets/img/icon.png', __FILE__) : '',
    'menu_priority'  => 60,
    'admin_bar'      => false,
));

OPTNNO::set_tab('custom_settings', array(
    'id'    => 'basic_settings',
    'title' => 'General Settings',
    'desc'  => 'Basic options for the plugin configuration',
    'fields' => array(
        array(
            'id'      => 'site_title',
            'type'    => 'text',
            'title'   => 'Site Title',
            'desc'    => 'Set the title for your site.',
            'default' => 'My WordPress Site',
        ),
        array(
            'id'      => 'site_description',
            'type'    => 'textarea',
            'title'   => 'Site Description',
            'desc'    => 'Provide a short description for your site.',
            'default' => 'This is my WordPress website.',
        ),
        array(
            'id'      => 'editor_content',
            'type'    => 'tinymce',
            'title'   => 'Custom Content',
            'desc'    => 'Add custom content to display.',
            'default' => '<p>Welcome to my site!</p>',
        ),
        array(
            'id'      => 'header_logo',
            'type'    => 'image',
            'title'   => 'Header Logo',
            'desc'    => 'Upload your custom logo for the header.',
            'default' => plugins_url('/assets/img/logo.png', __FILE__),
        ),
        array(
            'id'      => 'primary_color',
            'type'    => 'color',
            'title'   => 'Primary Color',
            'desc'    => 'Choose the primary color for your theme.',
            'default' => '#3498db',
        ),
        array(
            'id'      => 'layout_option',
            'type'    => 'buttonset',
            'title'   => 'Layout Type',
            'desc'    => 'Choose between boxed or full-width layout.',
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
            'desc'    => 'Enable or disable the custom styling for the admin panel.',
            'default' => true,
        ),
        array(
            'id'      => 'footer_style',
            'type'    => 'select',
            'title'   => 'Footer Style',
            'desc'    => 'Choose the footer style for your site.',
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
            'desc'    => 'Add your social media links.',
            'fields'  => array(
                array(
                    'id'    => 'social_platform',
                    'type'  => 'text',
                    'title' => 'Platform',
                    'desc'  => 'Name of the social media platform.',
                ),
                array(
                    'id'    => 'social_url',
                    'type'  => 'text',
                    'title' => 'URL',
                    'desc'  => 'URL of your social media profile.',
                ),
            ),
        ),
        array(
            'id'      => 'show_extra_options',
            'type'    => 'switcher',
            'title'   => 'Show Extra Options',
            'desc'    => 'Enable to show additional settings below.',
            'default' => false,
        ),
        array(
            'id'      => 'extra_setting',
            'type'    => 'color',
            'title'   => 'Extra Setting Color',
            'desc'    => 'This option only appears when "Show Extra Options" is enabled.',
            'default' => '#ff5733',
            'checkCondition' => array(
                'field' => 'show_extra_options',
                'value' => true,
            ),
        ),
    ),
));
```

## Field Types ⚙️

- **Text**: A single-line text input. ✏️
- **Textarea**: A multi-line text input. 📝
- **TinyMCE**: A rich text editor. 🖋️
- **Image**: An upload field for images. 🖼️
- **Color**: A color picker. 🎨
- **Buttonset**: A set of radio buttons. 🔘
- **Switcher**: A toggle switch. 🔄
- **Select**: A dropdown selection. ⬇️
- **Repeater**: A field that allows multiple entries of sub-fields. ➕ 

## License 📄

Optionino Framework is licensed under the MIT License. Feel free to use it in your projects. 
