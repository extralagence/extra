<!-- SITE TITLE (LOGO) -->
<?php if(is_front_page()): ?>	<h1 class="site-title"><span><?php bloginfo("name"); ?></span></h1>
<?php else: ?>					<h2 class="site-title"><a href="<?php echo site_url('/'); ?>"><?php bloginfo("name"); ?></a></h2> <?php endif; ?>
<!-- MAIN NAV -->
<nav id="main-menu-container">
	<?php $args = array(
		'theme_location' 	=> 'main',
		'container'			=> null,
		'menu_id'			=> 'main-menu'
	); 
	wp_nav_menu($args); ?>
</nav>