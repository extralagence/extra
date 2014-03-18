<?php
/*
 * Extra Pagination
 *
 * (c) 2013 Jean-Baptiste Janot / Extra l'agence
 *
 */
global $wp_query, $wp_rewrite;
$current = ($wp_query->query_vars['paged'] > 1) ? $wp_query->query_vars['paged'] : 1;
$pagination = array(
	'base'         => @add_query_arg('page','%#%'),
	'format'       => '',
	'total'        => $wp_query->max_num_pages,
	'current'      => $current,
    'show_all'     => false,
    'end_size'     => 1,
    'mid_size'     => 2,
	'type'         => 'array',
	'next_text'    => apply_filters('extra_pagination_next_link_text', __('Suivant', 'extra')),
	'prev_text'    => apply_filters('extra_pagination_prev_link_text', __('Précédent', 'extra'))
);
if($wp_rewrite->using_permalinks()) {
	$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
}
if(!empty($wp_query->query_vars['s'])) {
	$pagination['add_args'] = array(
	   's' => get_query_var('s')
    );
}
$links = paginate_links( $pagination );
if(sizeof($links)):
?>

<div class="pagination">
	<ul>

		<?php foreach($links as $link):
		?>
		<li>
			<?php echo $link; ?><span class="separator"></span>
		</li>
		<?php endforeach; ?>
	</ul>
</div>

<?php endif; ?>