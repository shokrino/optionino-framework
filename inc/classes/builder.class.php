<?php defined( 'SDOPATH' ) || exit;
/**
 * Builder Class
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
if (!class_exists('SDO_Builder')) {
    class SDO_Builder {
        public function __construct() {}
        public static function logo($dev_name) {
            $settingsArray = SDO::$settings;
            foreach ($settingsArray as $dev_name => $settings) {
                $option = get_option($dev_name,NULL);
                if (is_null($option) or empty($option) or $option == []) {
                    SDO_Ajax_Handler::defaults($dev_name);
                }
            }
            $settings = $settingsArray[$dev_name];
            if (isset($settings)) {
                $logo_url = $settings["logo_url"];
                if (!empty($logo_url)) {
                    return $logo_url;
                }
            }
            return SDO_ASSETS . 'img/logo.png';
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
        public static function loading() {
            echo '<div class="loading-spinner-sdo"><svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
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
            echo '<form action="" method="POST" id="save-options-sdo" class="sdo-tab-content-outer">';
            echo '<div class="success-text"></div><div class="error-text"></div>';
        }
        public static function form_end($dev_name) {
            echo '<input type="hidden" name="dev_name" value="'.$dev_name.'">
                   <div class="sdo-options-save-box">
                <button type="submit" class="submit-sdo" name="save-sdo">'.__('save changes', 'sdo').'</button>
            </div>
            </form>';
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
                    <button class="tablinks flex" onclick="openTabSDO(event, '<?php echo $tab['id']; ?>')">
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
        public static function form_fields($dev_name) {
            $tabsArray = SDO::$tabs;
            if (isset($tabsArray[$dev_name]) && is_array($tabsArray[$dev_name])) {
                foreach ($tabsArray[$dev_name] as $tab) { ?>
                    <div id="<?php echo $tab['id']; ?>" class="tabcontent">
                        <?php
                        $fields = $tab['fields'];
                        if (isset($fields) && is_array($fields)) {
                            foreach ($fields as $field) {
                                SDO_Builder::field_option($dev_name,$field);
                            }
                        }
                        ?>
                    </div>
                    <?php
                }
            }
        }
        public static function field_option($dev_name, $field) {
            ?>
            <div class="sdo-box-option<?php if (!empty($field['require'][0])){ echo " sdo-conditional-option"; } ?>" data-require="<?php echo $field['require'][0]; ?>"
                 data-require-operator="<?php echo $field['require'][1]; ?>"
                 data-require-value="<?php echo $field['require'][2]; ?>">
                <?php
                $type = $field['type'];
                $currentValue = sdo_option($dev_name, $field['id']);
                if (method_exists(__CLASS__, $type)) {
                    self::$type($dev_name, $field, $currentValue);
                }
                ?>
            </div>
            <?php
        }
        public static function text($dev_name, $field,$currentValue) {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] : '';
            $value = !empty($currentValue) ? $currentValue : '';
            if (isset($field['require']) && is_array($field['require']) && count($field['require']) == 3) {
                list($requiredField, $operator, $requiredValue) = $field['require'];
                $requiredFieldValue = sdo_option($dev_name, $requiredField);
                if (!self::checkCondition($requiredFieldValue, $operator, $requiredValue)) {
                    return;
                }
            }
            ?>
            <label class="sdo-form-label" for="<?php echo $name; ?>"><?php echo $title; ?></label>
            <input type="text" class="sdo-input" id="<?php echo $name; ?>" name="<?php echo $name; ?>"
                   value="<?php echo $value; ?>">
            <p><?php echo $desc; ?></p>
            <?php
        }
        public static function textarea($dev_name, $field,$currentValue) {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] : '';
            $value = !empty($currentValue) ? $currentValue : '';
            if (isset($field['require']) && is_array($field['require']) && count($field['require']) == 3) {
                list($requiredField, $operator, $requiredValue) = $field['require'];
                $requiredFieldValue = sdo_option($dev_name, $requiredField);
                if (!self::checkCondition($requiredFieldValue, $operator, $requiredValue)) {
                    return;
                }
            }
            ?>
            <label class="sdo-form-label" for="<?php echo $name; ?>"><?php echo $title; ?></label>
            <textarea class="sdo-input" id="<?php echo $name; ?>" name="<?php echo $name; ?>">
                <?php echo $value; ?>
            </textarea>
            <p><?php echo $desc; ?></p>
            <?php
        }
        public static function buttonset($dev_name, $field, $currentValue) {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] : '';
            $options = !empty($field['options']) && is_array($field['options']) ? $field['options'] : array();
            $value = !empty($currentValue) ? $currentValue : '';
            if (isset($field['require']) && is_array($field['require']) && count($field['require']) == 3) {
                list($requiredField, $operator, $requiredValue) = $field['require'];
                $requiredFieldValue = sdo_option($dev_name, $requiredField);
                if (!self::checkCondition($requiredFieldValue, $operator, $requiredValue)) {
                    return;
                }
            }
            echo '<label class="sdo-form-label">' . $title . '</label>';
            echo '<div class="sdo-button-set-box flex">';
            $index = 0;
            foreach ($options as $key => $label) {
                $index++;
                $id = $name.$index;
                if ($key != $value && $index == 1) {
                    $active = true;
                } elseif ($key == $value) {
                    $active = true;
                } else {
                    $active = false;
                }
                $checked = ($active) ? 'checked' : '';
                echo '<input type="radio" class="sdo-radio button-set" id="' . $id . '" name="' . $name . '" value="' . esc_attr($key) . '" ' . $checked . '>';
                echo '<label class="sdo-button-label flex" for="'.$id.'">' . esc_html($label) . '</label>';
            }
            echo '</div>';
            echo '<p>' . $desc . '</p>';
        }
        public static function switcher($dev_name, $field, $currentValue) {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] : '';
            $value = ($currentValue === "on") ? true : filter_var($currentValue, FILTER_VALIDATE_BOOLEAN);
            if (isset($field['require']) && is_array($field['require']) && count($field['require']) == 3) {
                list($requiredField, $operator, $requiredValue) = $field['require'];
                $requiredFieldValue = sdo_option($dev_name, $requiredField);
                if (!self::checkCondition($requiredFieldValue, $operator, $requiredValue)) {
                    return;
                }
            }

            echo '<label class="sdo-form-label">' . $title . '</label>';
            echo '<div class="sdo-switch-box flex">';
            $id = $name . '_switch';
            $checked = ($value == "on") ? 'checked' : '';
            echo '<input type="checkbox" class="sdo-switch-checkbox sdo-radio" id="' . $id . '" name="' . $name . '" ' . $checked . '>';
            echo '<label class="sdo-switch-label" for="' . $id . '"></label>';
            echo '</div>';
            echo '<p>' . $desc . '</p>';
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
