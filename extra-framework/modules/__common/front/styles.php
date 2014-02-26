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
 * STYLEHSEETS
 *
 *
 *
 *********************/
function extra_template_enqueue_styles() {
	// FANCYBOX
	wp_enqueue_style( 'fancybox', get_template_directory_uri() . '/assets/css/jquery.fancybox.css', array(), false, 'all' );
	// EXTRA MOSAIC
	wp_enqueue_style( 'extra-mosaic', get_template_directory_uri() . '/assets/css/extra.mosaic.css', array(), false, 'all' );
}
add_action('wp_enqueue_scripts', 'extra_template_enqueue_styles');