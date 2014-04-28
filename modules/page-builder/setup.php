<?php

global $page_builder_metabox;
$page_builder_metabox = new ExtraPageBuilder(array(
	'id' => '_page_builder',
	'lock' => WPALCHEMY_LOCK_AFTER_POST_TITLE,
	'title' => __("Extra Page Builder", "extra"),
	'types' => array('page')
));

//global $page_builder_debug_metabox;
//$page_builder_debug_metabox = new ExtraMetaBox(array(
//	'id' => '_page_builder',
//	'lock' => WPALCHEMY_LOCK_AFTER_POST_TITLE,
//	'title' => __("Extra Page Builder", "extra"),
//	'types' => array('page'),
//	'fields' => array(
//		array(
//			'type' => 'tabs',
//			'name' => 'debug_test',
//			'subfields' => array(
//				array(
//					'type' => 'text',
//					'name' => 'debug'
//				)
//			)
//		)
//	)
//));