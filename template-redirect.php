
<?php 
/*
Template name: Redirection
*/
global $post, $redirection_metabox;
the_post();
$redirection_metabox->the_meta();
$data = $redirection_metabox->meta;

$redirection_type = 'auto';
if (isset($data['redirection_type']) && !empty($data['redirection_type'])) {
	$redirection_type = $data['redirection_type'];
	$redirection_value = $data['redirection_content'];
}

switch ($redirection_type) {
	case 'auto' :

		$pagekids = get_pages("child_of=".$post->ID."&sort_column=menu_order");
		if(!empty($pagekids)) {
			$firstchild = $pagekids[0];
			wp_redirect(get_permalink($firstchild->ID));
		} else {
			_e("Cette page n'a pas d'enfant", "extra");
		}
		break;

	case 'manual' :

		if(!empty($redirection_value)) {
			wp_redirect($redirection_value);
		} else {
			_e("Cette page n'a pas d'url de destination", "extra");
		}
		break;

	case 'content' :

		if(!empty($redirection_value)) {
			wp_redirect($permalink = get_permalink($redirection_value));
		} else {
			_e("Cette page n'a pas d'url de destination", "extra");
		}
		break;
}

die();


?>