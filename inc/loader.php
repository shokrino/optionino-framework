<?php
/**
 * Optionino Framework - Shared Loader (multi-instance safe)
 *
 * Ensures the framework can be bundled inside multiple plugins/themes without collisions.
 * - Keeps a global registry with the active version.
 * - Loads files only once (highest version wins).
 * - Guards constants, functions, and classes.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$__OPTNNO_BUNDLE = array(
    'version'   => '1.0.0',
    'base_dir'  => dirname(__DIR__),
    'file'      => dirname(__DIR__) . '/optionino-framework.php',
    'slug'      => 'optionino',
);

if ( ! isset( $GLOBALS['__OPTNNO_REGISTRY__'] ) || ! is_array( $GLOBALS['__OPTNNO_REGISTRY__'] ) ) {
    $GLOBALS['__OPTNNO_REGISTRY__'] = array();
}
$reg = &$GLOBALS['__OPTNNO_REGISTRY__'];

$__OPTNNO_SKIP = false;
if ( isset( $reg['active'] ) && is_array( $reg['active'] ) ) {
    $active = $reg['active'];
    if ( isset( $active['version'] ) && version_compare( $active['version'], $__OPTNNO_BUNDLE['version'], '>=' ) ) {
        $__OPTNNO_SKIP = true;
    }
}

if ( ! defined( 'OPTNNO_PATH' ) ) {
    define( 'OPTNNO_PATH', rtrim( $__OPTNNO_BUNDLE['base_dir'], '/\\' ) . '/' );
}
if ( ! function_exists( 'trailingslashit' ) ) {
    function trailingslashit( $s ) { return rtrim( $s, "/\\" ) . '/'; }
}
$__opt_base_url = trailingslashit( plugins_url( '', $__OPTNNO_BUNDLE['file'] ) );

if ( ! defined( 'OPTNNO_URL' ) ) {
    define( 'OPTNNO_URL', $__opt_base_url );
}
if ( ! defined( 'OPTNNO_INC' ) ) {
    define( 'OPTNNO_INC', OPTNNO_PATH . 'inc/' );
}
if ( ! defined( 'OPTNNO_CLSS' ) ) {
    define( 'OPTNNO_CLSS', OPTNNO_INC . 'classes/' );
}
if ( ! defined( 'OPTNNO_ASSETS' ) ) {
    // URL for enqueues
    define( 'OPTNNO_ASSETS', OPTNNO_URL . 'assets/' );
}
if ( ! defined( 'OPTNNO_LANG' ) ) {
    define( 'OPTNNO_LANG', OPTNNO_PATH . 'languages/' );
}
if ( ! defined( 'OPTNNO_TMPL' ) ) {
    define( 'OPTNNO_TMPL', OPTNNO_INC . 'templates/' );
}
if ( ! defined( 'OPTNNO_VERSION' ) ) {
    define( 'OPTNNO_VERSION', $__OPTNNO_BUNDLE['version'] );
}
if ( ! defined( 'OPTNNO_TEXTDOMAIN' ) ) {
    define( 'OPTNNO_TEXTDOMAIN', 'optionino' );
}

if ( ! $__OPTNNO_SKIP ) {
    
    if ( ! function_exists( 'optionino_get' ) ) {
        @include_once OPTNNO_INC . 'functions.php';
    }

    if ( ! class_exists( 'OPTNNO_Secure', false ) && file_exists( OPTNNO_CLSS . 'secure.class.php' ) ) {
        @include_once OPTNNO_CLSS . 'secure.class.php';
    }
    if ( ! class_exists( 'OPTNNO_Builder', false ) && file_exists( OPTNNO_CLSS . 'builder.class.php' ) ) {
        @include_once OPTNNO_CLSS . 'builder.class.php';
    }
    if ( ! class_exists( 'OPTNNO_Ajax_Handler', false ) && file_exists( OPTNNO_CLSS . 'ajax.class.php' ) ) {
        @include_once OPTNNO_CLSS . 'ajax.class.php';
    }
    if ( ! class_exists( 'OPTNNO', false ) && file_exists( OPTNNO_CLSS . 'core.class.php' ) ) {
        @include_once OPTNNO_CLSS . 'core.class.php';
    }

    $reg['active'] = $__OPTNNO_BUNDLE;
}

if ( ! function_exists( 'optionino_loader_info' ) ) {
    function optionino_loader_info() {
        return isset( $GLOBALS['__OPTNNO_REGISTRY__']['active'] ) ? $GLOBALS['__OPTNNO_REGISTRY__']['active'] : null;
    }
}
