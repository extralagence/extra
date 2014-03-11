<?php
/**********************
 *
 *
 *
 * SECOND TITLE METABOX
 *
 *
 *
 *********************/
global $redirection_metabox;
$redirection_metabox = new ExtraMetaBox(array(
	'id' => '_redirection',
	'lock' => WPALCHEMY_LOCK_AFTER_POST_TITLE,
	'title' => __("Redirection", "extra"),
	'types' => array('page'),
	'include_template' => array('template-redirect.php'),
	'hide_editor' => TRUE,
	'template' => EXTRA_MODULES_PATH . '/_redirection/admin/meta.php'
));