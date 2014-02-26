<?php
global $extra_options;
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
 *
 * MAIN 
 *
 *
 *********************/
?>				
<div id="main">

	<div class="wrapper">
		
		<aside class="sidebar">
			<?php get_template_part("sidebar"); ?>
		</aside><!-- aside.sidebar -->
		
		<article class="content main-content">
		
			<p class="metas"><span class="date"><?php echo get_the_date(); ?></span></p>
			<h1 class="content-title"><?php the_title(); ?></h1>
			 
			
			<?php the_content(); ?>
	
		</article><!-- article.content -->
	
	</div><!-- .wrapper -->
			
	
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