<?php defined( 'ABSPATH' ) || exit;
/**
 * AJAX Class for Optionino Framework
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
if (!class_exists('OPTNNO_Ajax_Handler')) {
    class OPTNNO_Ajax_Handler {
        public function __construct() {
            add_action('wp_ajax_save_optionino_data', [$this, 'save_optionino_data']);
            add_action('wp_ajax_nopriv_save_optionino_data', [$this, 'save_optionino_data']);
        }
        public static function save_optionino_data() {
            check_ajax_referer('optionino_nonce', 'security');
            $dev_name = sanitize_text_field($_POST['dev_name']);
            $data = $_POST;
            if (isset($dev_name) && isset($data) && is_array($data)) {
                $field_ids = self::get_field_ids_for_dev_name($dev_name);
                if ($field_ids && is_array($field_ids)) {
                    $data_to_save = [];
                    foreach ($field_ids as $field_id => $field_type) {
                        if (isset($data[$field_id])) {
                            if ($field_type == "repeater") {
                                parse_str($data[$field_id], $field_value_post);
                                foreach ($field_value_post as $key => $subarray) {
                                    parse_str($subarray, $subarray_parsed);
                                    $field_value_posts[$key] = $subarray_parsed;
                                }
                                $sanitized_value = $field_value_posts;
                            } elseif ($field_type == "number") {
                                $field_value = (int)sanitize_text_field($data[$field_id]);
                                $sanitized_value = $field_value;
                            } elseif ($field_type == "switcher") {
                                $field_value = $data[$field_id];
                                $sanitized_value = $field_value == 'off' ? "false" : "true";
                            } elseif ($field_type == "tinymce") {
                                $field_value = $data[$field_id];
                                $sanitized_value = $field_value;
                            } else {
                                $field_value = sanitize_text_field($data[$field_id]);
                                $sanitized_value = esc_html($field_value);
                            }
                            $data_to_save[$field_id] = $sanitized_value;
                        }
                    }
                    self::save_data($dev_name, $data_to_save);
                    wp_send_json_success(array('message' => __('Data saved successfully!'),OPTNNO_TEXTDOMAIN));
                } else {
                    wp_send_json_error(array('message' => __('Invalid dev name!',OPTNNO_TEXTDOMAIN)));
                }
            } else {
                wp_send_json_error(array('message' => __('Invalid data received!',OPTNNO_TEXTDOMAIN)));
            }
        }
        public static function get_field_ids_for_dev_name($dev_name) {
            $tabs = OPTNNO::$tabs;
            $fieldIds = [];
            if (isset($tabs[$dev_name]) && is_array($tabs[$dev_name])) {
                foreach ($tabs[$dev_name] as $tab) {
                    if (isset($tab['id']) && isset($tab['fields']) && is_array($tab['fields'])) {
                        foreach ($tab['fields'] as $field) {
                            if (isset($field['id'])) {
                                $fieldIds[$field['id']] = $field['type'];
                            }
                        }
                    }
                }
                return $fieldIds;
            }
            return false;
        }
        public static function defaults($dev_name, $defaults) {
            self::save_data($dev_name, $defaults);
        }
        public static function save_data($dev_name, $data_to_save) {
            $saved_data = get_option($dev_name, array());
            foreach ($data_to_save as $field_id => $sanitized_value) {
                $saved_data[$field_id] = $sanitized_value;
            }
            update_option($dev_name, $saved_data);
            //delete_option($dev_name);
        }
    }
    new OPTNNO_Ajax_Handler;
}