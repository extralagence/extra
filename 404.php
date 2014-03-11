<?php

// GLOBAL OPTIONS
global $extra_options;

?><!DOCTYPE html>
<!--[if lt IE 7 ]><html <?php language_attributes(); ?> class="no-js ie ie6 lte7 lte8 lte9"><![endif]-->
<!--[if IE 7 ]><html <?php language_attributes(); ?> class="no-js ie ie7 lte7 lte8 lte9"><![endif]-->
<!--[if IE 8 ]><html <?php language_attributes(); ?> class="no-js ie ie8 lte8 lte9"><![endif]-->
<!--[if IE 9 ]><html <?php language_attributes(); ?> class="no-js ie ie9 lte9 recent"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> class="recent noie no-js"><!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<link rel="profile" href="http://gmpg.org/xfn/11" />   		
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		 
		<!-- TITLE -->		 
		<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
		
		<!-- REMOVE NO-JS -->		 
		<script>document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/,'') + ' js';</script>
		
		<!-- ANALYTICS TRACKER -->
		<?php get_template_part("google-analytics"); ?>
		
		<!-- MOBILE FRIENDLY -->		
        <meta name="viewport" content="width=device-width, initial-scale=1.0">  
		
		<!-- IE9.js -->		
		<!--[if lt IE 9]>    
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/tpl/css/ie.css" />         
		<script src="<?php echo get_template_directory_uri(); ?>/tpl/js/lib/IE9.js">IE7_PNG_SUFFIX=":";</script>  
		<script src="<?php echo get_template_directory_uri(); ?>/tpl/js/lib/ie7-squish.js"></script>  
		<![endif]-->
					
		<!-- WORDPRESS HEAD HOOK -->
		<?php wp_head(); ?>  
		
	</head>
	<body <?php body_class(); ?>>
		
		<div id="wrapper404">
				
					<!-- SITE TITLE (LOGO) -->
					<a class="link404" href="<?php echo site_url('/'); ?>"><?php bloginfo("name"); ?></a>
				
		</div>
		
		<!-- WORDPRESS FOOTER HOOK -->
		<?php wp_footer(); ?>

	</body>
</html>