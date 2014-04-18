<?php
/**********************
 *
 *
 *
 * THEME CONFIG
 *
 *
 *
 *********************/
if ( ! function_exists( 'extra_setup' ) ) {
	function extra_setup() {
		// LANGUAGE
		load_theme_textdomain('extra', get_template_directory().'/includes/lang');

		// DEFAULT POST THUMBNAIL SIZE
		add_theme_support('post-thumbnails', array('post', 'page'));

		// AUTO RSS
		add_theme_support( 'automatic-feed-links' );
        
        // HTML 5
        add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

		// NAVIGATION MENUS
		register_nav_menus( array(
			'main' => 'Principale',
			'mobile' => 'Mobile',
			'footer' => 'Pied de page'
		));

		// CAP
		$editor = get_role( 'editor' );
		$editor->add_cap( 'manage_options' );

		global $content_width;
		$content_width = 540;
	}
}
add_action('after_setup_theme', 'extra_setup');

/**********************
 *
 *
 *
 * FAVICON
 *
 *
 *
 *********************/
function extra_favicon() {  ?>
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo home_url("/"); ?>apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo home_url("/"); ?>apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo home_url("/"); ?>apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo home_url("/"); ?>apple-touch-icon-144x144.png" />
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo home_url("/"); ?>apple-touch-icon-60x60.png" />
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo home_url("/"); ?>apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo home_url("/"); ?>apple-touch-icon-76x76.png" />
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo home_url("/"); ?>apple-touch-icon-152x152.png" />
	<link rel="icon" type="image/png" href="<?php echo home_url("/"); ?>favicon-16x16.png" sizes="16x16" />
	<link rel="icon" type="image/png" href="<?php echo home_url("/"); ?>favicon-32x32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="<?php echo home_url("/"); ?>favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/png" href="<?php echo home_url("/"); ?>favicon-160x160.png" sizes="160x160" />
	<meta name="msapplication-TileColor" content="#2d89ef" />
	<meta name="msapplication-TileImage" content="<?php echo home_url("/"); ?>/mstile-144x144.png" />
<?php }
add_action('wp_head', 'extra_favicon');
add_action('admin_head', 'extra_favicon');
add_action('login_head', 'extra_favicon');
/**********************
 *
 *
 *
 * BODY CLASSES
 *
 *
 *
 *********************/
function extra_body_class($classes) {
	if(is_page()) {
		global $post;
		$classes[] = 'page-'.$post->post_name;
	}
	return $classes;
}
add_filter('body_class', 'extra_body_class');
/**********************
 *
 *
 *
 * CONTACT FORM 7
 *
 *
 *
 *********************/
function extra_wpcf7_ajax_loader () {
	return get_template_directory_uri() . '/assets/img/loading.gif';
}
add_filter('wpcf7_ajax_loader', 'extra_wpcf7_ajax_loader');
/**********************
 *
 *
 *
 * CUSTOM SEARCH
 *
 *
 *
 *********************/
function extra_search_form($form) {
	$form = '
	<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
		<label for="s">'.__("Une recherche ?", "extra").'</label>
		<input type="text" value="'.get_search_query().'" name="s" id="s" />
		<button type="submit" id="searchsubmit"><span class="icon icon-search"></span><span class="text">'.__('Valider', 'extra').'</span></button>
	</form>
	';
	return $form;
}
add_filter( 'get_search_form', 'extra_search_form' );
/**********************
 *
 *
 *
 * EXCERPT
 *
 *
 *
 *********************/
// LENGTH
function extra_excerpt_length($length) {
	return 35;
}
add_filter('excerpt_length', 'extra_excerpt_length', 999);
// TEXT
function extra_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'extra_excerpt_more');
/**********************
 *
 *
 *
 * NO 10px MARGIN CAPTION
 *
 *
 *
 *********************/
