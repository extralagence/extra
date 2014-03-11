<?php
/*
 * when we have a metabox again
$social_allowed_post_types = array('post');
$extra_social_post_types = apply_filters('extra_second_title_post_types', $extra_social_post_types);
*/
/**********************
 *
 *
 *
 * PUBLIC FUNCTION
 *
 *
 *
 *********************/
function extra_share() {
	
	global $post; 
	$link = get_permalink($post->ID);
	
	// IF LINK, ECHO SHARE
	if(!empty($link)) {
		$return = '';
		$return .= '<div class="share">';    
		$return .= '<a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$link.'" data-count="horizontal" data-lang="fr"></a>';
        $return .= '<a href="https://twitter.com/share" class="twitter-share-button" data-url="'.$link.'" data-via="TWITTERACCOUNT" data-lang="fr">Tweeter</a>';                                                                                                                                                                      
		$return .= '<g:plusone size="medium" href="'.$link.'"></g:plusone>';
		$return .= '<iframe src="//www.facebook.com/plugins/like.php?href='.urlencode($link).'&amp;width=400&amp;height=35&amp;colorscheme=light&amp;layout=standard&amp;action=like&amp;show_faces=false&amp;send=false&amp;appId=220611784722096" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:400px; height:20px;" allowTransparency="true"></iframe>';
		$return .= '</div>';
		//echo $return;
	}
} 
/**********************
 *
 *
 *
 * ENQUEUE ASSETS
 * 
 *
 *
 *********************/ 
function extra_share_enqueue_assets() {
	wp_enqueue_script('twitter', 'http://platform.twitter.com/widgets.js', null, false, true);
	wp_enqueue_script('googleplus', 'https://apis.google.com/js/plusone.js', null, false, true);
}
//add_action('wp_enqueue_scripts', 'extra_share_enqueue_assets');
?>