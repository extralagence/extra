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

$extra_second_title_post_types = array('page');
$extra_second_title_post_types = apply_filters('extra_second_title_post_types', $extra_second_title_post_types);

global $second_title_metabox;
$second_title_metabox = new WPAlchemy_MetaBox(array(
	'exclude_post_id' => get_option('page_on_front'),
	'id' => '_second_title',
	'init_action' => 'extra_second_title_metabox_enqueue_assets',
	'lock' => WPALCHEMY_LOCK_AFTER_POST_TITLE,
	'title' => __("Titre alternatif", "extra"),
	'types' => $extra_second_title_post_types,
	'template' => EXTRA_MODULES_PATH.'/_second-title/admin/meta.php'
));
// INCLUDE CSS
function extra_second_title_metabox_enqueue_assets () {
	wp_enqueue_style('second_title_metabox', EXTRA_MODULES_URI.'/_second-title/admin/css/style.css');
}

/**********************
 *
 *
 *
 * USABLE FUNCTIONS
 * 
 *
 *
 *********************/
function get_second_title($id = 0){
	
	global $post;
	global $second_title_metabox;

	if(isset($id)) {
		$post = get_post($id);
	}
	
	$id = isset($post->ID) ? $post->ID : (int) $id;
	$meta = get_post_meta($id, $second_title_metabox->get_the_id(), TRUE);

	$title = isset($post->post_title) ? $post->post_title : '';

	if(isset($meta) && isset($meta["second_title"]) && !empty($meta["second_title"])){
		$title = $meta["second_title"];
	} else {
		$title = get_the_title($id);
	}
	
	return apply_filters('second_title', $title, $id);
}
function the_second_title(){
	global $post;
	echo get_second_title($post->ID);
}