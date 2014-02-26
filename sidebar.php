<nav id="sidebar-menu-container">
	<?php $args = array(
		'theme_location' 	=> 'sidebar',
		'container'			=> null,
		'menu_id'			=> 'sidebar-menu',
		'link_before'		=> '<span>',
		'link_after'		=> '</span>'
	); 
	wp_nav_menu($args); ?>
</nav>