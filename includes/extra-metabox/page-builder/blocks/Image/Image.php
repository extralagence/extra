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
 */
class Image extends AbstractBlock {

	public static function init () {
		parent::init();

		wp_enqueue_style('extra-page-builder-block-image', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Image/css/image.less');
		wp_enqueue_script('extra-page-builder-block-image', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Image/js/image.js', array('jquery'), null, true);
	}

	function __construct($mb, $type) {
		parent::__construct($mb, $type);
		$this->resizable = true;
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		if (empty($this->add_icon)) {
			$this->add_icon = 'icon-extra-page-builder-image';
		}
		if (empty($this->add_label)) {
			$this->add_label = __("Image", "extra-admin");
		}
	}

	public function the_admin($name_suffix) {
		$name = $name_suffix;
		$this->mb->the_field($name); ?>
		<input class="extra-page-builder-image-input" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" />
		<?php
	}

	public function the_preview($name_suffix) {
		$name = $name_suffix;
		$this->mb->the_field($name);

		$img_id = $this->mb->get_the_value();

		if(!empty($img_id)){
			$src =  wp_get_attachment_image_src( $img_id, 'full' );

			//echo '<div class="extra-page-builder-image"><img src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" /></div>';
			echo '<div class="extra-page-builder-image" style="background-image: url('.$src[0].');"></div>';
		} else {
			echo '<div class="extra-page-builder-image empty"></div>';
		}
	}

	public static function get_front($block_data, $name_suffix, $block_height) {
		$img_id = $block_data[$name_suffix];
		if(!empty($img_id)){
			$src =  wp_get_attachment_image_src( $img_id, 'full' );
			//$html = '<div class="extra-page-builder-image" style="background-image: url('.$src[0].');"></div>';
            $html = '<div class="extra-page-builder-image"><img src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" /></div>';
		} else {
			$html = '<div class="extra-page-builder-image empty"></div>';
		}

		return $html;
	}
}