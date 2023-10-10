<?php defined( 'SDOPATH' ) || exit;
/**
 * Config SDO framework
 */
SDO::main_config('sdo_default',array(
    'dev_title' => SDO_NAME,
    'dev_version' => SDO_VERSION,
    'dev_textdomain' > SDO_TEXTDOMAIN,
    'menu_type' => 'menu',
    'menu_title' => esc_html__('Default Options', 'sdo'),
    'page_title' => esc_html__('Default Options', 'sdo'),
    'page_parent' => 'options.php',
    'page_capability' => 'manage_options',
    'page_slug' => 'sdo_default'.'_settings',
    'icon_url' => '',
    'menu_priority' => 10,
    'admin_bar' => false,
    'admin_bar_priority' => null,
    'admin_bar_icon' => 'dashicons-menu',
));

SDO::main_config('sdo_drfault',array(
    'dev_title' => 'fdfdfdfdf',
    'dev_version' => '1.0.1',
    'dev_textdomain' > 'sdsdsd',
    'menu_type' => 'menu',
    'menu_title' => esc_html__('Defrrrult Options', 'sdo'),
    'page_title' => esc_html__('Defrrrult Options', 'sdo'),
    'page_parent' => 'options.php',
    'page_capability' => 'manage_options',
    'page_slug' => 'sdo_drfault'.'_settings',
    'page_content' => array(
        5
    ),
    'icon_url' => '',
    'menu_priority' => 11,
    'admin_bar' => false,
    'admin_bar_priority' => null,
    'admin_bar_icon' => 'dashicons-menu',
));