function extra_img_caption_shortcode($x = null, $attr, $content){
	extract(shortcode_atts(array(
		'id'	=> '',
		'align'	=> 'alignnone',
		'width'	=> '',
		'caption' => ''
	), $attr));
	if ( 1 > (int) $width || empty($caption) )
		return $content;
	if ( $id ) $id = 'id="' . $id . '" ';
	return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . (0 + (int) $width) . 'px">' . do_shortcode( $content ) . '<div class="wp-caption-text">' . $caption . '</div></div>';
}
add_filter('img_caption_shortcode', 'extra_img_caption_shortcode', 10, 3);
/**********************
 *
 *
 *
 * DEFINE LANGUAGE CONSTANTS
 *
 *
 *
 *********************/
define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
define('ICL_DONT_LOAD_LANGUAGES_JS', true);
/**********************
 *
 *
 *
 * LANGUAGE SWITCHER
 *
 *
 *
 *********************/
function extra_language_switcher(){
	if(function_exists('icl_get_languages')) {
		$languages = icl_get_languages('skip_missing=0');
		if(1 < count($languages)){
			echo '<li class="language-switcher"><div class="inner">';
			foreach($languages as $l){
				if($l['active']) echo '<span class="'.$l['language_code'].' active">'.$l['language_code'].'</span>';
			}
			foreach($languages as $l){
				if(!$l['active']) echo '<a class="'.$l['language_code'].'" href="'.$l['url'].'">'.$l['language_code'].'</a>';
			}
			echo '</div></li>';
		}
	}
}
/**********************
 *
 *
 *
 * HOOK MENU CLASSES
 *
 *
 *
 *********************/
function extra_nav_menu_css_class_home($classes, $item){

	global $extra_options;

	if(get_option("page_on_front") == $item->object_id) {
		$classes[] = "menu-item-home";
	}

	/*if((is_singular("post") || is_singular("event")) && $extra_options["page-agenda"] == $item->object_id) {
		$classes[] = "current-page-ancestor current-menu-ancestor current_page_ancestor";
	}*/

	return $classes;
}
add_filter('nav_menu_css_class' , 'extra_nav_menu_css_class_home' , 10 , 2);

/**********************
 *
 *
 *
 * HOOK MENU TITLES
 *
 *
 *
 *********************/
function extra_hook_nav_menu_footer ($items, $menu, $args) {
//	global $extra_options;

	foreach ($items as $item) {
		if ($item->object_id === get_option('page_on_front')) {
			$item->title = '<span class="icon icon-home"></span><span class="text">'.$item->title.'</span>';
		}
	}
	return $items;
}
add_filter('wp_get_nav_menu_items','extra_hook_nav_menu_footer', 10, 3);

function remove_parent_classes($class)
{
	// check for current page classes, return false if they exist.
	return ($class == 'current_page_item' || $class == 'current_page_parent' || $class == 'current_page_ancestor'  || $class == 'current-menu-item') ? FALSE : TRUE;
}
/**********************
 *
 *
 *
 * CONTACT FORM / CUSTOM SUBMIT
 *
 *
 *
 *********************/
add_action('init', 'extra_add_shortcode_submit', 10);
function extra_add_shortcode_submit() {
	if(function_exists('wpcf7_remove_shortcode')) {
		wpcf7_remove_shortcode( 'submit', 'wpcf7_submit_shortcode_handler' );
		wpcf7_add_shortcode( 'submit', 'extra_submit_shortcode_handler' );
	}
}
function extra_submit_shortcode_handler( $tag ) {
	$tag = new WPCF7_Shortcode( $tag );

	$class = wpcf7_form_controls_class( $tag->type );

	$atts = array();

	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_option( 'id', 'id', true );
	$atts['tabindex'] = $tag->get_option( 'tabindex', 'int', true );

	$value = isset( $tag->values[0] ) ? $tag->values[0] : '';

	if ( empty( $value ) )
		$value = __( 'Send', 'wpcf7' );

	$atts['type'] = 'submit';
	//$atts['value'] = $value;

	$atts = wpcf7_format_atts( $atts );

	$html = sprintf( '<button %1$s>%2$s</button>', $atts, $value );

	return $html;
}

/**
 * echo reponsive image
 * @param $src $source
 * @param array $params $params['desktop'] $params['tablet'] $params['mobile'] required
 * @param string $class add custom classes
 * @param string $alt
 */
