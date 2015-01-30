<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

namespace ExtraPageBuilder\Blocks;

use ExtraPageBuilder\AbstractBlock;

/**
 * Class Map
 *
 * Define a map block
 *
 * type = map
 */
class Map extends AbstractBlock {

	public static function init () {
		parent::init();

		wp_enqueue_style('extra-page-builder-block-map-admin', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Map/css/map-admin.less');
		wp_enqueue_style('extra-page-builder-block-map-front', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Map/css/map-front.less');

		wp_enqueue_script(
			'extra-page-builder-block-custom-editor-plugin',
			EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/custom-editor-plugin.js',
			array('jquery', 'quicktags'),
			null,
			true
		);

		wp_enqueue_script(
			'extra-page-builder-block-custom-editor-iframe-resizer',
			EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/iframeResizer.js',
			array('jquery'),
			null,
			true
		);

		wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBpFeTSnmCMi1Vb3LuLoAivc4D4CeA2YJs&sensor=false', array('jquery'), null, true);
		wp_enqueue_script('extra-page-builder-block-map-admin', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Map/js/map-admin.js', array('jquery', 'google-maps-api', 'extra-page-builder-block-custom-editor-plugin', 'extra-page-builder-block-custom-editor-iframe-resizer'), null, true);
		wp_enqueue_script('extra-page-builder-block-map-front', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Map/js/map-front.js', array('jquery', 'google-maps-api'), null, true);


		//iframeResizerContentWindow
		wp_localize_script('extra-page-builder-block-map-admin', 'iframeResizerContentWindow', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/iframeResizer.contentWindow.js');
	}

	function __construct($mb, $type) {
		parent::__construct($mb, $type);
		$this->resizable = true;
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		if (empty($this->add_icon)) {
			$this->add_icon = 'icon-extra-page-builder-map';
		}
		if (empty($this->add_label)) {
			$this->add_label = __("Carte", "extra-admin");
		}
	}

	public function the_admin($name_suffix) {
		$extra_map_options = apply_filters('extra_map_options', null);
		wp_localize_script('extra-page-builder-block-map-front', 'extra_map_options', $extra_map_options);
		?>
		<div class="extra-map-wrapper">
			<div class="extra-map">
				<h2><?php _e("Paramètre de la carte", "extra-admin"); ?></h2>

				<!-- TITLE -->
				<?php $this->mb->the_field('title_'.$name_suffix); ?>
				<label for="<?php $this->mb->the_name(); ?>"><?php _e("Titre de la carte", "extra-admin"); ?></label>
				<input class="title" type="text" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" value="<?php echo $this->mb->get_the_value(); ?>"/>

				<!-- LATITUDE -->
				<?php $this->mb->the_field('lat_'.$name_suffix);
				$field = $this->mb->get_the_value(); ?>
				<input class="lat" type="hidden" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" value="<?php
				echo (!empty($field)) ? $field : '45.7681906';
				?>"/>

				<!-- LONGITUDE -->
				<?php $this->mb->the_field('lon_'.$name_suffix);
				$field = $this->mb->get_the_value(); ?>
				<input class="lon" type="hidden" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" value="<?php
				echo (!empty($field)) ? $field : '4.84568790000003';
				?>"/>

				<!-- ADDRESS -->
				<?php $this->mb->the_field('address_map_'.$name_suffix);
				$field = $this->mb->get_the_value();
				?>
				<p>
					<label for="<?php $this->mb->the_name(); ?>"><?php _e("Adresse pour la carte", "extra-admin"); ?></label>
					<input class="address" type="text" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" value="<?php echo $field; ?>" />
				</p>

				<!-- MAP -->
				<div class="map-container"></div>
				<br>
				<h2><?php _e("Description associée", "extra-admin"); ?></h2>
				<!-- DESCRIPTION -->
				<div class="extra-map-custom-editor-wrapper">
					<?php
					$editor_name = 'description_'.$name_suffix;
					$custom_editor = new CustomEditor($this->mb, 'custom_editor');
					$custom_editor->the_admin($editor_name);
					?>
				</div>
			</div>
		</div>
		<?php
	}

	public function the_preview($name_suffix, $block_width) {
		$extra_map_options = apply_filters('extra_map_options', null);
		wp_localize_script('extra-page-builder-block-map-front', 'extra_map_options', $extra_map_options);

		$lat = $this->mb->get_the_value('lat_'.$name_suffix);
		$lon = $this->mb->get_the_value('lon_'.$name_suffix);
		$title = $this->mb->get_the_value('title_'.$name_suffix);
		$description = $this->mb->get_the_value('description_'.$name_suffix);

		$html = 	'<div class="extra-page-builder-map-wrapper">';
		$html .= 	'	<h2 class="extra-page-builder-map-title">'.$title.'</h2>';
		$html .= 	'	<div class="extra-page-builder-map" data-lat="'.$lat.'" data-lon="'.$lon.'"></div>';
		$html .= 	'	<div class="extra-page-builder-map-description-wrapper"><div class="extra-page-builder-map-description">'.apply_filters('the_content', html_entity_decode( $description, ENT_QUOTES, 'UTF-8' )).'</div></div>';
		$html .= 	'</div>';

		echo $html;
	}

	public static function get_front($block_data, $name_suffix, $block_height, $block_width) {
		$extra_map_options = apply_filters('extra_map_options', null);
		wp_localize_script('extra-page-builder-block-map-front', 'extra_map_options', $extra_map_options);

		$lat = (isset($block_data['lat_'.$name_suffix])) ? $block_data['lat_'.$name_suffix] : null;
		$lon = (isset($block_data['lon_'.$name_suffix])) ? $block_data['lon_'.$name_suffix] : null;
		$title = (isset($block_data['title_'.$name_suffix])) ? $block_data['title_'.$name_suffix] : null;
		$description = (isset($block_data['description_'.$name_suffix])) ? $block_data['description_'.$name_suffix] : '';

		$block_height_html = ($block_height != null) ? ' style="height: '.$block_height.';"' : '';

		$html = 	'<div class="extra-page-builder-map-wrapper">';
		if (!empty($title)) {
			$html .= 	'	<h2 class="extra-page-builder-map-title">'.$title.'</h2>';
		}
		$html .= 	'	<div class="extra-page-builder-map" data-lat="'.$lat.'" data-lon="'.$lon.'"'.$block_height_html.'></div>';
		$html .= 	'	<div class="extra-page-builder-map-description">'.apply_filters('the_content', html_entity_decode( $description, ENT_QUOTES, 'UTF-8' )).'</div>';
		$html .= 	'</div>';

		return $html;
	}
}