<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Gallery
 *
 * Define a gallery metabox
 *
 * type = gallery
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
class Gallery extends AbstractField {

	public static function init () {
		parent::init();
		add_action('admin_enqueue_scripts', function() {
			wp_enqueue_style('extra-image-gallery-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-image-gallery.less');
			wp_enqueue_script('extra-gallery-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-gallery.js', array('jquery'), null, true);
		});
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?>">
			<?php if ($this->title != null) : ?>
				<h2><?php
					echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
					echo $this->title; ?>
				</h2>
			<?php endif; ?>
			<div class="extra-custom-gallery">
				<?php $this->mb->the_field($this->get_single_field_name("gallery_shortcode")); ?>
				<a href="#" class="button choose-button"><?php _e("Éditer la galerie d'images", "extra"); ?></a>
				<div class="thumbs"><?php
					$ids = $this->mb->get_the_value();
					if(isset($ids) && !empty($ids) && sizeof($ids) > 0) {
						$ids = explode(",", $ids);
						foreach($ids as $id) {
							$src = wp_get_attachment_image_src($id, 'thumbnail');
							echo '<span class="image"><img data-id="'.$id.'" src="'.$src[0].'" width="150" /><a class="close" href="#close"><span class="dashicons dashicons-no"></span></a></span>';
						}
					}
					?>
				</div>
				<input class="gallery-input" type="hidden" value="<?php $this->mb->the_value(); ?>" name="<?php $this->mb->the_name(); ?>" />
			</div>
		</div>
		<?php
	}

	public static function explodeGalleryShortCode($shortcodes) {
		$data = array();
		if (!empty($shortcodes)) {
			$data = explode(',', $shortcodes);
		}

		return $data;
	}

	public function the_admin_column_value() {
		//TODO
		echo '-';
	}
}