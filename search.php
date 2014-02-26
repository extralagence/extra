<?php 
global $wpdb;
global $query;
global $searched_post_types;
global $count;

// POST TYPES TO SEARCH IN
$searched_post_types = array('post', 'page', 'pole', 'company', 'team');

if(isset($_GET) && array_key_exists("post_type", $_GET)) {
	$searched_post_types = array($_GET["post_type"]);
}

// SET KEYWORD
$keyword = get_search_query();
$keyword = '%' . like_escape( $keyword ) . '%';


// SEARCH IN CUSTOM FIELDS
$post_ids_meta = $wpdb->get_col( $wpdb->prepare( "
    SELECT DISTINCT post_id FROM {$wpdb->postmeta}
    WHERE meta_value LIKE '%s'
", $keyword ) );


// SEARCH IN TITLE AND CONTENT
$post_ids_post = $wpdb->get_col( $wpdb->prepare("
    SELECT DISTINCT ID FROM {$wpdb->posts}
    WHERE post_title LIKE '%s'
    OR post_content LIKE '%s'
", $keyword, $keyword));
$post_ids = array_merge( $post_ids_meta, $post_ids_post );


// THE QUERY
$args = array(
    'post_type'   => $searched_post_types,
    'post_status' => 'publish',
    'post__in'    => $post_ids,
    'posts_per_page' => -1
);
$query = new WP_Query($args);
$count = $query->post_count;
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
	
	<section class="main-wrapper">
		
		<aside class="right-content">
			<h2 class="sidebar-title"><?php _e("Résultats<br />de recherche", "extra"); ?></h2>
		</aside><!-- aside.sidebar -->
		
		<article class="content left-content">		
				
			<?php
			$filtered_posts = array();
			foreach($searched_post_types as $searched_post_type) {
				$filtered_posts[$searched_post_type] = array();
				foreach($query->posts as $post) {
					if($post->post_type == $searched_post_type) {
						array_push($filtered_posts[$searched_post_type], $post);
					}
				}
			}
			if(have_posts()) : ?>
				<div class="list-wrapper">
				 	<?php
					/*********************
					 *
					 * PAGES
					 * 
					 ********************/
					$count = sizeof($filtered_posts['page']);
					?>
					
					<div class="list-content">
						
						<?php 
						// THERE IS RESULTS
						if($count > 0): ?>
							
						<h2><?php printf(_n("%d contenu sur le site", "%d contenus sur le site", $count, "extra"), $count); ?></h2>
						<ul>
							<?php foreach($filtered_posts['page'] as $post) : ?>
								<li><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
							<?php endforeach; ?>
						</ul>
						
						<?php 
						// NO RESULTS
						else: ?>
							
						<h2 class="no-result"><?php _e("Aucun contenu correspondant sur le site", "extra"); ?></h2>
						
						<?php endif; ?>
						
					</div>
				 	<?php
					/*********************
					 *
					 * NEWS
					 * 
					 ********************/
					$count = sizeof($filtered_posts['post']);
					?>
					
					<div class="list-content">
						
						<?php 
						// THERE IS RESULTS
						if($count > 0): ?>
							
						<h2><?php printf(_n("%d actualité sur le site", "%d actualités sur le site", $count, "extra"), $count); ?></h2>
						<ul>
							<?php foreach($filtered_posts['post'] as $post) : ?>
								<li><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
							<?php endforeach; ?>
						</ul>
						
						<?php 
						// NO RESULTS
						else: ?>
							
						<h2 class="no-result"><?php _e("Aucune actualité trouvée", "extra"); ?></h2>
						
						<?php endif; ?>
						
					</div>
				 	<?php
					/*********************
					 *
					 * TEAM
					 * 
					 ********************/
					$count = sizeof($filtered_posts['team']);
					?>
					
					<div class="list-content">
						
						<?php 
						// THERE IS RESULTS
						if($count > 0): ?>
							
						<h2><?php printf(_n("%d collaborateur", "%d collaborateurs", $count, "extra"), $count); ?></h2>
						<ul>
							<?php foreach($filtered_posts['team'] as $post) : ?>
								<li><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
							<?php endforeach; ?>
						</ul>
						
						<?php 
						// NO RESULTS
						else: ?>
							
						<h2 class="no-result"><?php _e("Aucun collaborateur", "extra"); ?></h2>
						
						<?php endif; ?>
						
					</div>
				 	<?php
					/*********************
					 *
					 * POLES
					 * 
					 ********************/
					$count = sizeof($filtered_posts['pole']);
					?>
					
					<div class="list-content">
						
						<?php 
						// THERE IS RESULTS
						if($count > 0): ?>
							
						<h2><?php printf(_n("%d pôle", "%d pôles", $count, "extra"), $count); ?></h2>
						<ul>
							<?php foreach($filtered_posts['pole'] as $post) : ?>
								<li><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
							<?php endforeach; ?>
						</ul>
						
						<?php 
						// NO RESULTS
						else: ?>
							
						<h2 class="no-result"><?php _e("Aucun pôle", "extra"); ?></h2>
						
						<?php endif; ?>
						
					</div>
				 	<?php
					/*********************
					 *
					 * DOSSIERS
					 * 
					 ********************/
					$count = sizeof($filtered_posts['company']);
					?>
					
					<div class="list-content">
						
						<?php 
						// THERE IS RESULTS
						if($count > 0): ?>
							
						<h2><?php printf(_n("%d entreprise", "%d entreprises", $count, "extra"), $count); ?></h2>
						<ul>
							<?php foreach($filtered_posts['company'] as $post) : ?>
								<li><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
							<?php endforeach; ?>
						</ul>
						
						<?php 
						// NO RESULTS
						else: ?>
							
						<h2 class="no-result"><?php _e("Aucune entreprise", "extra"); ?></h2>
						
						<?php endif; ?>
						
					</div>
				</div>
			</article>
		<?php
		 /*********************
		 * 
		 * 
		 * 
		 * 
		 * NO RESULTS
		 * 
		 * 
		 * 
		 * 
		 ********************/	?>
		<?php else : ?>		
		<article class="content left-content">
			<h2><?php _e("Aucun résultat", "extra"); ?></h2>
			<p><?php _e("Votre recherche n'a trouvé aucun résultat. Essayez avec d'autres mots-clefs.", "extra"); ?></p>
			<p><a class="link-button" href="<?php bloginfo("wpurl"); ?>/"><?php _e("Retour à la page d'accueil", "extra"); ?></a></p>
		</article><!-- article.content -->
		
		<?php endif; ?> 
	
	</section><!-- .wrapper -->
			
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