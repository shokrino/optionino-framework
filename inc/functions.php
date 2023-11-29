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
function is_id_duplicate_sdo($array, $id) {
    $id_counts = array_count_values($array);
    return isset($id_counts[$id]) && $id_counts[$id] > 1;
}
