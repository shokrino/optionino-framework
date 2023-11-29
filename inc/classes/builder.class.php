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
            <div id="container-<?php echo $field['id']; ?>" class="sdo-box-option sdo-conditional-option"
                 display="true"
                <?php if (is_array($field['require'])) { ?>
                    <?php foreach ($field['require'] as $index => $require) { ?>
                        data-require-<?php echo $index; ?>='<?php echo json_encode($require, JSON_UNESCAPED_UNICODE); ?>'
                    <?php } ?>
                <?php } ?>>
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
            if (isset($field['require']) && is_array($field['require']) && count($field['require']) == 3 && !empty($value)) {
                list($requiredField, $operator, $requiredValue) = $field['require'];
                $requiredFieldValue = $value;
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
            if (isset($field['require']) && is_array($field['require'])) {
                $showField = true;

                foreach ($field['require'] as $condition) {
                    list($requiredField, $operator, $requiredValue) = $condition;
                    $requiredFieldValue = sdo_option($dev_name, $requiredField);

                    if (!self::checkCondition($requiredFieldValue, $operator, $requiredValue)) {
                        $showField = false;
                        break;
                    }
                }

                if (!$showField) {
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
        public static function tinymce($dev_name, $field, $currentValue) {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] : '';
            $value = !empty($currentValue) ? $currentValue : '';

            if (isset($field['require']) && is_array($field['require'])) {
                $showField = true;

                foreach ($field['require'] as $condition) {
                    list($requiredField, $operator, $requiredValue) = $condition;
                    $requiredFieldValue = sdo_option($dev_name, $requiredField);

                    if (!self::checkCondition($requiredFieldValue, $operator, $requiredValue)) {
                        $showField = false;
                        break;
                    }
                }

                if (!$showField) {
                    return;
                }
            }

            ?>
            <label class="sdo-form-label" for="<?php echo $name; ?>"><?php echo $title; ?></label>
            <textarea id="<?php echo $name; ?>" name="<?php echo $name; ?>" class="sdo-input"><?php echo $value; ?></textarea>
            <p><?php echo $desc; ?></p>
            <?php wp_enqueue_editor(); ?>
            <script>
                jQuery(document).ready(function($){
                    wp.editor.initialize('<?php echo $name; ?>', {
                        tinymce: {
                            wpautop: true,
                            toolbar: 'formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink | charmap | pastetext | removeformat | undo redo | wp_adv',
                            wpautop: true,
                            wrap_lines: true,
                            tabfocus_elements: ':prev,:next',
                            toolbar1: 'styleselect formatselect fontselect fontsizeselect',
                            toolbar2: 'cut copy paste | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink | charmap | pastetext | removeformat | undo redo | wp_adv',
                            toolbar3: 'table | hr | wp_more | wp_page | wp_pagebreak | visualchars | visualblocks | code',
                            toolbar4: 'fullscreen | wp_adv | wp_fullscreen',
                        },
                        quicktags: {
                            buttons: "b,i,ul,ol,li,link,close"
                        }
                    });
                });
            </script>
            <?php
        }
        public static function buttonset($dev_name, $field, $currentValue) {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] : '';
            $options = !empty($field['options']) && is_array($field['options']) ? $field['options'] : array();
            $value = !empty($currentValue) ? $currentValue : '';

            if (isset($field['require']) && is_array($field['require'])) {
                $showField = true;

                foreach ($field['require'] as $condition) {
                    list($requiredField, $operator, $requiredValue) = $condition;
                    $requiredFieldValue = sdo_option($dev_name, $requiredField);

                    if (!self::checkCondition($requiredFieldValue, $operator, $requiredValue)) {
                        $showField = false;
                        break;
                    }
                }

                if (!$showField) {
                    return;
                }
            }

            echo '<label class="sdo-form-label">' . $title . '</label>';
            echo '<div class="sdo-button-set-box flex">';

            foreach ($options as $key => $label) {
                $id = $name . '_' . $key;
                $checked = ($key == $value) ? 'checked' : '';
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
            if (isset($field['require']) && is_array($field['require'])) {
                $showField = true;

                foreach ($field['require'] as $condition) {
                    list($requiredField, $operator, $requiredValue) = $condition;
                    $requiredFieldValue = sdo_option($dev_name, $requiredField);

                    if (!self::checkCondition($requiredFieldValue, $operator, $requiredValue)) {
                        $showField = false;
                        break;
                    }
                }

                if (!$showField) {
                    return;
                }
            }


            echo '<label class="sdo-form-label">' . $title . '</label>';
            echo '<div class="sdo-switch-box flex">';
            $id = $name;
            $checked = ($value == "on") ? 'checked' : '';
            echo '<input type="checkbox" class="sdo-switch-checkbox sdo-radio" id="' . $id . '" name="' . $name . '" ' . $checked . '>';
            echo '<label class="sdo-switch-label" for="' . $id . '"></label>';
            echo '</div>';
            echo '<p>' . $desc . '</p>';
        }
        public static function select($dev_name, $field, $currentValue) {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] : '';
            $options = !empty($field['options']) && is_array($field['options']) ? $field['options'] : array();

            if (isset($field['require']) && is_array($field['require'])) {
                $showField = true;

                foreach ($field['require'] as $condition) {
                    list($requiredField, $operator, $requiredValue) = $condition;
                    $requiredFieldValue = sdo_option($dev_name, $requiredField);

                    if (!self::checkCondition($requiredFieldValue, $operator, $requiredValue)) {
                        $showField = false;
                        break;
                    }
                }

                if (!$showField) {
                    return;
                }
            }


            echo '<label class="sdo-form-label" for="' . $name . '">' . $title . '</label>';
            echo '<select class="sdo-select" id="' . $name . '" name="' . $name . '">';

            foreach ($options as $key => $label) {
                $selected = ($currentValue == $key) ? 'selected' : '';
                echo '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_html($label) . '</option>';
            }

            echo '</select>';
            echo '<p>' . $desc . '</p>';
        }
        public static function repeater($dev_name, $field, $currentValue)
        {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] : '';
            $fields = !empty($field['fields']) && is_array($field['fields']) ? $field['fields'] : array();

            // Display the repeater field UI
            echo '<div class="repeater-field" data-repeater-name="' . $name . '">';
            echo '<label class="sdo-form-label">' . $title . '</label>';
            echo '<div class="repeater-container">';
            echo '<button class="add-repeater-item">Add Item</button>';

            // Check if $currentValue is empty and create a default empty item
            if (empty($currentValue)) {
                $currentValue = [array_fill_keys(array_column($fields, 'id'), '')];
            }

            // Loop through the repeater items and display the fields
            foreach ($currentValue as $index => $item) {
                echo '<div class="repeater-item" data-item-index="' . $index . '">';

                foreach ($fields as $subfield) {
                    // Use the field_option method for subfields
                    self::field_option($dev_name, $subfield);
                }

                echo '<button class="remove-repeater-item">Remove</button>';
                echo '</div>';
            }

            echo '</div>';
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
