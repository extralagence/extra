<nav id="menu-footer-wrapper">
	<?php
	/**********************
	 *
	 * NAVIGATION
	 *
	 *********************/
	$args = array(
		'theme_location' 	=> 'footer-second',
		'menu_class'		=> 'menu-footer-second',
		'menu_id'			=> 'menu-footer-second',
		'container'			=> false
	); 
	wp_nav_menu($args); ?>
</nav><!-- .menu-footer-wrapper -->