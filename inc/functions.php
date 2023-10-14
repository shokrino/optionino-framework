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
    return $array[$field];
}