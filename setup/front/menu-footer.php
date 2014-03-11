<nav id="menu-footer-wrapper">
	<?php
	/**********************
	 *
	 * NAVIGATION
	 *
	 *********************/
	$args = array(
		'theme_location' 	=> 'footer',
		'menu_class'		=> 'menu-footer',
		'menu_id'			=> 'menu-footer',
		'container'			=> false
	); 
	wp_nav_menu($args); ?>
</nav><!-- .menu-footer-wrapper -->