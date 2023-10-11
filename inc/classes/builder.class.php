<?php defined( 'SDOPATH' ) || exit;
/**
 * Builder Class
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
if (!class_exists('SDO')) {
    class SDO_Builder {
        public function __construct() {}
        public static function logo($dev_name) {
            $settingsArray = SDO::$settings;
            $settings = $settingsArray[$dev_name];
            $logo_url = $settings['logo_url'];
            if (!empty($logo_url)) {
                return $logo_url;
            }
            return SDO_ASSETS.'img/logo.png';
        }
        public static function title($title) {
            echo '<h1 class="sdo-header-title wp-heading-inline">'.$title.'</h1>';
        }
        public static function container_start() {
            echo '<div id="doco" class="sdo-container wrap">';
        }
        public static function container_end() {
            echo '</div>';
        }
        public static function section_start($class) {
            echo '<div class="sdo-section flex section-options-'.$class.'">';
        }
        public static function section_end() {
            echo '</div>';
        }
        public static function tab_start($dev_name,$version) {
            echo '<div class="tab">
            <div class="tab-information flex-center">
                <img src="'.self::logo($dev_name).'">
                <span>'.__('Version: ', 'sdo').'<strong>'.$version.'</strong></span>
            </div>';
        }
        public static function tab_end() {
            echo '</div>';
        }
    }
}