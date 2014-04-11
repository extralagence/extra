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
	wp_enqueue_script('jquery', 'http://code.jquery.com/jquery-1.11.0.min.js', null, null, true);
	// TWEENMAX
	wp_enqueue_script('tweenmax', 'http://cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/TweenMax.min.js', null, null, true);
	// SCROLLTO
	wp_enqueue_script('tweenmax-scrollto', 'http://cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/plugins/ScrollToPlugin.min.js', null, null, true);
	// JQUERY GSAP
	wp_enqueue_script('jquery-gsap', 'http://cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/jquery.gsap.min.js', null, null, true);
	// DAGGABLE
	wp_enqueue_script('draggable', 'http://cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/utils/Draggable.min.js', array('jquery', 'tweenmax'), null, true);
	// MOUSEWHEEL
	wp_enqueue_script('mousewheel', get_template_directory_uri() . '/assets/js/lib/jquery.mousewheel-3.0.6.pack.js', array('jquery'), null, true);
	// FANCYBOX
	wp_enqueue_script('fancybox', get_template_directory_uri() . '/assets/js/lib/jquery.fancybox.pack.js', array('jquery'), null, true);
	// FLEXIE
	wp_enqueue_script('flexie', get_template_directory_uri() . '/assets/js/lib/flexie.min.js', array('jquery'), null, true);
	// FLEXIE
	wp_enqueue_script('fancyselect', get_template_directory_uri() . '/assets/js/lib/fancyselect.js', array('jquery'), null, true);
	// EXTRA
	wp_enqueue_script('extra', get_template_directory_uri() . '/assets/js/lib/extra.js', array('jquery', 'tweenmax'), null, true);
	// EXTRA-SLIDER
	wp_enqueue_script('extra-slider', get_template_directory_uri() . '/assets/js/lib/extra.slider.js', array('jquery', 'extra', 'tweenmax'), null, true);
	// EXTRA-SLIDER
	wp_enqueue_script('extra-tooltip', get_template_directory_uri() . '/assets/js/lib/extra.tooltip.js', array('jquery', 'extra', 'tweenmax'), null, true);
	// EXTRA
	wp_enqueue_script('extra-gallery', get_template_directory_uri() . '/assets/js/lib/extra.gallery.js', array('jquery', 'extra-slider', 'tweenmax'), null, true);
}

add_action('wp_enqueue_scripts', 'extra_template_enqueue_scripts');