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
define('EXTRA_COMMON_MODULE_PATH', EXTRA_PATH . '/setup');

define('EXTRA_URI', get_template_directory_uri());
define('EXTRA_MODULES_URI', EXTRA_URI . '/modules');
define('EXTRA_INCLUDES_URI', EXTRA_URI . '/includes');
define('EXTRA_COMMON_MODULE_URI', EXTRA_URI . '/setup');

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

	// INCLUDE EXTRA METABOX
	require_once EXTRA_INCLUDES_PATH . '/extra-metabox/ExtraMetaBox.php';
	require_once EXTRA_INCLUDES_PATH . '/extra-metabox/ExtraPageBuilder.php';

	// INCLUDE EXTRA GALLERY
	require_once EXTRA_INCLUDES_PATH . '/extra-gallery/setup.php';

	// BFI THUMB
	require_once EXTRA_INCLUDES_PATH . '/bfi_thumb/BFI_Thumb.php';
	//remove_filter( 'image_resize_dimensions', 'bfi_image_resize_dimensions', 10, 5 );
	//remove_filter( 'image_downsize', 'bfi_image_downsize', 1, 3 );

    // LESS PHP
    require_once EXTRA_INCLUDES_PATH . '/lessphp/lessc.inc.php';

    // WP LESS
    require_once EXTRA_INCLUDES_PATH . '/wp-less/wp-less.php';

    // REDUX FRAMEWORK
    if (!class_exists('ReduxFramework')) {
        require_once EXTRA_INCLUDES_PATH . '/redux-framework/ReduxCore/framework.php';
    }
    // Modify {$redux_opt_name} to match your opt_name
    add_action("redux/extensions/extra_options/before", 'redux_register_custom_extension_loader', 0);
}
function redux_register_custom_extension_loader($ReduxFramework) {
    $path = EXTRA_INCLUDES_PATH . '/redux-extensions/';
    $folders = scandir( $path, 1 );
    foreach($folders as $folder) {
        if ($folder === '.' or $folder === '..' or !is_dir($path . $folder) ) {
            continue;
        }
        $extension_class = 'ReduxFramework_Extension_' . $folder;
        if( !class_exists( $extension_class ) ) {
            // In case you wanted override your override, hah.
            $class_file = $path . $folder . '/extension_' . $folder . '.php';
            $class_file = apply_filters( 'redux/extension/'.$ReduxFramework->args['opt_name'].'/'.$folder, $class_file );
            if ( file_exists( $class_file ) ) {
                require_once( $class_file );
                $extension = new $extension_class( $ReduxFramework );
            }
        }
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

require_once EXTRA_COMMON_MODULE_PATH . '/functions.php';
require_once EXTRA_COMMON_MODULE_PATH . '/admin/setup.php';
require_once EXTRA_COMMON_MODULE_PATH . '/scripts.php';
require_once EXTRA_COMMON_MODULE_PATH . '/styles.php';
if ('extra' != wp_get_theme()->stylesheet) {
	if(file_exists(THEME_PATH.'/setup/scripts.php')) {
		require_once THEME_PATH.'/setup/scripts.php';
	}
	if(file_exists(THEME_PATH.'/setup/styles.php')) {
		require_once THEME_PATH.'/setup/styles.php';
	}
}
/**********************
 *
 *
 *
 * TEMPLATE WRAPPER
 *
 *
 *
 *********************/
function extra_template_path() {
    return Extra_Wrapping::$main_template;
}

function extra_template_base() {
    return Extra_Wrapping::$base;
}

class Extra_Wrapping {
    /**
     * Stores the full path to the main template file
     */
    static $main_template;
    /**
     * Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
     */
    static $base;

    static function wrap($template) {
        self::$main_template = $template;
        self::$base = substr(basename(self::$main_template), 0, -4);
        if ('index' == self::$base) {
            self::$base = false;
        }
        $templates = array('wrapper.php');
        if (self::$base) {
            array_unshift($templates, sprintf('wrapper-%s.php', self::$base));
        }
        return locate_template($templates);
    }

}
add_filter('template_include', array(
    'Extra_Wrapping',
    'wrap'
), 99);
/**********************
 *
 *
 *
 * EXTRA INIT
 *
 *
 *
 *********************/
do_action('extra_init');