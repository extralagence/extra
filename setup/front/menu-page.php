<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 11/02/2014
 * Time: 17:28
 */
global $post;

/**********************
 *
 *
 *
 * SUBMENU
 *
 *
 *
 *********************/

$has_no_children = false;
// SET PARENT
if ($post->post_parent !== 0){
	$ancestors = get_post_ancestors($post->ID);
	$parent = get_post($ancestors[count($ancestors)-1]);
} else {
	$parent = $post;

	$children = get_pages(
		array(
			'parent'     	=> $parent->ID,
			'sort_column'  	=> 'menu_order',
			'sort_order'	=> 'ASC'
		)
	);
	$has_no_children = empty($children);

}
$parent = apply_filters('set_submenu_parent', $parent);

// SELECTED
$selectedID = $post->ID;
$selectedID = apply_filters('set_submenu_selected', $selectedID);



function menu_page($id, $current_parent, $level = 1) {
	// SET ARGS
	$args = array(
		'parent'     	=> $current_parent->ID,
		'sort_column'  	=> 'menu_order',
		'sort_order'	=> 'ASC'
	);
	$children = get_pages($args);

	if (!empty($children)) {
		echo '<ul class="menu level'.$level.'">';
		foreach($children as $child) {

			if($child->post_parent == $current_parent->ID) {

				$selected = ($id == $child->ID) ? " current-page-item" : "";
				echo '<li class="page-item page-item-'.$child->ID.$selected.'">';
				if($id == $child->ID) {
					echo '<a href="'.get_permalink($child->ID).'">'.apply_filters('set_current_submenu_title', $child->post_title).'</a>';
				} else {
					echo '<a href="'.get_permalink($child->ID).'">'.apply_filters('set_'.$child->ID.'_submenu_title', $child->post_title).'</a>';
				}

				menu_page($id, $child, ($level+1));

				echo '</li>';
			}
		}
		echo '	</ul>';
	}
}
?>
<div class="menu-page<?php echo ($has_no_children) ? ' menu-page-empty' : ''; ?>">
	<?php
	$selected = ($post->ID == $parent->ID) ? " current-page-item" : "";
	$parent_page_template = get_post_meta($parent->ID, '_wp_page_template', true);
	echo '<div class="menu-title page-item-'.$parent->ID.$selected.'">';
	if($parent_page_template == 'template-redirect.php') {
		echo '<span>'.apply_filters('extra_set_submenu_parent_title', $parent->post_title).'</span>';
	} else {
		echo '<a href="'.get_permalink($parent->ID).'">'.apply_filters('extra_set_submenu_parent_title', $parent->post_title).'</a>';
	}
	echo '</div>';
	menu_page($selectedID, $parent);
	?>
</div>