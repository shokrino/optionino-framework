<?php defined( 'SDOPATH' ) || exit;
/**
 * Core Class
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
if (!class_exists('SDO')) {
    class SDO {
        private $dev_name = 'sdo_default';
        private $dev_title;
        private $dev_version;
        private $dev_textdomain;
        private $menu_type;
        private $menu_title;
        private $page_title;
        private $page_parent;
        private $page_capability;
        private $page_slug;
        private $icon_url;
        private $admin_bar;
        private $admin_bar_priority;
        private $admin_bar_icon;
        public function __construct() {
            self::main_settings();
            add_action('init', [$this,'setup']);
            add_action('after_setup_theme', [$this,'setup']);
            add_action('switch_theme', [$this,'setup']);
            add_action('wp_enqueue_scripts', [$this,'wp_scripts']);
            add_action('admin_enqueue_scripts', [$this,'admin_scripts']);
            add_action('wp_head', [$this,'wp_head']);
        }
        public function main_settings($settigs = '') {
            $default_settings = array(
                'dev_name' => 'sdo_default',
                'dev_title' => SDO_NAME,
                'dev_version' => SDO_VERSION,
                'dev_textdomain' > SDO_TEXTDOMAIN,
                'menu_type' => 'menu',
                'menu_title' => esc_html__('Default Options', 'sdo'),
                'page_title' => esc_html__('Default Options', 'sdo'),
                'page_parent' => 'options.php',
                'page_capability' => 'manage_options',
                'page_slug' => $this->dev_name.'_settings',
                'icon_url' => '',
                'admin_bar' => false,
                'admin_bar_priority' => null,
                'admin_bar_icon' => 'dashicons-menu',
            );
            $new_settings = array(
                'dev_name' => $settigs['dev_name'],
                'dev_title' => $settigs['dev_title'],
                'dev_version' => $settigs['dev_version'],
                'dev_textdomain' => $settigs['dev_textdomain'],
                'menu_type' => $settigs['menu_type'],
                'menu_title' => $settigs['menu_title'],
                'page_title' => $settigs['page_title'],
                'page_parent' => $settigs['page_parent'],
                'page_capability' => $settigs['page_capability'],
                'page_slug' => $settigs['page_slug'],
                'icon_url' => $settigs['icon_url'],
                'admin_bar' => $settigs['admin_bar'],
                'admin_bar_priority' => $settigs['admin_bar_priority'],
                'admin_bar_icon' => $settigs['admin_bar_icon'],
            );
            $to_set = array_intersect_key($new_settings, $default_settings) + $default_settings;

            foreach ($to_set as $field => $value) {
                $this->$field = $value;
            }
        }
        public function setup() {

        }
        public function wp_scripts() {

        }
        public function admin_scripts() {

        }
        public function wp_head() {

        }
    }
    new SDO;
}