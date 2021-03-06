<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 17/02/2014
 * Time: 11:34
 */

/**********************
 *
 *
 *
 * JAVASCRIPTS
 *
 *
 *
 *********************/
function extra_template_enqueue_scripts() {
	// REPLACE JQUERY
	wp_deregister_script('jquery');
//	wp_enqueue_script('jquery', get_template_directory_uri() . '/assets/js/lib/fix_web_jquery.min.js', null, null, true);
	wp_enqueue_script('jquery', 'http://code.jquery.com/jquery-1.11.1.min.js', null, null, true);
	// TWEENMAX
	wp_enqueue_script('tweenmax', get_template_directory_uri() . '/assets/js/lib/TweenMax.min.js', array('jquery'), null, true);
	// SCROLLTO
	wp_enqueue_script('tweenmax-scrollto', get_template_directory_uri() . '/assets/js/lib/ScrollToPlugin.min.js', array('jquery'), null, true);
	// JQUERY GSAP
	wp_enqueue_script('jquery-gsap', get_template_directory_uri() . '/assets/js/lib/jquery.gsap.min.js', array('jquery'), null, true);
	// DAGGABLE
	wp_enqueue_script('draggable', get_template_directory_uri() . '/assets/js/lib/Draggable.min.js', array('jquery', 'tweenmax'), null, true);
	// MOUSEWHEEL
	wp_enqueue_script('mousewheel', get_template_directory_uri() . '/assets/js/lib/jquery.mousewheel-3.0.6.pack.js', array('jquery'), null, true);
	// FANCYBOX
	wp_enqueue_script('fancybox', get_template_directory_uri() . '/assets/js/lib/jquery.fancybox.pack.js', array('jquery'), null, true);
	// FLEXIE
	wp_enqueue_script('flexie', get_template_directory_uri() . '/assets/js/lib/flexie.min.js', array('jquery'), null, true);
	// FANCY SELECT
	wp_enqueue_script('fancyselect', get_template_directory_uri() . '/assets/js/lib/fancyselect.js', array('jquery'), null, true);
	// EXTRA
	wp_enqueue_script('extra', get_template_directory_uri() . '/assets/js/lib/extra.js', array('jquery', 'tweenmax', 'fancybox'), null, true);
	$sizes = apply_filters('extra_responsive_sizes', array(
        'desktop' => 'only screen and (min-width: 961px)',
        'tablet' => 'only screen and (min-width: 691px) and (max-width: 960px)',
        'mobile' => 'only screen and (max-width: 690px)'
	));
	wp_localize_script('extra', 'extraResponsiveSizes', $sizes);
    // EXTRA RESPONSIVE MENU
    wp_enqueue_script('extra-responsive-menu', get_template_directory_uri() . '/assets/js/lib/ExtraResponsiveMenu.js', array('jquery', 'tweenmax', 'extra'), null, true);
	// EXTRA FADE FROM BOTTOM
	wp_enqueue_script('extra-scroll-animator', get_template_directory_uri() . '/assets/js/lib/ExtraScrollAnimator.js', array('jquery', 'tweenmax', 'extra'), null, true);
	// EXTRA FADE FROM BOTTOM
	wp_enqueue_script('extra-smooth-fit', get_template_directory_uri() . '/assets/js/lib/ExtraSmoothFit.js', array('jquery', 'tweenmax', 'extra'), null, true);
	// EXTRA-SLIDER
	wp_enqueue_script('extra-slider', get_template_directory_uri() . '/assets/js/lib/extra.slider.js', array('jquery', 'extra', 'tweenmax'), null, true);
	// EXTRA-SLIDER
	wp_enqueue_script('extra-tooltip', get_template_directory_uri() . '/assets/js/lib/extra.tooltip.js', array('jquery', 'extra', 'tweenmax'), null, true);
	// EXTRA
	wp_enqueue_script('extra-gallery', get_template_directory_uri() . '/assets/js/lib/extra.gallery.js', array('jquery', 'extra-slider', 'tweenmax'), null, true);
}

add_action('wp_enqueue_scripts', 'extra_template_enqueue_scripts');