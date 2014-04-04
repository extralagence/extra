<a id="switch-mobile-menu" href="#"><span class="icon icon-mobile"></span><?php _e("Afficher ou masquer le menu mobile", "extra"); ?></a>

<nav id="mobile-menu-container">
	<?php $args = array(
		'theme_location' 	=> 'mobile',
		'container'			=> null,
		'menu_id'			=> 'mobile-menu'
	); 
	wp_nav_menu($args); ?>
</nav>