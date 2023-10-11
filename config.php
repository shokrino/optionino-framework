<?php defined( 'SDOPATH' ) || exit;
/**
 * Config SDO framework
 */
SDO::set_config('sdo_default',array(
    'dev_title' => SDO_NAME,
    'dev_version' => SDO_VERSION,
    'logo_url' => '',
    'dev_textdomain' > SDO_TEXTDOMAIN,
    'menu_type' => 'menu',
    'menu_title' => esc_html__('Default Options', 'sdo'),
    'page_title' => esc_html__('Default Options', 'sdo'),
    'page_parent' => 'themes.php',
    'page_capability' => 'manage_options',
    'page_slug' => 'sdo_default'.'_settings',
    'icon_url' => '',
    'menu_priority' => 10,
    'admin_bar' => false,
    'admin_bar_priority' => null,
    'admin_bar_icon' => 'dashicons-menu',
));

SDO::set_tab('sdo_default',array(
    'id' => 'tab_general',
    'title' => esc_html__('General Tab', 'sdo'),
    'desc' => esc_html__('General Tab', 'sdo'),
    'svg_logo' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-adjustments-horizontal" width="30"
             height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="#e2e2e2" fill="none" stroke-linecap="round"
             stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <circle cx="14" cy="6" r="2"/>
            <line x1="4" y1="6" x2="12" y2="6"/>
            <line x1="16" y1="6" x2="20" y2="6"/>
            <circle cx="8" cy="12" r="2"/>
            <line x1="4" y1="12" x2="6" y2="12"/>
            <line x1="10" y1="12" x2="20" y2="12"/>
            <circle cx="17" cy="18" r="2"/>
            <line x1="4" y1="18" x2="15" y2="18"/>
            <line x1="19" y1="18" x2="20" y2="18"/>
        </svg>',
    'fields' => array(
        array(
            'id' => 'test-option',
            'type' => 'text',
            'title' => esc_html__('Test Text', 'sdo'),
            'subtitle' => esc_html__('test subtitle', 'sdo'),
            'desc' => esc_html__('test description', 'sdo'),
            'default' => esc_html__('Default Text', 'sdo'),
        ),
    ),
));

SDO::set_tab('sdo_default',array(
    'id' => 'tab_general_2',
    'title' => esc_html__('General Tab 2', 'sdo'),
    'desc' => esc_html__('General Tab 2', 'sdo'),
    'icon' => 'el-icon-home',
    'fields' => array(
        array(
            'id' => 'test-option-1',
            'type' => 'text',
            'title' => esc_html__('Test Text', 'sdo'),
            'subtitle' => esc_html__('test subtitle', 'sdo'),
            'desc' => esc_html__('test description', 'sdo'),
            'default' => esc_html__('Default Text', 'sdo'),
        ),
    ),
));


//SDO::set_config('sdo_drfault',array(
//    'dev_title' => 'fdfdfdfdf',
//    'dev_version' => '1.0.1',
//    'dev_textdomain' > 'sdsdsd',
//    'menu_type' => 'menu',
//    'menu_title' => esc_html__('Defrrrult Options', 'sdo'),
//    'page_title' => esc_html__('Defrrrult Options', 'sdo'),
//    'page_parent' => 'options.php',
//    'page_capability' => 'manage_options',
//    'page_slug' => 'sdo_drfault'.'_settings',
//    'page_content' => array(
//        5
//    ),
//    'icon_url' => '',
//    'menu_priority' => 11,
//    'admin_bar' => false,
//    'admin_bar_priority' => null,
//    'admin_bar_icon' => 'dashicons-menu',
//));
