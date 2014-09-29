<?php
/*
 * Extra Breadcrumb
 *
 * (c) 2013 Jean-Baptiste Janot / Extra l'agence
 *
 */
// GET POST GLOBAL
global $post;
?>
<div id="arianne">

	<div class="inner">

	<?php

	// if $post variable exists
	if(isset($post) && !empty($post)) {

		$home = array(
			'class' => 'home',
			'name'  => __("Accueil", "extra"),
			'link'  => home_url("/"),
		);

		$parents = array();

		$current_post = array(
			'class' => 'current',
			'name'  => '',
			'link'  => null,
		);


		// CATEGORY
		if (is_category()) {
			$current_category = get_category(get_query_var('cat'), false);
			if ($current_category->parent != 0) {
				$ancestors_ids = get_ancestors($current_category->ID, 'category');
				$ancestors_ids = array_reverse($ancestors_ids);

				foreach ($ancestors_ids as $ancestor_id) {
					$ancestor_category = get_category($ancestor_id, false);
					$parents[] = array(
						'class' => '',
						'name'  => $ancestor_category->name,
						'link'  => get_category_link($ancestor_category->term_id),
					);
				}
			}

			$homeID = get_option("page_for_posts");
			$home_post = get_post($homeID);
			$parents[] = array(
				'class' => '',
				'name'  => $home_post->post_title,
				'link'  => get_permalink($homeID),
			);

			$current_post['name'] = sprintf(__('Archive de la catégorie "%s"', 'extra'), single_cat_title('', false));
		}

		// SEARCH
		else if (is_search()) {
			$current_post['name'] = sprintf(__('Résultats pour la recherche "%s"', 'extra'), get_search_query());
		}

		// TIME - DAY
		else if (is_day()) {
			if (get_option("page_for_posts") != 0) {
				$homeID = get_option("page_for_posts");
				$home_post = get_post($homeID);
				$parents[] = array(
					'class' => '',
					'name'  => $home_post->post_title,
					'link'  => get_permalink($homeID),
				);
			}
			$parents[] = array(
				'class' => '',
				'name'  => get_the_time('Y'),
				'link'  => get_year_link(get_the_time('Y')),
			);
			$parents[] = array(
				'class' => '',
				'name'  => get_the_time('F'),
				'link'  => get_month_link(get_the_time('Y'), get_the_time('m')),
			);
			$current_post['name'] = get_the_time('d');
		}

		// TIME - MONTH
		else if (is_month()) {
			if (get_option("page_for_posts") != 0) {
				$homeID = get_option("page_for_posts");
				$home_post = get_post($homeID);
				$parents[] = array(
					'class' => '',
					'name'  => $home_post->post_title,
					'link'  => get_permalink($homeID),
				);
			}
			$parents[] = array(
				'class' => '',
				'name'  => get_the_time('Y'),
				'link'  => get_year_link(get_the_time('Y')),
			);
			$current_post['name'] = get_the_time('F');
		}

		// TIME - YEAR
		else if (is_year()) {
			if (get_option("page_for_posts") != 0) {
				$homeID = get_option("page_for_posts");
				$home_post = get_post($homeID);
				$parents[] = array(
					'class' => '',
					'name'  => $home_post->post_title,
					'link'  => get_permalink($homeID),
				);
			}
			$current_post['name'] = get_the_time('Y');
		}

		// NEWS HOME
		else if(is_home()) {
			$homeID = get_option("page_for_posts");
			$home_post = get_post($homeID);
			$current_post['name'] = $home_post->post_title;
		}

		// SINGLE, NOT ATTACHMENT
		else if (is_single() && !is_attachment()) {

			// CUSTOM POST TYPE
			if (get_post_type() != 'post') {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				$parents[] = array(
					'class' => '',
					'name'  => $post_type->labels->singular_name,
					'link'  => home_url("/").'/'.$slug['slug'],
				);
				$current_post['name'] = get_the_title();
			}
			// POST
			else {
				if (get_option("page_for_posts") != 0) {
					$homeID = get_option("page_for_posts");
					$home_post = get_post($homeID);
					$parents[] = array(
						'class' => '',
						'name'  => $home_post->post_title,
						'link'  => get_permalink($homeID),
					);
				}
				$current_post['name'] = get_the_title();
			}
		}

		// CUSTOM POST TYPE
		else if (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
			$post_type = get_post_type_object(get_post_type());
			$current_post['name'] = $post_type->labels->singular_name;
		}

		// ATTACHMENT
		elseif (is_attachment()) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID);
			$cat = $cat[0];

			$ancestors_ids = get_ancestors($cat, 'category');
			$ancestors_ids = array_reverse($ancestors_ids);
			$ancestors_ids[] = $cat;

			foreach ($ancestors_ids as $ancestor_id) {
				$ancestor_category = get_category($ancestor_id, false);
				$parents[] = array(
					'class' => '',
					'name'  => $ancestor_category->name,
					'link'  => get_category_link($ancestor_category->term_id),
				);
			}

			$parents[] = array(
				'class' => '',
				'name'  => $parent->post_title,
				'link'  => get_permalink($parent),
			);
			$current_post['name'] = get_the_title();
		}

		// TOP LEVEL PAGE
		elseif ( is_page() && !$post->post_parent ) {
			$current_post['name'] = get_the_title();
		}

		// PAGE WITH ANCESTOR
		else if(is_page()){
			$ancestors_ids = get_ancestors($post-> ID, 'page');
			$ancestors_ids = array_reverse($ancestors_ids);
			foreach ($ancestors_ids as $ancestor_id) {
				$parents[] = array(
					'class' => '',
					'name'  => get_the_title($ancestor_id),
					'link'  => get_permalink($ancestor_id),
				);
			}
			$current_post['name'] = get_the_title();
		}

		// TAG
		else if(is_tag()) {
			$current_post['name'] = sprintf(__('Actualités correspondant au tag %s', 'extra'), single_tag_title('', false));
		}


		// AUTHOR
		else if(is_author()) {
			global $author;
			$userdata = get_userdata($author);
			$current_post['name'] = sprintf(__('Actualités rédigées par %s', 'extra'), $userdata->display_name);
		}


		// 404
		else if (is_404()) {
			$current_post['name'] = __('Erreur 404', 'extra');
		}

		// PAGINATE
		if(get_query_var('paged')) {
			$name = '';
			if(is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
				$name .= ' (';
			}
			$name .=  __('Page', 'extra').' '.get_query_var('paged');
			if(is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
				$name .=  ')';
			}

			$current_post['name'] .= $name;
		}

		$home = apply_filters('extra_arianne_home', $home);

		$parents = apply_filters('extra_arianne_parents', $parents);

		$current_post = apply_filters('extra_arianne_current', $current_post);

		$delimiter = apply_filters('extra_arianne_delimiter', '');

		$breadcrumbs = array();
		$breadcrumbs[] = $home;
		$breadcrumbs = array_merge($breadcrumbs, $parents);
		$breadcrumbs[] = $current_post;

		$first = true;

		foreach ($breadcrumbs as $breadcrumb) {
			if (!empty($breadcrumb['name'])) {
				if ($first) {
					$first = false;
				} else {
					echo $delimiter;
				}

				if (!empty($breadcrumb['class'])) {
					echo '<span class="'.$breadcrumb['class'].'">';
				}

				if (!empty($breadcrumb['link'])) {
					echo '<a href="'.$breadcrumb['link'].'">';
				}

				echo $breadcrumb['name'];

				if (!empty($breadcrumb['link'])) {
					echo '</a>';
				}

				if (!empty($breadcrumb['class'])) {
					echo '</span>';
				}
			}
		}
	}

	?>

	</div><!-- .inner -->

</div><!-- #arianne -->