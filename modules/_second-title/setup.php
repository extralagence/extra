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
add_action('init', 'extra_second_title_metabox_init');
function extra_second_title_metabox_init () {
	$extra_second_title_post_types = apply_filters('extra_second_title_post_types', array('page'));
	$extra_second_title_exclude_post_id = apply_filters('extra_second_title_exclude_post_id', array());
	$extra_second_title_exclude_template = apply_filters('extra_second_title_exclude_template', array('template-redirect.php'));

	global $second_title_metabox;
	$second_title_metabox = new ExtraMetaBox(array(
		'exclude_post_id' => $extra_second_title_exclude_post_id,
		'exclude_template' => $extra_second_title_exclude_template,
		'id' => '_second_title',
		'init_action' => 'extra_second_title_metabox_enqueue_assets',
		'lock' => WPALCHEMY_LOCK_AFTER_POST_TITLE,
		'title' => __("Titre alternatif", "extra"),
		'types' => $extra_second_title_post_types,
		'fields' => array(
			array(
				'type' => 'textarea',
				'css_class' => 'second_title',
				'name' => 'second_title',
				'label' => __("Titre alternatif", "extra")
			)
		)
	));
}
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
function get_second_title($id = 0, $fallback = true, $format = true){

	global  $post,
			$second_title_metabox;

    $temp_post = $post;

	if(isset($id)) {
		$post = get_post($id);
	}

	$id = isset($post->ID) ? $post->ID : (int) $id;
	$meta = get_post_meta($id, $second_title_metabox->get_the_id(), TRUE);

	$title = null;

	if(isset($meta) && isset($meta["second_title"]) && !empty($meta["second_title"])){
		$title = $meta["second_title"];
		if($format) {
			$title = nl2br($title);
		}
	} else if($fallback) {
		$title = get_the_title($id);
	}

    $post = $temp_post;

	return apply_filters('second_title', $title, $id);
}
function the_second_title(){
	global $post;
	echo get_second_title($post->ID);
}