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

		wp_enqueue_style('extra-page-builder-block-image', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Image/css/image.less');
		wp_enqueue_script('extra-page-builder-block-image', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Image/js/image.js', array('jquery'), null, true);
	}

	public function the_admin($name) {
		?>
		<?php $this->mb->the_field($name); ?>
		<input class="extra-page-builder-image-input" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" />
		<?php
	}

	public function the_preview($name) {
		$this->mb->the_field($name);

		$imgid = $this->mb->get_the_value();

		if(!empty($imgid)){
			$src =  wp_get_attachment_image_src( $imgid, 'full' );

			//echo '<div class="extra-page-builder-image"><img src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" /></div>';
			echo '<div class="extra-page-builder-image" style="background-image: url('.$src[0].');"></div>';
		} else {
			echo '<div class="extra-page-builder-image empty"><img src="" /></div>';
		}
	}

	public function the_front($name) {

	}
}