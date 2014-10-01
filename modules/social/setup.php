<?php
/*
 * When we have a metabox again
 * $social_allowed_post_types = array('post');
 * $extra_social_post_types = apply_filters('extra_second_title_post_types', $extra_social_post_types);
 */

/**********************
 *
 *
 * PUBLIC FUNCTION
 *
 *
 *********************/
function extra_share($id = 0) {
//	TODO RESET WHEN INTERNET IS BACK
	$shares = array(
		array(
			'url' => 'https://apis.google.com/js/plusone.js',
			'id' => null
		),
		array(
			'url' => '//connect.facebook.net/fr_FR/all.js#xfbml=1',
			'id' => 'facebook-jssdk'
		),
		array(
			'url' => 'http://platform.twitter.com/widgets.js',
			'id' => null
		)
	);
	wp_localize_script('extra-social', 'shareApis', json_encode($shares));

	global $post;

	if ($id == 0) {
		$id = $post->ID;
	}

	$link = get_permalink($id);

	// IF LINK, ECHO SHARE
	if(!empty($link)) {
		$return = '';
		$return .= '<div class="share">';
		$return .= '<a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-url="'.$link.'" data-lang="fr"></a>';
		$return .= '<div class="g-plusone-wrapper"><div class="g-plusone" data-size="medium" data-count="true" data-href="'.$link.'"></div></div>';
		$return .= '<div class="fb-like" data-href="'.$link.'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>';
		$return .= '</div>';
		echo apply_filters('extra_share_html', $return);
	}
}
/**********************
 *
 *
 * ENQUEUE ASSETS
 *
 *
 *********************/
function extra_social_enqueue_assets() {
	wp_enqueue_style('extra-social', EXTRA_MODULES_URI.'/social/front/css/social.less');
	wp_enqueue_script('extra-social', EXTRA_MODULES_URI.'/social/front/js/social.js', array('jquery'), false, true);
}
add_action('wp_enqueue_scripts', 'extra_social_enqueue_assets');

?>
