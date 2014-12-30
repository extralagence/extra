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
	'fields' => array(
		array(
			'type' => 'redirection',
			'name' => 'redirection',
			'css_class' => 'bloc'
		),
	),
));


function extra_redirect()
{
	if ( is_page_template( 'template-redirect.php' ) ) {
		global $post, $redirection_metabox;
		the_post();
		$meta = $redirection_metabox->the_meta();
		$data = $redirection_metabox->meta;

		$redirection_type = 'auto';
		if (isset($data['redirection_type']) && !empty($data['redirection_type'])) {
			$redirection_type = $data['redirection_type'];
			$redirection_value = $data['redirection_content'];
		}

		switch ($redirection_type) {
			case 'auto' :

				$pagekids = get_pages("child_of=".$post->ID."&sort_column=menu_order");
				if(!empty($pagekids)) {
					$firstchild = $pagekids[0];
					wp_redirect(get_permalink($firstchild->ID));
				} else {
					_e("Cette page n'a pas d'enfant", "extra");
				}
				break;

			case 'manual' :

				if(!empty($redirection_value)) {
					wp_redirect($redirection_value);
				} else {
					_e("Cette page n'a pas d'url de destination", "extra");
				}
				break;

			case 'content' :

				if(!empty($redirection_value)) {
					wp_redirect(get_permalink($redirection_value));
				} else {
					_e("Cette page n'a pas d'url de destination", "extra");
				}
				break;

			case 'post-type' :

				$redirection_value = $data['redirection_post-type'];
				if(!empty($redirection_value)) {

					$targets = get_posts(array(
						'post_type' => $redirection_value,
						'numberposts' => 1,
						'orderby' => 'menu_order',
						'order' => 'ASC',
						'suppress_filters' => 0
					));

					if (count($targets) > 0) {
						if(function_exists('icl_object_id')) {
							$target_id = icl_object_id($targets[0]->ID, $redirection_value, true);
						} else {
							$target_id = $targets[0]->ID;
						}
						wp_redirect(get_permalink($target_id));
					} else {
						_e("Cette page n'a pas d'url de destination", "extra");
					}
				} else {
					_e("Cette page n'a pas d'url de destination", "extra");
				}
				break;
		}

		die();
	}
}
add_action( 'template_redirect', 'extra_redirect' );