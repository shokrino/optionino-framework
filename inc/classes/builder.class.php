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
            echo '<div id="sdo" class="sdo-container wrap">';
        }
        public static function container_end() {
            echo '</div>';
        }
        public static function form_start() {
            echo '<form action="" method="POST" id="save-options-sdo" class="sdo-tab-content-outer">';
        }
        public static function form_end() {
            echo '<div class="sdo-options-save-box">
                <button type="submit" class="submit-sdo" name="save-sdo">'.__('save changes', 'sdo').'</button>
            </div>
            </form>';
        }
        public static function form_fields($dev_name) {
            $tabsArray = SDO::$tabs;
            if (isset($tabsArray[$dev_name]) && is_array($tabsArray[$dev_name])) {
                foreach ($tabsArray[$dev_name] as $tab) {
                    $fields = $tab['fields'];
                    if (isset($fields) && is_array($fields)) {
                        foreach ($fields as $field) {
                            var_dump($field);
                            echo '<br>';
                        }
                    }
                }
            }
        }
        public static function section_start($class) {
            if (is_rtl()) {
                $class = $class.' rtl';
            }
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
        public static function tab_buttons($dev_name) {
            $tabsArray = SDO::$tabs;
            if (isset($tabsArray[$dev_name]) && is_array($tabsArray[$dev_name])) {
                foreach ($tabsArray[$dev_name] as $tab) {
                    ?>
                    <button class="tablinks flex" onclick="openTabSDO(event, <?php echo $tab['id']; ?>)">
                        <div class="sdo-tab-titles-box">
                            <div class="sdo-title-tab-options">
                                <?php echo $tab['title']; ?>
                            </div>
                            <div class="sdo-desc-tab-options">
                                <?php echo $tab['desc']; ?>
                            </div>
                        </div>
                        <?php
                        if (!empty($tab['svg_logo'])) {
                            echo $tab['svg_logo'];
                        } else {
                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-adjustments-horizontal" width="30"
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
                            </svg>';
                        } ?>
                    </button>
                    <?php
                }
            }
        }
    }
}