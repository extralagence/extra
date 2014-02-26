<?php 
/*
Template name: Plan du site
*/ 
// GLOBALS
global $extra_options;

// SETUP
the_post();
/**********************
 *
 *
 *
 * HEADER
 * 
 *
 *
 *********************/
get_header();
/**********************
 *
 * ARIANNE
 *
 *********************/
get_template_part("arianne");
/**********************
 *
 *
 * MAIN 
 *
 *
 *********************/
?>				
<div id="main">

	<div class="wrapper">
		
		<aside class="sidebar">
			<?php get_template_part("sidebar", "submenu"); ?>
		</aside><!-- aside.sidebar -->
		
		<article class="content main-content">
		
			<h1 class="content-title"><?php the_second_title(); ?></h1> 
			
			<?php the_content(); ?>
			
			<ul><?php wp_list_pages(array('title_li' => null)); ?></ul>
	
		</article><!-- article.content -->
	
	</div><!-- .wrapper -->
			
	<a class="totop" href="#top"><?php _e("Retour haut de page", "extra"); ?></a>
	
</div><!-- #main -->			
<?php 
/**********************
 *
 *
 *
 * THE FOOTER
 * 
 *
 *
 *********************/
get_footer();
?>