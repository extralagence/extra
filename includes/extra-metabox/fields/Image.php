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
 * - name (mandatory)
 * - label (optional)
 * - icon (optional)
 */
class Image extends Field {

	public static function init () {
		parent::init();
		wp_enqueue_style('extra-image-gallery-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-image-gallery.less');
		wp_enqueue_script('extra-image-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-image.js', array('jquery'), null, true);
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?>">
			<h2>
				<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
				<?php echo ($this->label == null) ? __('Image', 'extra-admin') : $this->label; ?>
			</h2>
			<?php $this->mb->the_field($this->get_single_field_name("image")); ?>
			<div class="extra-custom-image">

				<div class="floater">
					<label for="<?php $this->mb->the_name(); ?>"><?php _e("Sélectionner une image", "extra-admin"); ?></label>
					<input class="image-input" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" />
					<input class="choose-button button" type="button" value="<?php _e("Ouvrir le gestionnaire d'images", "extra-admin"); ?>" />
				</div>

				<?php
				$imgid = $this->mb->get_the_value();
				if(!empty($imgid)){
					$src =  wp_get_attachment_image_src( $imgid, 'thumbnail' );
					echo '<div class="image"><img src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" /></div>';
				} else {
					echo '<div class="image empty"><img src="" /></div>';
				}
				?>
			</div>
		</div>
		<?php
	}
} 