<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

class Gallery extends Field {

	protected $label;

	public static function init () {
		wp_enqueue_script('extra-gallery-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-gallery.js', array('jquery'), null, true);
	}


	public function the_admin($bloc_classes) {
		?>
		<div class="bloc <?php echo $bloc_classes; ?>">
			<h2><?php echo ($this->label == null) ? __('Photos', 'extra-admin') : $this->label; ?></h2>
			<div class="extra_custom_gallery">
				<?php $this->mb->the_field($this->get_single_field_name("gallery_shortcode")); ?>
				<a href="#" class="button choose-button"><?php _e("Éditer la galerie d'images", "extra"); ?></a>
				<div class="thumbs"><?php
					$ids = $this->mb->get_the_value();
					$ids = explode(",", $ids);
					foreach($ids as $id) {
						$src = wp_get_attachment_image_src($id, 'thumbnail');
						echo '<span class="image"><img data-id="'.$id.'" src="'.$src[0].'" width="150" /></span>';
					}
					?>
				</div>
				<input class="gallery-input" type="hidden" value="<?php $this->mb->the_value(); ?>" name="<?php $this->mb->the_name(); ?>" />
			</div>
		</div>
		<?php
	}

	public function extract_properties($properties) {
		$this->label = $properties['label'];
	}

	public static function explodeGalleryShortCode($shortcodes) {
		$data = array();
		if (!empty($shortcodes)) {
			$data = explode(',', $shortcodes);
		}

		return $data;
	}
} 