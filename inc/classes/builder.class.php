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
            return esc_url(OPTNNO_ASSETS . 'img/logo.png');
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
                if (method_exists(__CLASS__, $type)) {
                    $index_repeater = $repeater ? "_" . $index : "";
                    self::$type($dev_name, $field, $currentValue, $index_repeater);
                }
                ?>
            </div>
            <?php
        }
        public static function text($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $value = !empty($currentValue) ? $currentValue : '';
            ?>
            <label class="optionino-form-label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?></label>
            <input type="text" class="optionino-input" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>"
                   value="<?php echo esc_attr($value); ?>">
            <p><?php echo esc_html($desc); ?></p>
            <?php
        }
        public static function number($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $value = !empty($currentValue) ? $currentValue : '';
            ?>
            <label class="optionino-form-label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?></label>
            <input type="number" class="optionino-input" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>"
                   value="<?php echo esc_attr($value); ?>">
            <p><?php echo esc_html($desc); ?></p>
            <?php
        }
        public static function textarea($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $value = !empty($currentValue) ? $currentValue : '';
            ?>
            <label class="optionino-form-label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?></label>
            <textarea class="optionino-input" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>"><?php echo esc_textarea($value); ?></textarea>
            <p><?php echo esc_html($desc); ?></p>
            <?php
        }
        public static function tinymce($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $value = !empty($currentValue) ? $currentValue : '';
            ?>
            <label class="optionino-form-label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?></label>
            <textarea id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" class="optionino-input"><?php echo esc_textarea($value); ?></textarea>
            <p><?php echo esc_html($desc); ?></p>
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
        public static function image($dev_name, $field, $currentValue, $index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] . $index : '';
            $value = !empty($currentValue) ? $currentValue : '';
            ?>
            <label class="optionino-form-label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?></label>
            <div class="optionino-box-image-field">
                <div class="inner-image-box-optionino">
                    <input type="text" class="optionino-input image-url" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>"
                           value="<?php echo esc_attr($value); ?>">
                    <input type="button" class="optionino-button upload-image-button" data-image-field="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr__('Upload Image', OPTNNO_TEXTDOMAIN); ?>">
                    <p><?php echo esc_html($desc); ?></p>
                </div>
                <img id="<?php echo $name; ?>-preview" class="uploaded-image optionino-image-preview" src="<?php echo esc_url($value); ?>" style="max-width: 100%;">
            </div>
            <?php
        }
        public static function color($dev_name, $field, $current_value, $index = '') {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] . $index : '';
            $value = !empty($current_value) ? $current_value : '';
            ?>
            <label class="optionino-form-label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?></label>
            <input type="text" class="optionino-color-selector" id="<?php echo esc_attr($name); ?>" placeholder="#RRGGBB" pattern="^#?[0-9A-Fa-f]{3,8}$" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>">
            <p><?php echo esc_html($desc); ?></p>
            <?php
        }
        public static function buttonset($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $options = !empty($field['options']) && is_array($field['options']) ? $field['options'] : array();
            $value = !empty($currentValue) ? $currentValue : '';
            echo '<label class="optionino-form-label">' . esc_html($title) . '</label>';
            echo '<div class="optionino-button-set-box flex">';

            foreach ($options as $key => $label) {
                $id = esc_attr($name . '_' . $key);
                $checked = ($key == $value) ? 'checked' : '';
                echo '<input type="radio" class="optionino-radio button-set" id="' . $id . '" name="' . esc_attr($name) . '" value="' . esc_attr($key) . '" ' . $checked . '>';
                echo '<label class="optionino-button-label flex" for="'.$id.'">' . esc_html($label) . '</label>';
            }

            echo '</div>';
            echo '<p>' . esc_html($desc) . '</p>';
        }

        public static function switcher($dev_name, $field, $currentValue, $index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'] . $index : '';
            $value = filter_var($currentValue, FILTER_VALIDATE_BOOLEAN);
            $checked = $value ? 'checked' : '';

            echo '<label class="optionino-form-label">' . esc_html($title) . '</label>';
            echo '<div class="optionino-switch-box flex">';
            $id = $name;

            echo '<input type="hidden" name="' . esc_attr($name) . "_filled" . '" value="false">';

            echo '<input type="checkbox" class="optionino-switch-checkbox optionino-radio" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="true" ' . $checked . '>';
            echo '<label class="optionino-switch-label" for="' . esc_attr($id) . '"></label>';
            echo '</div>';
            echo '<p>' . esc_html($desc) . '</p>';
        }


        public static function select($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $options = !empty($field['options']) && is_array($field['options']) ? $field['options'] : array();
            echo '<label class="optionino-form-label" for="' . esc_attr($name) . '">' . esc_html($title) . '</label>';
            echo '<select class="optionino-select" id="' . esc_attr($name) . '" name="' . esc_attr($name) . '">';

            foreach ($options as $key => $label) {
                $selected = ($currentValue == $key) ? 'selected' : '';
                echo '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_html($label) . '</option>';
            }

            echo '</select>';
            echo '<p>' . esc_html($desc) . '</p>';
        }
        public static function repeater($dev_name, $field,$currentValue,$index = "") {
            $title = !empty($field['title']) ? $field['title'] : '';
            $desc = !empty($field['desc']) ? $field['desc'] : '';
            $name = !empty($field['id']) ? $field['id'].$index : '';
            $fields = !empty($field['fields']) && is_array($field['fields']) ? $field['fields'] : array();
            echo '<div class="optionino-repeater-field" data-repeater-name="' . esc_attr($name) . '">';
            echo '<label class="optionino-form-label">' . esc_html($title) . '</label>';
            echo '<div class="optionino-repeater-container">';
            if (empty($currentValue) || !is_array($currentValue)) {
                $currentValue = [array_fill_keys(array_column($fields, 'id'), '')];
            }
            foreach ($currentValue as $index => $item) {
                echo '<div class="optionino-repeater-item" data-item-index="' . esc_attr($index) . '">';
                echo '<div class="optionino-repeater-seperate-subfields">';
                foreach ($fields as $subfield) {
                    $fieldValue = isset($item[$subfield['id']]) ? $item[$subfield['id']] : '';
                    self::field_option($dev_name, $subfield, true, $index ,$fieldValue);
                }
                echo '</div>';
                echo '<div class="optionino-repeater-seperate-subbuttons">';
                echo '<button class="optionino-remove-repeater-item"><svg width="25" height="25" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f00" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M4 7h16" />
                  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                  <path d="M10 12l4 4m0 -4l-4 4" />
                </svg>' . __('Delete', OPTNNO_TEXTDOMAIN) . ' </button>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '<button class="optionino-add-repeater-item">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="25" height="25" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M12 5l0 14" />
                  <path d="M5 12l14 0" />
                </svg>
                </button>';
            echo '</div>';
            echo '<p>' . esc_html($desc) . '</p>';
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