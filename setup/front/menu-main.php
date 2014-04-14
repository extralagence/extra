<!-- MAIN NAV -->
<nav id="main-menu-container">
	<?php $args = array(
		'theme_location' 	=> 'main',
		'container'			=> null,
		'menu_id'			=> 'main-menu'
	); 
	wp_nav_menu($args); ?>
</nav>