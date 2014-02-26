<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 17/02/2014
 * Time: 14:17
 */

define('EXTRA', 'extra-framework');

define('EXTRA_PATH', get_template_directory().'/'.EXTRA);
define('EXTRA_MODULES_PATH', EXTRA_PATH.'/modules');
define('EXTRA_INCLUDES_PATH', EXTRA_PATH.'/includes');
define('EXTRA_COMMON_MODULE_PATH', EXTRA_PATH.'/modules/__common');

define('EXTRA_URI', get_template_directory_uri().'/'.EXTRA);
define('EXTRA_MODULES_URI', EXTRA_URI.'/modules');
define('EXTRA_INCLUDES_URI', EXTRA_URI.'/includes');
define('EXTRA_COMMON_MODULE_URI', EXTRA_URI.'/modules/__common');

define('THEME_PATH', get_stylesheet_directory().'/'.EXTRA);
define('THEME_MODULES_PATH', THEME_PATH.'/modules');
define('THEME_INCLUDES_PATH', THEME_PATH.'/includes');
define('THEME_COMMON_MODULE_PATH', THEME_PATH.'/modules/__common');

define('THEME_URI', get_stylesheet_directory_uri().'/'.EXTRA);
define('THEME_MODULES_URI', THEME_URI.'/modules');
define('THEME_INCLUDES_URI', THEME_URI.'/includes');
define('THEME_COMMON_MODULE_URI', THEME_URI.'/modules/__common');

function extra_current_url() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function require_extra_module_setup ($module_name, $is_admin = true, $extra_module_path = EXTRA_MODULES_PATH) {
	$extra_excluded_directories = array(
		'.',
		'..'
	);

	$require = false;
	if(is_dir($extra_module_path.'/'.$module_name) && !in_array($module_name, $extra_excluded_directories)) {
		$context = ($is_admin) ? 'admin' : 'front';

		$module_setup_file = $extra_module_path.'/'.$module_name.'/'.$context.'/setup.php';
		//var_dump($module_setup_file);

		if(file_exists($module_setup_file)) {
			require_once $module_setup_file;
			$require = true;
		}
	}

	return $require;
}

function get_extra_module_front_view_part ($slug, $module = null ) {
	get_extra_module_part('/front/views', $slug, $module);
}

function get_extra_module_part ($base, $slug, $module = null) {
	$template_name = ($module !== null) ? $module : get_extra_module_part_name();

	$templates = array();
	$templates[] = EXTRA.'/modules/'.$template_name.$base.'/'."{$slug}.php";
	$templates[] = EXTRA.'/modules/__common'.$base.'/'."{$slug}.php";

//	if ($module == "post") {
//		var_dump($templates);
//	}

	locate_template($templates, true, false);
}

/**
 * Return name for a template part.
 *
 * @return mixed|null|string $name of the part
 */
function get_extra_module_part_name () {
	global $post;

	$name = null;
	if (is_front_page()) {
		$name = 'front-page';
	} elseif (is_home()) {
		$name = 'home';
	} elseif (is_page()) {
		$name = get_post_meta( get_the_ID(), '_wp_page_template', TRUE );
		$prefix = 'template-';
		$name = substr($name, strlen($prefix), -4);
	} else {
		$name = $post->post_type;
	}

	return $name;
}

function require_extra_libraries () {
	// INCLUDE WPALCHEMY
	require_once EXTRA_INCLUDES_PATH . '/wpalchemy/MetaBox.php';
	require_once EXTRA_INCLUDES_PATH . '/wpalchemy/MediaAccess.php';

	// BFI THUMB
	require_once EXTRA_INCLUDES_PATH . '/BFI_Thumb.php';

	// WP LESS
	require_once EXTRA_INCLUDES_PATH . '/wp-less.php';

	// REDUX FRAMEWORK
	if (!class_exists('ReduxFramework') && file_exists(EXTRA_INCLUDES_PATH . '/ReduxFramework/ReduxCore/framework.php')) {
		require_once EXTRA_INCLUDES_PATH . '/ReduxFramework/ReduxCore/framework.php';
	}

	require_once 'functions.php';
}

function init_extra_framework () {
	// REQUIRE LIBRARIES
	require_extra_libraries();

	// SCAN AND REQUIRE ALL MODULE ADMIN SETUP FILES FOR EXTRA
	$modules = scandir(EXTRA_MODULES_PATH);
	foreach($modules as $module) {
		require_extra_module_setup($module, true);
	}

	if ('extra' != wp_get_theme()->stylesheet) {
		// SCAN AND REQUIRE ALL MODULE ADMIN SETUP FILES FOR CURRENT THEME
		$modules = scandir(THEME_MODULES_PATH);
		foreach($modules as $module) {
			require_extra_module_setup($module, true, THEME_MODULES_PATH);
		}
	}

	add_action('get_header', 'init_extra_front');
}

function init_extra_front() {
	require_extra_module_setup('__common', false, THEME_MODULES_PATH);
	require_extra_module_setup('__common', false, EXTRA_MODULES_PATH);

	if (!require_extra_module_setup(get_extra_module_part_name(), false, THEME_MODULES_PATH)) {
		require_extra_module_setup(get_extra_module_part_name(), false, EXTRA_MODULES_PATH);
	}
}