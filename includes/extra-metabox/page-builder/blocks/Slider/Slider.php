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
 * Class Slider
 *
 * Define a slider block
 *
 * type = slider
 */
class Slider extends AbstractBlock {

	public static function init () {
		parent::init();

		wp_enqueue_style('extra-page-builder-block-slider-admin', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Slider/css/slider-admin.less');
		wp_enqueue_script('extra-page-builder-block-slider-admin', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Slider/js/slider-admin.js', array('jquery'), null, true);

		wp_enqueue_style('extra-page-builder-block-slider-preview', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Slider/css/slider-preview.less');

		add_action('print_media_templates', function() {

			// TODO Sortir ça du page builder
			// define your backbone template;
			// the "tmpl-" prefix is required,
			// and your input field should have a data-setting attribute
			// matching the shortcode name
			?>
			<script type="text/html" id="tmpl-extra-page-builder-slider-settings">
				<h3><?php _e('Réglages du carrousel', 'extra-admin'); ?></h3>
				<label class="setting">
					<span><?php _e('Type d\'animation', 'extra-admin'); ?></span>
					<select data-setting="extra_page_builder_slider_type">
						<option value="slide"> <?php _e('Slide', 'extra-admin'); ?> </option>
						<option value="fade"> <?php _e('Fade', 'extra-admin'); ?> </option>
					</select>
				</label>
				<label class="setting">
					<span><?php _e('Afficher <em>Suivant</em> et <em>Précédent</em>', 'extra-admin'); ?></span>
					<input data-setting="extra_page_builder_slider_navigate"
						   type="checkbox"
						   value="navigate"
						>
				</label>
				<label class="setting">
					<span><?php _e('Afficher la pagination', 'extra-admin'); ?></span>
					<input data-setting="extra_page_builder_slider_paginate"
						   type="checkbox"
						   value="paginate"
						>
				</label>
				<label class="setting">
					<span><?php _e('Navigation au clavier', 'extra-admin'); ?></span>
					<input data-setting="extra_page_builder_slider_keyboard"
						   type="checkbox"
						   value="keyboard"
						>
				</label>
				<label class="setting">
					<span><?php _e('Navigation tactile', 'extra-admin'); ?></span>
					<input data-setting="extra_page_builder_slider_draggable"
						   type="checkbox"
						   value="draggable"
						>
				</label>
				<label class="setting">
					<span><?php _e('Vitesse d’animation', 'extra-admin'); ?></span>
					<select data-setting="extra_page_builder_slider_speed">
						<option value="0.3"> <?php _e('Rapide', 'extra-admin'); ?> </option>
						<option value="0.5"> <?php _e('Normale', 'extra-admin'); ?> </option>
						<option value="1.0"> <?php _e('Lent', 'extra-admin'); ?> </option>
					</select>
				</label>
				<label class="setting">
					<span><?php _e('Défilement automatique', 'extra-admin'); ?></span>
					<select data-setting="extra_page_builder_slider_auto">
						<option value="false"> <?php _e('Aucun', 'extra-admin'); ?> </option>
						<option value="1.0"> <?php _e('Rapide', 'extra-admin'); ?> </option>
						<option value="5.0"> <?php _e('Normal', 'extra-admin'); ?> </option>
						<option value="10.0"> <?php _e('Lent', 'extra-admin'); ?> </option>
					</select>
				</label>
			</script>

			<script>
				jQuery(document).ready(function(){
					// add your shortcode attribute and its default value to the
					// gallery settings list; $.extend should work as well...

					_.extend(wp.media.gallery.defaults, {
						extra_page_builder_slider_type: 'slide',
						extra_page_builder_slider_navigate: true,
						extra_page_builder_slider_paginate: true,
						extra_page_builder_slider_keyboard: false,
						extra_page_builder_slider_draggable: false,
						extra_page_builder_slider_speed: '0.5',
						extra_page_builder_slider_auto: false
					});

					// merge default gallery settings template with yours
					wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
						template: function(view){
//							return wp.media.template('gallery-settings')(view)
//								+ wp.media.template('my-custom-gallery-setting')(view);

							if (view.model.is_extra_slider == true) {
								return wp.media.template('extra-page-builder-slider-settings')(view);
							}
							return wp.media.template('extra-gallery-type')(view);
//							return wp.media.template('gallery-settings')(view);
						}
					});
				});

			</script>
		<?php
		});
	}

	function __construct($mb, $type) {
		parent::__construct($mb, $type);
		$this->resizable = true;
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		if (empty($this->add_icon)) {
			$this->add_icon = 'icon-extra-page-builder-slider';
		}
		if (empty($this->add_label)) {
			$this->add_label = __("Carrousel", "extra-admin");
		}
	}

	public function the_admin($name_suffix) {
		?>
		<?php $this->mb->the_field($name_suffix); ?>
		<input class="extra-page-builder-gallery-input" type="hidden" value="<?php $this->mb->the_value(); ?>" name="<?php $this->mb->the_name(); ?>" />
		<?php $this->mb->the_field('properties_'.$name_suffix); ?>
		<input class="extra-page-builder-gallery-input-properties" type="hidden" value="<?php $this->mb->the_value(); ?>" name="<?php $this->mb->the_name(); ?>" />
		<?php
	}

	public function the_preview($name_suffix, $block_width) {
		$ids = $this->mb->get_the_value($name_suffix);
		$properties = $this->mb->get_the_value('properties_'.$name_suffix);
		?>
		<div class="extra-slider-preview" data-properties="<?php echo $properties; ?>">
			<?php
			if(isset($ids) && !empty($ids) && sizeof($ids) > 0) {
				$ids = explode(",", $ids);

				$id = $ids[0];
				$src = wp_get_attachment_image_src($id, 'full');
				?>
				<div class="image" style="background-image: url(<?php echo $src[0] ?>);"></div>
				<?php
			} else {
				echo '<div class="image empty"></div>';
			}
			?>
		</div>
		<?php
	}

	public static function get_front($block_data, $name_suffix, $block_height, $block_width) {
		$ids = $block_data[$name_suffix];
		$properties = $block_data['properties_'.$name_suffix];

		$block_height_html = ($block_height != null) ? ' style="height: '.$block_height.';"' : '';

		$html = '';
		$html .= '	<div class="extra-slider" data-properties="'.$properties.'"'.$block_height_html.'>';
		$html .= '		<div class="wrapper">';
		$html .= '			<ul>';

		if(isset($ids) && !empty($ids) && sizeof($ids) > 0) {
			$ids = explode(",", $ids);
			foreach($ids as $id)  {
				$attachment = get_post( $id );
				$html .= '<li>';
				$html .= extra_get_responsive_image($id, array(
					'desktop' => array(
						'height' => $block_height,
						'width' => $block_width
					),
					'tablet' => array(
						'height' => $block_height,
						'width' => $block_width
					),
					'mobile' => array(
						'height' => $block_height,
						'width' => $block_width
					)
				));
				if (!empty($attachment->post_excerpt)) {
					$html .= '<div class="legend">'.$attachment->post_excerpt.'</div>';
				}
				$html .= '</li>';
			}
		}

		$html .= '			</ul>';
		$html .= '		</div>';
		$html .= '	</div>';

		return $html;


		return $html;
	}
}