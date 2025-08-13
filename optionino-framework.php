<?php
defined( 'ABSPATH' ) || exit;
/**
 * Plugin Name: Optionino Framework
 * Plugin URI: https://shokrino.com/
 * Author: Shokrino Team
 * Author URI: https://shokrino.com/
 * Version: 1.0.0
 * Text Domain: optionino
 * Domain Path: /languages
 * Description: Professional Tool to develop your WordPress theme and plugin easier
 * @package   Optionino Framework - Makes WordPress Options Page
 * @link      https://shokrino.com
 * @copyright 2024 Shokrino
 */

require_once __DIR__ . '/inc/loader.php';

$__opt_cfg = __DIR__ . '/config.php';
if ( file_exists( $__opt_cfg ) ) {
    include_once $__opt_cfg;
}
