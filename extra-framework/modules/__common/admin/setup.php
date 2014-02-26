<?php

/**********************
 *
 *
 *
 * LOGIN LOGO LINK
 * 
 *
 *
 *********************/
function extra_url_login(){
	return "http://www.extralagence.com/";
}
add_filter('login_headerurl', 'extra_url_login');
/**********************
 *
 *
 *
 * ADMIN STYLESHEET
 * 
 *
 *
 *********************/
function extra_css_admin() {
	wp_enqueue_style( 'admin-css', EXTRA_COMMON_MODULE_URI . '/admin/css/style.css' );
}
add_action('admin_print_styles', 'extra_css_admin');
add_action('login_head', 'extra_css_admin');
/**********************
 *
 *
 *
 * ADMIN TEXT
 * 
 *
 *
 *********************/
function extra_footer_admin () {
	printf(__('&copy; %s - <a href="http://www.extralagence.com" target="_blank">Extra l\'agence</a> - Propulsé par Wordpress', 'extra-admin'), date("Y"));
}
add_filter('admin_footer_text', 'extra_footer_admin');
/**********************
 *
 *
 *
 * TINY MCE
 * 
 *
 *
 *********************/
function extra_tinymce($init) {
	$init['theme_advanced_blockformats'] = 'p,h2,h3,h4';
	$init['theme_advanced_styles'] = "";
	$init['theme_advanced_buttons1'] = 'bold,italic,separator,formatselect,styleselect,blockquote,quote,hr,extra_cleaner,separator,charmap,separator,bullist,numlist,separator,link,unlink,separator,extra,separator,pastetext,removeformat,separator,hr,separator,fullscreen';
	$init['theme_advanced_buttons2'] = '';
	$init['theme_advanced_buttons3'] = '';
	$init['theme_advanced_buttons4'] = '';
	$init['extended_valid_elements'] = 'iframe[id|class|title|style|align|frameborder|height|longdesc|marginheight|marginwidth|name|scrolling|src|width]';
    $style_formats = array(
        array (
        	'title' => 'Lien bouton',
        	'block' => 'p',
        	'classes' => 'link-button'
        ), array (
        	'title' => 'Lien important',
        	'block' => 'p',
        	'classes' => 'link-important'
        ), array (
        	'title' => 'Question',
        	'block' => 'h3',
        	'classes' => 'question'
        ), array (
        	'title' => 'Chapô',
        	'block' => 'div',
        	'classes' => 'chapo',
        	'wrapper' => true
        )
    );

    $init['style_formats'] = json_encode( $style_formats );
    global $typenow;
	global $post;
    if($typenow == 'page' || (isset($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) == 'page')) {
		$init['body_class'] .= ' page-'.$post->post_name;
	}
	return $init;
}
add_filter('tiny_mce_before_init', 'extra_tinymce' );
/**********************
 *
 *
 *
 * EDITOR INSERT BUTTON PLUGIN
 * 
 *
 *
 *********************/	 
// INSERT BUTTON 
function extra_add_insert_plugin() {
	if(!current_user_can('edit_posts') && !current_user_can('edit_pages'))
		return;
	if(get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "extra_add_tinymce_button");
	}
}
function extra_add_tinymce_button($plugin_array) {
   $plugin_array['extra'] = EXTRA_COMMON_MODULE_URI.'/admin/js/extra-editor-plugin.js';
   return $plugin_array;
}
add_action('init', 'extra_add_insert_plugin');
/**********************
 *
 *
 *
 * MORE FILE TYPES
 * 
 *
 *
 *********************/
function addUploadMimes($mimes) {
	//$mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'addUploadMimes');
/**********************
 *
 *
 *
 * CHANGE STRINGS
 * 
 *
 *
 *********************/
function extra_gettext_filter( $translated, $original, $domain ) {
	$strings = array(
		'Déplacer dans la Corbeille' => 'Mettre à la corbeille'
	);
	if(isset( $strings[$translated])) {
		$translations = &get_translations_for_domain($domain);
		$translated = $translations->translate($strings[$translated]);
	}
	return $translated;
}
add_filter( 'gettext', 'extra_gettext_filter', 10, 3 );
/**********************
 *
 *
 *
 * RENAME UPLOADED FILES
 * 
 *
 *
 *********************/	 
function extra_sanitize_file_name ($filename) {
	$filename = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $filename);
	return remove_accents($filename);
}
add_filter('sanitize_file_name', 'extra_sanitize_file_name', 10);

/**********************
 *
 *
 *
 * POST TO NEWS
 * 
 *
 *
 *********************/	 
function extra_post_labels() {
	global $wp_post_types;
	$labels = $wp_post_types['post']->labels;
	$labels->name = __('Actualités', 'extra');
	$labels->singular_name = __('Actualités', 'extra');
	$labels->add_new = __('Nouvelle actualité', 'extra');
	$labels->add_new_item = __('Nouvelle actualité', 'extra');
	$labels->edit_item = __('Éditer une actualité', 'extra');
	$labels->new_item = __('Actualité', 'extra');
	$labels->view_item = __('Voir l\'actualité', 'extra');
	$labels->search_items = __('Rechercher une actualité', 'extra');
	$labels->not_found = __('Aucune actualité trouvée', 'extra');
	$labels->not_found_in_trash = __('Aucune actualité dans la corbeille', 'extra');
}
add_action( 'init', 'extra_post_labels' );
function extra_post_menu_labels() {
	global $menu;
	global $submenu;
	$menu[5][0] = __('Actualités', 'extra');
	$submenu['edit.php'][5][0] = __('Toutes les actualités', 'extra');
	$submenu['edit.php'][10][0] = __('Nouvelle actualité', 'extra');
	echo '';
}
add_action( 'admin_menu', 'extra_post_menu_labels' );

/**********************
 *
 *
 *
 * WELCOME WIDGET
 * 
 *
 *
 *********************/	 
require_once 'views/dashboard.php';

/**********************
 *
 *
 * OPTIONS
 *
 *
 **********************/
require_once 'global-options.php';
?>