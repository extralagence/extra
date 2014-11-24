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
	wp_enqueue_style( 'extra-admin-css', EXTRA_COMMON_MODULE_URI . '/admin/css/style.less' );
}
add_action('admin_enqueue_scripts', 'extra_css_admin');
add_action('login_head', 'extra_css_admin');
/**********************
 *
 *
 *
 * EXTRA ADMIN BAR
 *
 *
 *
 *********************/
// SCRIPTS
function extra_admin_bar_scripts() {
	if(is_admin_bar_showing()) {
		wp_enqueue_style( 'extra-custom-admin-bar-css', EXTRA_COMMON_MODULE_URI . '/admin/css/adminbar.less' );
	}
}
add_action('admin_enqueue_scripts', 'extra_admin_bar_scripts');
add_action('wp_enqueue_scripts', 'extra_admin_bar_scripts');
add_action('login_enqueue_scripts', 'extra_admin_bar_scripts');
// ADMIN BAR
function extra_admin_bar( $wp_admin_bar ){
	$wp_admin_bar->remove_menu('wp-logo');
	$wp_admin_bar->add_menu(array(
		'id'     => 'extra',
		'title' => '<span class="extra-icon extra-icon-e"></span>',
		'href'  => 'http://www.extralagence.com',
		'meta'  => array(
			'title' => __("À propos d'Extra l'agence"),
			'target' => '_blank',
		)
	));
	$wp_admin_bar->add_menu( array(
		'parent' => 'extra',
		'id'     => 'extra-url',
		'title'  => __("Extra l'agence", 'extra'),
		'href'  => 'http://www.extralagence.com',
		'meta'  => array(
			'title' => __("À propos d'Extra l'agence"),
			'target' => '_blank',
		)
	) );
}
add_action( 'admin_bar_menu', 'extra_admin_bar', 20 );
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
	printf(__('&copy; %s - <a href="http://www.extralagence.com" target="_blank">Extra l\'agence</a> - Propulsé par WordPress', 'extra-admin'), date("Y"));
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

    $toolbar1 = apply_filters('extra_tinymce_toolbar1', 'formatselect,styleselect,alignleft,aligncenter,alignright,bold,italic,link,unlink,separator,outdent,indent,blockquote,quote,hr,extra_cleaner,separator,charmap,separator,bullist,numlist,removeformat');
    $toolbar2 = apply_filters('extra_tinymce_toolbar2', '');
    $toolbar3 = apply_filters('extra_tinymce_toolbar3', '');
    $toolbar4 = apply_filters('extra_tinymce_toolbar4', '');

    $init['theme_advanced_buttons1'] = $init['toolbar1'] = $toolbar1;
    $init['theme_advanced_buttons2'] = $init['toolbar2'] = $toolbar2;
    $init['theme_advanced_buttons3'] = $init['toolbar3'] = $toolbar3;
    $init['theme_advanced_buttons4'] = $init['toolbar4'] = $toolbar4;

    $init['theme_advanced_blockformats'] = 'p,h2,h3,h4';
    $init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6';
    $init['theme_advanced_styles'] = "";

    //$init['resize'] = false;
    unset($init['preview_styles']);

	$style_formats = array();
	if(isset($init['style_formats'])) {
    	$style_formats = json_decode($init['style_formats']);
	}
    $style_formats = array_merge($style_formats, array());
    $init['style_formats'] = json_encode( $style_formats );


    global $typenow;
	global $post;
    if($typenow == 'page' || (isset($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) == 'page')) {
        $init['body_class'] .= ' page-'.$post->post_name;
        $page_template = substr(basename(get_page_template_slug($post->id)), 0, -4);
        if(!empty($page_template)) {
            $init['body_class'] .= ' ' . $page_template;
        }
	}

	return $init;
}
add_filter('tiny_mce_before_init', 'extra_tinymce', 1);
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
    $mimes = array_merge($mimes, array(
	    'swf|kml|kmz|gpx' => 'application/octet-stream',
        'xml' => 'text/xml'
    ));
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
		$translations = get_translations_for_domain($domain);
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
	$filename = preg_replace('/[^a-zA-Z0-9_\.-]/s', '', $filename);
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
	$labels->singular_name = __('Actualité', 'extra');
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
 * ALWAYS VISUAL EDITOR BY DEFAULT
 *
 *
 *
 *********************/
add_action( 'init', function() {
    set_user_setting('editor', 'tinymce');
});
/**********************
 *
 *
 *
 * HIDE UPDATE NOTICE FROM USERS
 *
 *
 *
 *********************/
function extra_hide_update_notices() {
	if (!current_user_can('update_core')) {
		remove_action( 'admin_notices', 'update_nag', 3 );
	}
}
add_action( 'admin_head', 'extra_hide_update_notices', 1 );
/**********************
 *
 *
 *
 * WELCOME WIDGET
 *
 *
 *
 *********************/
require_once 'dashboard.php';
/**********************
 *
 *
 *
 * REDUX PANEL
 *
 *
 *
 *********************/
require_once 'redux-options.php';
?>
