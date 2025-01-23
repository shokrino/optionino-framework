<?php defined( 'ABSPATH' ) || exit;
/**
 * Main Functions
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
function optionino_get($dev_name, $field) {
    if (!is_string($dev_name)) {
        return NULL;
    }
    $array = get_option($dev_name, array());
    if (is_array($array) && (is_string($field) || is_int($field)) && array_key_exists($field, $array)) {
        return $array[$field];
    }
    return NULL;
}

function is_id_duplicate_optionino($array, $id) {
    $count = 0;
    foreach ($array as $value) {
        if ($value == $id) {
            $count++;
        }
        if ($count > 1) {
            return true;
        }
    }
    return false;
}

function to_boolean_optionino($value) {
    return $value === "true" ? true : false;
}