function extra_get_responsive_image($src = 0, $params= array(), $class = '', $alt = '') {

	// hook it to override available sizes
	$sizes = apply_filters('extra_responsive_sizes', array(
		'desktop' => 'only screen and (min-width: 961px)',
		'tablet' => 'only screen and (min-width: 691px) and (max-width: 960px)',
		'mobile' => 'only screen and (max-width: 690px)'
	));

	if(is_numeric($src)) {
		$src = wp_get_attachment_image_src($src, 'full');
		$src = $src[0];
		if(empty($alt)) {
			$alt = get_post_meta($src, '_wp_attachment_image_alt', true);
			if(empty($alt)) {
				$alt = get_the_title($src);
			}
		}
	} else if(empty($src)) {
		throw new Exception(__("La source de l'image est vide", 'extra'));
	} else if(!filter_var($src, FILTER_VALIDATE_URL)) {
		throw new Exception(__("La source de l'image n'est pas valide", 'extra') . ' : ' . $src);
	}

	foreach($sizes as $size => $details) {
		if(!array_key_exists($size, $params)) {
			throw new Exception(sprintf(__("Il manque la taille d'image <em>%s</em>", 'extra'), $size));
		}
	}

	?>
	<?php ob_start(); ?>
	<span class="responsiveImagePlaceholder<?php echo (!empty($class)) ? ' ' . $class : ''; ?>">
		<noscript
			data-alt="<?php echo $alt; ?>"
			<?php foreach($sizes as $size => $details): ?>
			data-src-<?php echo $size; ?>="<?php echo bfi_thumb($src, $params[$size]); ?>"
			<?php endforeach; ?>
			<img alt="<?php echo $alt; ?>" src="<?php echo $src; ?>">
		</noscript>
		<img class="placeholder-image"
		     src="<?php echo get_template_directory_uri(); ?>/assets/img/blank.gif"
		     alt="<?php echo $alt; ?>"
		     style="<?php echo (!empty($params['desktop']['width'])) ? 'width: ' . $params['desktop']['width'] . 'px;' : '';
			 echo (!empty($params['desktop']['height'])) ? ' height: ' . $params['desktop']['height'] . 'px;' : ''; ?>" />
	</span>
	<?php $return = ob_get_contents(); ?>

<?php
	ob_end_clean();
	return $return;
}
function extra_responsive_image($src = 0, $params= array(), $class = '', $alt = '') {
	echo extra_get_responsive_image($src, $params, $class, $alt);
}

/**
 * Shortify a string with "..."
 *
 * @param $text
 * @param $max_length
 *
 * @return null|string
 */
function extra_shortify_text ($text, $max_length) {
	if (strlen($text) > $max_length) {
		$text_array = explode(' ', $text);
		$text = null;
		foreach ($text_array as $text_part) {
			if ($text == null) {
				$text = $text_part;
				if (strlen($text) > $max_length) {
					$text = substr($text, 0, $max_length-1).'...';
					break;
				}
			} else if (strlen($text.' '.$text_part) <= $max_length) {
				$text .= ' '.$text_part;
			} else {
				$text .= '...';
				break;
			}
		}
	}

	return $text;
}

function extra_get_archive_title ($id = 0) {
	global $post;
	$old_post = $post;

	if ($id != 0) {
		$post = get_post($id);
	}

	$title = null;
	if(isset($post) && !empty($post)) {
		// CATEGORY
		if (is_category()) {
			$title = sprintf(__('Archive de la catégorie "%s"', 'extra'), single_cat_title('', false));
		}

		// SEARCH
		else if (is_search()) {
			$title = sprintf(__('Résultats pour la recherche "%s"', 'extra'), get_search_query());
		}

		// TIME - DAY
		else if (is_day()) {
			$title = sprintf(__('Archive du %s', 'extra'), get_the_time('d F Y'));

		}

		// TIME - MONTH
		else if (is_month()) {
			$title = sprintf(__('Archive %s', 'extra'), get_the_time('F Y'));
		}

		// TIME - YEAR
		else if (is_year()) {
			$title = sprintf(__('Archive %s', 'extra'), get_the_time('Y'));
		}
	}

	$post = $old_post;

	return $title;
}

function extra_the_archive_title ($id = 0) {
	echo extra_get_archive_title($id);
}