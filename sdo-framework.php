<?php defined( 'ABSPATH' ) || exit;
/**
 * Plugin Name: ShokrinoDevOptions Framework
 * Plugin URI: https://shokrino.com/
 * Author: Shokrino Team
 * Author URI: https://shokrino.com/
 * Version: 1.0.0
 * Text Domain: sdo
 * Domain Path: /languages
 * Description: Professional Tool to develop your WordPress theme and plugin easier
 * @package   SDO Framework - WordPress Options
 * @link      https://shokrino.com
 * @copyright 2023 Shokrino
 */
define('SDOPATH' , defined( 'ABSPATH' ));
defined( 'SDOPATH' ) || exit;
define('SDO_PATH' , plugin_dir_path(__FILE__));
define('SDO_URL' , plugin_dir_url(__FILE__));
define('SDO_PHP',substr(phpversion(), 0, strpos(phpversion(), '.', strpos(phpversion(), '.') + 1)));
define('SDO_INC' , SDO_PATH.'inc/');
define('SDO_CLSS' , SDO_PATH.'inc/classes/');
define('SDO_TMPL' , SDO_PATH.'inc/templates/');
define('SDO_ASSETS' , SDO_URL.'assets/');
$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
define('SDO_VERSION', $plugin_data['Version']);

include_once SDO_CLSS.'core.class.php';
include_once SDO_CLSS.'secure.class.php';