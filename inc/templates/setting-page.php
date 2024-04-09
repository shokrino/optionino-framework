<?php defined( 'SDOPATH' ) || exit;
/**
 * Builder Class
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 */
$sdo_instance = new SDO();
$sdo_instance->admin_scripts();
SDO_Builder::container_start();
SDO_Builder::title($settings['dev_title']);
SDO_Builder::section_start($settings['dev_name']);
SDO_Builder::tab_start($settings['dev_name'],$settings['dev_version']);
SDO_Builder::tab_buttons($settings['dev_name']);
SDO_Builder::tab_end();
SDO_Builder::form_start();
SDO_Builder::form_fields($settings['dev_name']);
SDO_Builder::form_end($settings['dev_name']);
SDO_Builder::section_end();
SDO_Builder::loading();
SDO_Builder::container_end();