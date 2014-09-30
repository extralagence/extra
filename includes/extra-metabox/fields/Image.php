<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Image
 *
 * Define a image metabox
 *
 * type = image
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
class Image extends AbstractField {

	protected $sizes;
	protected $size_label;

	public static function init () {

		parent::init();
		add_action( 'admin_enqueue_scripts', function () {
			wp_enqueue_media();
			wp_enqueue_style('extra-image-gallery-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-image-gallery.less');
			wp_enqueue_script('extra-image-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-image.js', array('jquery'), null, true);
		});
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?>">
			<?php if (!empty($this->title)) : ?>
				<h2>
					<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
					<?php echo ($this->title == null) ? __('Image', 'extra-admin') : $this->title; ?>
				</h2>
			<?php endif; ?>
			<?php $this->mb->the_field($this->get_single_field_name("image")); ?>
			<div class="extra-custom-image">

				<div class="floater">
					<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? __('Sélectionner une image', 'extra-admin') : $this->label; ?></label>
					<input class="image-input" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" />
					<input class="choose-button button" type="button" value="<?php _e("Ouvrir le gestionnaire d'images", "extra-admin"); ?>" />
				</div>

				<?php
				$imgid = $this->mb->get_the_value();

				$width = null;
				$height = null;
				$size_value = $this->mb->get_the_value($this->get_prefixed_field_name('size'));
				if (!empty($size_value) && !empty($this->sizes) && array_key_exists($size_value, $this->sizes)) {
					$size = $this->sizes[$size_value];
					$width = $size['width'];
					$height = $size['height'];
				}

				if(!empty($imgid)){
					// TODO IF YOU WANT A FULL SIZE IMAGE USE SIZE CASE !
					$src =  wp_get_attachment_image_src( $imgid, 'thumbnail' );
					$width = ($width == null) ? $src[1] : $width;
					$height = ($height == null) ? $src[2] : $height;

					echo '<div class="image"><img src="'.$src[0].'" width="'.$width.'" height="'.$height.'" /><a class="close" href="#close"><span class="dashicons dashicons-no"></span></a></div>';
				} else {
					echo '<div class="image empty"><img src="" width="'.$width.'" height="'.$height.'" /></div>';
				}
				?>

				<?php if (!empty($this->sizes)) : ?>
					<?php
					$this->mb->the_field($this->get_prefixed_field_name('size'));
					$size = $this->sizes[$this->mb->get_the_value()];
					wp_localize_script('extra-image-metabox', 'selected_size', $size);
					?>
					<div class="extra-custom-image-sizes">
						<p><?php echo ($this->size_label != null) ? $this->size_label : __("Sélectionnez une taille", "extra-admin") ?></p>
						<?php foreach ($this->sizes as $size_value => $size) : ?>
							<label for="<?php $this->mb->the_name(); ?>-<?php echo $size_value ?>"><?php echo $size['label']; ?></label>
							<input
								class="extra-custom-image-size-input"
								id="<?php $this->mb->the_name(); ?>-<?php echo $size_value ?>"
								type="radio"
								name="<?php $this->mb->the_name(); ?>"
								value="<?php echo $size_value ?>"<?php echo $this->mb->is_value($size_value)?' checked="checked"':''; ?>
								data-width="<?php echo $size['width']; ?>"
								data-height="<?php echo $size['height']; ?>"
								data-label="<?php echo $size['label']; ?>"
								>
							<br>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	public function the_front() {
		$this->mb->the_field($this->get_single_field_name("image"));

		$imgid = $this->mb->get_the_value();

		$width = null;
		$height = null;
		$size_value = $this->mb->get_the_value($this->get_prefixed_field_name('size'));
		if (!empty($size_value) && !empty($this->sizes) && array_key_exists($size_value, $this->sizes)) {
			$size = $this->sizes[$size_value];
			$width = $size['width'];
			$height = $size['height'];
		}

		if(!empty($imgid)){
			$src =  wp_get_attachment_image_src( $imgid, 'thumbnail' );
			$width = ($width == null) ? $src[1] : $width;
			$height = ($height == null) ? $src[2] : $height;

			echo '<div class="image"><img src="'.$src[0].'" width="'.$width.'" height="'.$height.'" /></div>';
		} else {
			echo '<div class="image empty"><img src="" width="'.$width.'" height="'.$height.'" /></div>';
		}
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->sizes = isset($properties['sizes']) ? $properties['sizes'] : array();
		$this->size_label = isset($properties['size_label']) ? $properties['size_label'] : null;
	}

	public function the_admin_column_value() {
		//TODO
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		echo $meta;
	}
}