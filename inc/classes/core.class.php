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
        public static $settings = array();
        public static $tabs = array();
        public function __construct() {
            add_action('init', [$this,'setup']);
            add_action('after_setup_theme', [$this,'setup']);
            add_action('switch_theme', [$this,'setup']);
            add_action('wp_enqueue_scripts', [$this,'wp_scripts']);
            add_action('admin_enqueue_scripts', [$this,'admin_scripts']);
            add_action('wp_head', [$this,'wp_head']);
            add_action('admin_menu', [$this,'create_menu']);
        }
        public static function set_config($dev_name, $settings) {
            self::$settings[$dev_name] = $settings;
        }
        public static function set_tab($dev_name, $tab_settings) {
            self::$tabs[$dev_name] = $tab_settings;
        }
        public function create_menu() {
            $settings = self::$settings;
            $tabs = self::$tabs;
            if (is_array($settings)) {
                foreach ($settings as $dev_name => $settings) {
                    $tab_settings = isset($tabs[$dev_name]) ? $tabs[$dev_name] : array();
                    add_menu_page(
                        $settings['dev_title'],
                        $settings['menu_title'],
                        $settings['page_capability'],
                        $settings['page_slug'],
                        function () use ($settings) {
                            $this->page_content($settings);
                        },
                        $settings['icon_url'],
                        $settings['menu_priority']
                    );
                }
            }
        }
        public function page_content($settings) {
            add_filter( 'admin_footer_text', [$this,'admin_footer_text'] );
            echo '<div class="wrap">';
            echo '<h1>' . $settings['menu_title'] . '</h1>';
            echo '</div>';
        }
        public function admin_footer_text() {
            _e('Powered by <a href="http://shokrino.com/" target="_blank">ShokrinoDevOptions Framework</a>','sdo');
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