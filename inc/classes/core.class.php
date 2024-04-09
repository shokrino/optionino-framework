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
        public static $fields = array();
        public function __construct() {
            add_action('init', [$this,'setup']);
            add_action('after_setup_theme', [$this,'setup']);
            add_action('switch_theme', [$this,'setup']);
            add_action('wp_enqueue_scripts', [$this,'wp_scripts']);
            add_action('wp_head', [$this,'wp_head']);
            add_action('admin_menu', [$this,'create_menu']);
            add_action( 'init', [$this,'sdo_load_textdomain'] );
        }
        public static function set_config($dev_name, $settings) {
            foreach (self::$settings as $existing_config) {
                if ($existing_config['dev_name'] === $dev_name) {
                    add_action('admin_notices', function() use ($dev_name) {
                        echo __('<div class="error"><p>Configuration with ID "' . esc_html($dev_name) . '" is already in use. Please use a unique ID.</p></div>','sdo');
                    });
                    return;
                }
            }
            $settings['dev_name'] = $dev_name;
            self::$settings[$dev_name] = $settings;
        }
        public static function set_tab($dev_name, $tab_settings) {
            if (!isset(self::$tabs[$dev_name])) {
                self::$tabs[$dev_name] = array();
            }
            foreach (self::$tabs[$dev_name] as $existing_tab) {
                if ($existing_tab['id'] === $tab_settings['id']) {
                    add_action('admin_notices', function() use ($tab_settings) {
                        echo __('<div class="error"><p>Tab ID "' . esc_html($tab_settings['id']) . '" is already in use. Please use a unique ID.</p></div>','sdo');
                    });
                    return;
                }
            }
            self::$tabs[$dev_name][$tab_settings['id']] = $tab_settings;
            $fields[$tab_settings['id']] = $tab_settings['fields'];
            self::set_fields($dev_name, $fields);
        }
        public static function set_fields($dev_name, $tabfields) {
            if (!isset(self::$fields[$dev_name])) {
                self::$fields[$dev_name] = array();
            }

            $existingFieldIds = [];
            foreach (self::$fields[$dev_name] as $existingTabFields) {
                foreach ($existingTabFields as $existingField) {
                    $existingFieldIds[] = $existingField['id'];
                }
            }

            foreach ($tabfields as $tab => $fields) {
                $tabFieldIds = array_column($fields, 'id');
                foreach ($tabFieldIds as $fieldId) {
                    if (in_array($fieldId, $existingFieldIds) || is_id_duplicate_sdo($tabFieldIds, $fieldId)) {
                        add_action('admin_notices', function() use ($fieldId, $tab) {
                            echo '<div class="error"><p>' . esc_html__('Field ID "' . $fieldId . '" is already in use. Please use a unique ID.', 'sdo') . '</p></div>';
                        });
                        return;
                    }
                }
            }

            foreach ($tabfields as $tab => $fields) {
                self::$fields[$dev_name][$tab] = $fields;
            }
        }
        public function create_menu() {
            $settings = self::$settings;
            $tabs = self::$tabs;
            if (is_array($settings)) {
                foreach ($settings as $dev_name => $settings) {
                    $tab_settings = isset($tabs[$dev_name]) ? $tabs[$dev_name] : array();
                    if ($settings['menu_type'] == 'menu') {
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
                    } elseif ($settings['menu_type'] == 'submenu') {
                        add_submenu_page(
                            $settings['page_parent'],
                            $settings['dev_title'],
                            $settings['menu_title'],
                            $settings['page_capability'],
                            $settings['page_slug'],
                            function () use ($settings) {
                                $this->page_content($settings);
                            },
                            $settings['menu_priority']
                        );
                    }
                }
            }
        }
        public function page_content($settings) {
            require_once SDO_TMPL.'setting-page.php';
            add_filter( 'admin_footer_text', [$this,'admin_footer_text'] );
        }
        public function admin_footer_text() {
            _e('Powered by <a href="http://shokrino.com/" target="_blank">ShokrinoDevOptions Framework</a>','sdo');
        }
        public function sdo_load_textdomain() {
            load_plugin_textdomain( SDO_TEXTDOMAIN, false,basename( SDO_PATH ) . '/languages/' );
        }
        public function setup() {

        }
        public function wp_scripts() {
            wp_enqueue_script('jquery');
        }
        public function admin_scripts() {
            if (!did_action('wp_enqueue_media')) {
                wp_enqueue_media();
            }
            wp_enqueue_style( 'sdo-settings-page', SDO_ASSETS.'css/setting.css', array(), false, 'all');
            wp_enqueue_script( 'sdo-settings-page', SDO_ASSETS.'js/setting.js' , array() , false , true);
            wp_localize_script( 'sdo-settings-page', 'data_sdo', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce('sdo_nonce')
            ));
        }
        public function wp_head() {

        }
    }
    new SDO;
}