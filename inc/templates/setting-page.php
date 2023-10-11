<?php defined( 'SDOPATH' ) || exit;
/**
 * Builder Class
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
SDO_Builder::section_start($settings['dev_name']);
SDO_Builder::tab_start($settings['dev_name'],$settings['dev_version']);
SDO_Builder::tab_buttons($settings['dev_name']);
SDO_Builder::tab_end();
SDO_Builder::section_end();