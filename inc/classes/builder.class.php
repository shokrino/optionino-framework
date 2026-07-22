<?php defined( 'ABSPATH' ) || exit;
/**
 * Builder Class for Optionino Framework
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
if (!class_exists('OPTNNO_Builder', false)) {
class OPTNNO_Builder {
        public function __construct() {}
        public static function logo($dev_name) {
            $settingsArray = OPTNNO::$settings;
            $settings = isset($settingsArray[$dev_name]) ? $settingsArray[$dev_name] : array();
            if (isset($settings['logo_url']) && !empty($settings['logo_url'])) {
                return esc_url($settings['logo_url']);
            }
            return esc_url(optnno_assets() . 'img/logo.png');
        }
        public static function title($title) {
            echo '<h1 class="optionino-header-title wp-heading-inline">'.esc_html($title).'</h1>';
        }
        public static function container_start() {
            ?>
            <div id="optionino" class="optionino-container wrap">
            <?php
        }
        public static function container_end() {
            ?>
            </div>
            <script>
                function openTabOptnno(evt, tabName) {
                    const tabcontent = document.getElementsByClassName("tabcontent");
                    for (let i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }

                    const tablinks = document.getElementsByClassName("tablinks");
                    for (let i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                    }

                    document.getElementById(tabName).style.display = "block";
                    evt.currentTarget.className += " active";
                }
            </script>
            <?php
        }
        public static function loading() {
            echo '<div class="loading-spinner-optionino"><svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                <path fill="currentColor" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                    <animateTransform
                            attributeName="transform"
                            attributeType="XML"
                            type="rotate"
                            dur="1s"
                            from="0 50 50"
                            to="360 50 50"
                            repeatCount="indefinite" />
                </path>
            </svg></div>';
        }
        public static function form_start() {
            echo '<form action="" method="POST" id="save-options-optionino" class="optionino-tab-content-outer">';
            echo '<div class="success-text"></div><div class="error-text"></div>';
        }
        public static function form_end($dev_name) {
            echo '<input type="hidden" name="dev_name" value="'.esc_attr($dev_name).'">
                   <div class="optionino-options-save-box">
                <button type="submit" class="submit-optionino" name="save-optionino">'.esc_html__('save changes', OPTNNO_TEXTDOMAIN).'</button>
            </div>
            </form>';
        }
        public static function section_start($class) {
            if (is_rtl()) {
                $class = $class.' rtl';
            }
            echo '<div class="optionino-section flex section-options-'.esc_attr($class).'">';
        }
        public static function section_end() {
            echo '</div>';
        }
        public static function tab_start($dev_name,$version) {
            echo '<div class="tab">
            <div class="tab-information flex-center">
                <img src="'.esc_url(self::logo($dev_name)).'" alt="'.esc_attr__('Logo', OPTNNO_TEXTDOMAIN).'">
                <span>'.esc_html__('Version: ', OPTNNO_TEXTDOMAIN).'<strong>'.esc_html($version).'</strong></span>
            </div>';
        }
        public static function tab_end() {
            echo '</div>';
        }
        public static function tab_buttons($dev_name) {
            $tabsArray = OPTNNO::$tabs;
            if (isset($tabsArray[$dev_name]) && is_array($tabsArray[$dev_name])) {
                foreach ($tabsArray[$dev_name] as $tab) {
                    ?>
                    <button class="tablinks flex" onclick="openTabOptnno(event, '<?php echo esc_js($tab['id']); ?>')">
                        <div class="optionino-tab-titles-box">
                            <div class="optionino-title-tab-options">
                                <?php echo esc_html($tab['title']); ?>
                            </div>
                            <div class="optionino-desc-tab-options">
                                <?php echo esc_html($tab['desc']); ?>
                            </div>
                        </div>
                        <?php
                        if (!empty($tab['svg_logo'])) {
                            echo wp_kses( $tab['svg_logo'], array(
                                'svg' => array('xmlns' => true, 'class' => true, 'width' => true, 'height' => true, 'viewBox' => true, 'stroke-width' => true, 'stroke' => true, 'fill' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true),
                                'path' => array('stroke' => true, 'd' => true, 'fill' => true),
                                'circle' => array('cx' => true, 'cy' => true, 'r' => true),
                                'line' => array('x1' => true, 'y1' => true, 'x2' => true, 'y2' => true),
                            ) );
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
        public static function form_fields($dev_name) {
            $transient_key = 'optnno_html_' . md5($dev_name);
            $cached_html = get_transient($transient_key);
            if ( $cached_html !== false ) {
                echo $cached_html;
                return;
            }
            
            ob_start();
            $tabsArray = OPTNNO::$tabs;
            if (isset($tabsArray[$dev_name]) && is_array($tabsArray[$dev_name])) {
                foreach ($tabsArray[$dev_name] as $tab) { ?>
                    <div id="<?php echo esc_attr($tab['id']); ?>" class="tabcontent">
                        <?php
                        if ( ! empty( $tab['file'] ) && is_string( $tab['file'] ) ) {
                            $path = $tab['file'];

                            if ( strpos($path, '://') === false && ! preg_match('#^([a-zA-Z]:[\\\\/]|/)#', $path) ) {
                                $path = ABSPATH . ltrim($path, '/\\');
                            }

                            if ( file_exists( $path ) && is_readable( $path ) ) {
                                include $path;
                            } else {
                                echo '<div class="notice notice-error"><p>' . esc_html__('Assigned file not found or not readable.', OPTNNO_TEXTDOMAIN) . '</p></div>';
                            }
                        }

                        $fields = isset($tab['fields']) && is_array($tab['fields']) ? $tab['fields'] : array();
                        if (!empty($fields)) {
                            foreach ($fields as $field) {
                                OPTNNO_Builder::field_option($dev_name, $field);
                            }
                        }
                        ?>
                    </div>
                    <?php
                }
            }
            $html = ob_get_clean();
            set_transient($transient_key, $html, 30 * DAY_IN_SECONDS);
            echo $html;
        }

        public static function field_option($dev_name, $field, $repeater = false, $index = 0, $currentValue = "") {
            ?>
            <div id="container-<?php echo esc_attr($field['id'] . "-" . $index); ?>" class="optionino-box-option optionino-conditional-option"
                 display="true" <?php if ($repeater) { echo 'repeater-name="' . esc_attr($field["id"]) . '"'; } ?>
                <?php if (isset($field['require']) && is_array($field['require'])) { // Check if 'require' exists and is an array ?>
                    <?php foreach ($field['require'] as $reqIndex => $require) { ?>
                        data-require-<?php echo esc_attr($reqIndex); ?>='<?php echo esc_attr(json_encode($require, JSON_UNESCAPED_UNICODE)); ?>'
                    <?php } ?>
                <?php } ?>>
                <?php
                $type = isset($field['type']) ? $field['type'] : 'text';
                $currentValue = $repeater ? $currentValue : optionino_get($dev_name, $field['id']);
                $class_name = 'OPTNNO_Field_' . ucfirst($type);
                if (class_exists($class_name)) {
                    $index_repeater = $repeater ? "_" . $index : "";
                    $class_name::render($dev_name, $field, $currentValue, $index_repeater);
                }
                ?>
            </div>
            <?php
        }
        public static function checkCondition($value, $operator, $requiredValue) {
            switch ($operator) {
                case '=':
                    return $value == $requiredValue;
                case '!=':
                    return $value != $requiredValue;
                case '>':
                    return $value > $requiredValue;
                case '<':
                    return $value < $requiredValue;
                default:
                    return false;
            }
        }
    }
}

