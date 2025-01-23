<?php defined( 'ABSPATH' ) || exit;

$optionino_instance = new OPTNNO();
$optionino_instance->admin_scripts();
OPTNNO_Builder::container_start();
OPTNNO_Builder::title($settings['dev_title']);
do_action("optionino_before_setting_{$settings['dev_name']}");
OPTNNO_Builder::section_start($settings['dev_name']);
OPTNNO_Builder::tab_start($settings['dev_name'],$settings['dev_version']);
OPTNNO_Builder::tab_buttons($settings['dev_name']);
OPTNNO_Builder::tab_end();
OPTNNO_Builder::form_start();
OPTNNO_Builder::form_fields($settings['dev_name']);
OPTNNO_Builder::form_end($settings['dev_name']);
OPTNNO_Builder::section_end();
do_action("optionino_after_setting_{$settings['dev_name']}");
OPTNNO_Builder::loading();
OPTNNO_Builder::container_end();