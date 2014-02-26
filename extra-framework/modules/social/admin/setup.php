<?php

// WHERE DO WE USE THIS METABOX
global $social_allowed_post_types;
$social_allowed_post_types = array('post');


/*// INCLUDE CSS
function extra_second_title_admin_stylesheet(){
	global $social_allowed_post_types;
	$screen = get_current_screen();
    if (in_array($screen->post_type, $social_allowed_post_types) && $screen->base == "post"){
        wp_enqueue_style('second_title_metabox', get_template_directory_uri() . '/includes/second-title/second-title.css');
	}
}
add_action( 'admin_enqueue_scripts', 'extra_second_title_admin_stylesheet' ); */

// HTML
function extra_share() {
	
	global $post; 
	$link = get_permalink($post->ID);
	
	// IF LINK, ECHO SHARE
	if(!empty($link)) {
		$return = '';
		$return .= '<div class="share">';    
		$return .= '<a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$link.'" data-count="horizontal" data-lang="fr"></a>';                                                                                                                                                                      
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
 * JAVASCRIPTS
 * 
 *
 *
 *********************/ 
function extra_share_scripts() {  
    wp_register_script('twitter', 'http://platform.twitter.com/widgets.js', null, false, true);
    wp_enqueue_script('twitter'); 
    wp_register_script('googleplus', 'https://apis.google.com/js/plusone.js', null, false, true);
    wp_enqueue_script('googleplus');
}     
//add_action('wp_enqueue_scripts', 'extra_share_scripts');
?>