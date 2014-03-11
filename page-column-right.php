<?php
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
		get_template_part(apply_filters(" extra_template_arianne", "setup/front/arianne"));

		?>
		<section class="main-wrapper">

			<article class="left-content large-content content">
				<?php
				/**********************
				 *
				 * CONTENT
				 *
				 *********************/
				get_template_part(apply_filters("extra_template_content", "setup/front/content"));
				?>
			</article><!-- article.content -->

			<aside class="right-content small-content sidebar">
				<?php
				/**********************
				 *
				 * SIDEBAR
				 *
				 *********************/
				get_template_part(apply_filters("extra_template_sidebar", "setup/front/sidebar"));
				?>
			</aside>

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