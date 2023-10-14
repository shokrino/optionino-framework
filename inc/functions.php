<?php defined( 'SDOPATH' ) || exit;
/**
 * Main Functions
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
function sdo_option($dev_name, $field) {
    $array = get_option($dev_name, array());
    if (is_array($array) && (is_string($field) || is_int($field)) && array_key_exists($field, $array)) {
        return $array[$field];
    }
    return NULL;
}
