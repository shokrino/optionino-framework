<?php defined( 'ABSPATH' ) || exit;
/**
 * AJAX Class for Optionino Framework
 *
 * @version 1.0.1
 * @since 1.0.0
 */

if ( ! function_exists( 'optionino_normalize_hex_color' ) ) {
    /**
     * Normalize & sanitize a hex color for storage.
     * Returns '' (empty string) for invalid/empty input. Never returns null.
     *
     * @param mixed $raw
     * @return string
     */
    function optionino_normalize_hex_color( $raw ) {
        if ( ! is_string( $raw ) ) {
            return '';
        }

        $raw = trim( wp_unslash( $raw ) );

        if ( $raw === '' ) {
            return '';
        }

        if ( $raw[0] !== '#' ) {
            $raw = '#' . $raw;
        }

        $hex = sanitize_hex_color( $raw );
        if ( $hex ) {
            return $hex;
        }

        if ( preg_match( '/^#([0-9A-Fa-f]{8})$/', $raw ) ) {
            return strtoupper( $raw );
        }

        return '';
    }
}

if ( ! class_exists( 'OPTNNO_Ajax_Handler', false ) ) {
    class OPTNNO_Ajax_Handler {

        public function __construct() {
            add_action( 'wp_ajax_save_optionino_data',        array( $this, 'save_optionino_data' ) );
            add_action( 'wp_ajax_nopriv_save_optionino_data', array( $this, 'save_optionino_data' ) );
        }

        /**
         * Handle AJAX save for Optionino.
         */
        public static function save_optionino_data() {
            check_ajax_referer( 'optionino_nonce', 'security' );

            $dev_name = isset( $_POST['dev_name'] ) ? sanitize_text_field( wp_unslash( $_POST['dev_name'] ) ) : '';
            $data     = $_POST;

            if ( empty( $dev_name ) || empty( $data ) || ! is_array( $data ) ) {
                wp_send_json_error( array( 'message' => __( 'Invalid data received!', OPTNNO_TEXTDOMAIN ) ) );
            }

            $field_ids = self::get_field_ids_for_dev_name( $dev_name );

            if ( ! $field_ids || ! is_array( $field_ids ) ) {
                wp_send_json_error( array( 'message' => __( 'Invalid dev name!', OPTNNO_TEXTDOMAIN ) ) );
            }

            $data_to_save = array();

            foreach ( $field_ids as $field_id => $field_type ) {
                if ( ! isset( $data[ $field_id ] ) ) {
                    continue;
                }

                $sanitized_value = '';

                switch ( $field_type ) {
                    case 'repeater':
                        $field_value_posts = array();
                        $raw_string = is_string( $data[ $field_id ] ) ? $data[ $field_id ] : '';
                        parse_str( $raw_string, $field_value_post );

                        if ( is_array( $field_value_post ) ) {
                            foreach ( $field_value_post as $key => $subarray ) {
                                $subarray_parsed = array();
                                if ( is_string( $subarray ) ) {
                                    parse_str( $subarray, $subarray_parsed );
                                } elseif ( is_array( $subarray ) ) {
                                    $subarray_parsed = $subarray;
                                }
                                $field_value_posts[ $key ] = $subarray_parsed;
                            }
                        }
                        $sanitized_value = $field_value_posts;
                        break;

                    case 'number':
                        $sanitized_value = (int) sanitize_text_field( wp_unslash( $data[ $field_id ] ) );
                        break;

                    case 'switcher':
                        $flag            = is_string( $data[ $field_id ] ) ? $data[ $field_id ] : '';
                        $sanitized_value = ( $flag === 'off' ) ? 'false' : 'true';
                        break;

                    case 'tinymce':
                        $sanitized_value = is_string( $data[ $field_id ] ) ? wp_unslash( $data[ $field_id ] ) : '';
                        break;

                    case 'color':
                        $sanitized_value = optionino_normalize_hex_color( $data[ $field_id ] );
                        break;

                    default:
                        $field_value     = sanitize_text_field( wp_unslash( $data[ $field_id ] ) );
                        $sanitized_value = esc_html( $field_value );
                        break;
                }

                if ( $sanitized_value === null ) {
                    $sanitized_value = '';
                }

                $data_to_save[ $field_id ] = $sanitized_value;
            }

            self::save_data( $dev_name, $data_to_save );

            wp_send_json_success( array( 'message' => __( 'Data saved successfully!', OPTNNO_TEXTDOMAIN ) ) );
        }

        /**
         * Build a map of field_id => type for a given dev name.
         *
         * @param string $dev_name
         * @return array|false
         */
        public static function get_field_ids_for_dev_name( $dev_name ) {
            $tabs = isset( OPTNNO::$tabs ) ? OPTNNO::$tabs : array();
            $fieldIds = array();

            if ( isset( $tabs[ $dev_name ] ) && is_array( $tabs[ $dev_name ] ) ) {
                foreach ( $tabs[ $dev_name ] as $tab ) {
                    if ( isset( $tab['id'], $tab['fields'] ) && is_array( $tab['fields'] ) ) {
                        foreach ( $tab['fields'] as $field ) {
                            if ( isset( $field['id'] ) ) {
                                $type = isset( $field['type'] ) ? $field['type'] : 'text';
                                $fieldIds[ $field['id'] ] = $type;
                            }
                        }
                    }
                }
                return $fieldIds;
            }
            return false;
        }

        /**
         * Save defaults directly.
         *
         * @param string $dev_name
         * @param array  $defaults
         * @return void
         */
        public static function defaults( $dev_name, $defaults ) {
            self::save_data( $dev_name, $defaults );
        }

        /**
         * Merge and persist data.
         *
         * @param string $dev_name
         * @param array  $data_to_save
         * @return void
         */
        public static function save_data( $dev_name, $data_to_save ) {
            $saved_data = get_option( $dev_name, array() );

            if ( ! is_array( $saved_data ) ) {
                $saved_data = array();
            }

            foreach ( $data_to_save as $field_id => $sanitized_value ) {
                $saved_data[ $field_id ] = $sanitized_value;
            }

            update_option( $dev_name, $saved_data );
            // delete_option( $dev_name ); // for debugging only
        }
    }

    new OPTNNO_Ajax_Handler();
}
