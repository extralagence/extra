<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

namespace ExtraPageBuilder\Blocks;

use ExtraPageBuilder\AbstractBlock;
use ExtraPageBuilder\AbstractResizableBlock;

/**
 * Class Image
 *
 * Define a image block
 *
 * type = image
 *
 * Options :
 * - name (required)
 * - add_label (required)
 * - add_icon (required)
 */
class Map extends AbstractResizableBlock {

	public static function init () {
		parent::init();

		wp_enqueue_style('extra-page-builder-block-map-admin', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Map/css/map-admin.less');
		wp_enqueue_style('extra-page-builder-block-map-front', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Map/css/map-front.less');

		wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBpFeTSnmCMi1Vb3LuLoAivc4D4CeA2YJs&sensor=false', array('jquery'), null, true);
		wp_enqueue_script('extra-page-builder-block-map-admin', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Map/js/map-admin.js', array('jquery', 'google-maps-api'), null, true);
		wp_enqueue_script('extra-page-builder-block-map-front', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Map/js/map-front.js', array('jquery', 'google-maps-api'), null, true);
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
		?>
		<div class="extra-map-wrapper">
			<h2><?php _e("Paramètre de la carte", "extra-admin"); ?></h2>
			<div class="extra-map">
				<!-- ADDRESS -->
				<?php $this->mb->the_field('address_'.$name_suffix); ?>
				<p><label for="<?php $this->mb->the_name(); ?>"><?php _e("Adresse à afficher", "extra-admin"); ?></label>
					<textarea id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>"><?php $this->mb->the_value(); ?></textarea></p>
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
			</div>
		</div>
		<?php
	}

	public function the_preview($name_suffix) {
		$lat = $this->mb->get_the_value('lat_'.$name_suffix);
		$lon = $this->mb->get_the_value('lon_'.$name_suffix);
		?>
		<div class="extra-page-builder-map" data-lat="<?php echo $lat; ?>" data-lon="<?php echo $lon; ?>"></div>
		<?php
	}

	public static function get_front($block_data, $name_suffix) {
		$lat = $block_data['lat_'.$name_suffix];
		$lon = $block_data['lon_'.$name_suffix];

		$html = '<div class="extra-page-builder-map" data-lat="'.$lat.'" data-lon="'.$lon.'"></div>';

		return $html;
	}
}