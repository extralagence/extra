<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 17/02/2014
 * Time: 14:17
 */

define('EXTRA_PATH', get_template_directory());
define('EXTRA_MODULES_PATH', EXTRA_PATH.'/modules');
define('EXTRA_INCLUDES_PATH', EXTRA_PATH.'/includes');
define('EXTRA_COMMON_MODULE_PATH', get_template_directory() . '/setup');

define('EXTRA_URI', get_template_directory_uri());
define('EXTRA_MODULES_URI', EXTRA_URI . '/modules');
define('EXTRA_INCLUDES_URI', EXTRA_URI . '/includes');
define('EXTRA_COMMON_MODULE_URI', get_template_directory_uri() . '/setup');

define('THEME_PATH', get_stylesheet_directory());
define('THEME_MODULES_PATH', THEME_PATH.'/modules');
define('THEME_INCLUDES_PATH', THEME_PATH.'/includes');
define('THEME_COMMON_MODULE_PATH', get_stylesheet_directory() . '/setup');

define('THEME_URI', get_stylesheet_directory_uri());
define('THEME_MODULES_URI', THEME_URI.'/modules');
define('THEME_INCLUDES_URI', THEME_URI.'/includes');
define('THEME_COMMON_MODULE_URI', get_stylesheet_directory_uri() . '/setup');

/**
 * Return current url
 *
 * @param array $args Params for extract current url :
 * 'ignore_pagination' => true|false default false
 *
 * @return mixed|string
 */
function extra_current_url($args = array()) {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}

	if (array_key_exists('ignore_pagination', $args) && $args['ignore_pagination'] == true) {
		$pageURL = preg_replace('/page\/.*/', '', $pageURL);
	}

	return $pageURL;
}

function require_extra_module_setup ($module_name, $extra_module_path = EXTRA_MODULES_PATH) {
	$extra_excluded_directories = array(
		'.',
		'..'
	);

	$require = false;
	if(is_dir($extra_module_path.'/'.$module_name) && !in_array($module_name, $extra_excluded_directories)) {
		$module_setup_file = $extra_module_path.'/'.$module_name.'/setup.php';
		if(file_exists($module_setup_file)) {
			require_once $module_setup_file;
			$require = true;
		}
	}

	return $require;
}

function require_extra_libraries () {
	// INCLUDE WPALCHEMY
	require_once EXTRA_INCLUDES_PATH . '/extra-metabox/ExtraMetaBox.php';

	// BFI THUMB
	require_once EXTRA_INCLUDES_PATH . '/BFI_Thumb.php';

	// WP LESS
	require_once EXTRA_INCLUDES_PATH . '/wp-less.php';

	// REDUX FRAMEWORK
	if (!class_exists('ReduxFramework') && file_exists(EXTRA_INCLUDES_PATH . '/ReduxFramework/ReduxCore/framework.php')) {
		require_once EXTRA_INCLUDES_PATH . '/ReduxFramework/ReduxCore/framework.php';
	}
}

/**********************
 *
 *
 * INIT
 *
 *
 *********************/

// REQUIRE LIBRARIES
require_extra_libraries();

// SCAN AND REQUIRE ALL MODULE ADMIN SETUP FILES FOR EXTRA
$modules = scandir(EXTRA_MODULES_PATH);
foreach($modules as $module) {
	require_extra_module_setup($module);
}

if ('extra' != wp_get_theme()->stylesheet) {
	// SCAN AND REQUIRE ALL MODULE ADMIN SETUP FILES FOR CURRENT THEME
	$modules = scandir(THEME_MODULES_PATH);
	foreach($modules as $module) {
		require_extra_module_setup($module, THEME_MODULES_PATH);
	}
}

require_once 'functions.php';
require_once 'admin/setup.php';
require_once 'scripts.php';
require_once 'styles.php';
if ('extra' != wp_get_theme()->stylesheet) {
	if(file_exists(THEME_PATH.'/setup/scripts.php')) {
		require_once THEME_PATH.'/setup/scripts.php';
	}
	if(file_exists(THEME_PATH.'/setup/styles.php')) {
		require_once THEME_PATH.'/setup/styles.php';
	}
}