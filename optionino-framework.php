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

define('OPTNNO_PATH', plugin_dir_path(__FILE__));
define('OPTNNO_URL', plugin_dir_url(__FILE__));
define('OPTNNO_INC', OPTNNO_PATH . 'inc/');
define('OPTNNO_CLSS', OPTNNO_PATH . 'inc/classes/');
define('OPTNNO_TMPL', OPTNNO_PATH . 'inc/templates/');
define('OPTNNO_ASSETS', OPTNNO_URL . 'assets/');

$plugin_data = get_file_data(__FILE__, array('Version' => 'Version', 'Plugin Name' => 'Plugin Name'), false);
$current_theme = wp_get_theme()->get('Name');

define('OPTNNO_NAME', $plugin_data['Plugin Name']);
define('OPTNNO_VERSION', $plugin_data['Version']);
define('OPTNNO_CURRENT_THEME', $current_theme);
define('OPTNNO_CURRENT_PHP', PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION);

include_once OPTNNO_INC . 'functions.php';
include_once OPTNNO_CLSS . 'secure.class.php';
include_once OPTNNO_CLSS . 'builder.class.php';
include_once OPTNNO_CLSS . 'ajax.class.php';
include_once OPTNNO_CLSS . 'core.class.php';

include_once 'config.php';