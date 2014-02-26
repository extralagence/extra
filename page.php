<?php
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
 *
 * MAIN
 *
 *
 *********************/
?>
	<div id="main">
		<?php
		/**********************
		 *
		 * ARIANNE
		 *
		 *********************/
		get_extra_module_front_view_part("arianne");
		?>
		<section class="main-wrapper">

			<aside class="left-content small-content sidebar">
				<?php
				/**********************
				 *
				 * SIDEBAR
				 *
				 *********************/
				get_extra_module_front_view_part("sidebar/sidebar");
				?>
			</aside>

			<article class="right-content large-content content">
				<?php
				/**********************
				 *
				 * CONTENT
				 *
				 *********************/
				get_extra_module_front_view_part("content/content");
				?>
			</article><!-- article.content -->

		</section><!-- .main-wrappe -->

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