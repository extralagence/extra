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
class Image extends AbstractBlock {

	public static function init () {
		parent::init();

		wp_enqueue_script('extra-page-builder-block-image', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/image/js/image.js', array('jquery'), null, true);
	}

	public function the_admin($name) {
		?>
		<div class="extra-field-form">
			<?php $this->mb->the_field($name); ?>
			<input class="image-input" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" />
		</div>
		<?php
	}

	public function the_front($name) {
		$this->mb->the_field($name);

		$imgid = $this->mb->get_the_value();

		if(!empty($imgid)){
			$src =  wp_get_attachment_image_src( $imgid, 'full' );
			$width = $src[1];
			$height = $src[2];

			echo '<div class="image"><img src="'.$src[0].'" width="'.$width.'" height="'.$height.'" /></div>';
		} else {
			echo '<div class="image empty"><img src="" /></div>';
		}
	}
}