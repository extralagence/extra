<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

namespace ExtraPageBuilder\Blocks;

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
class Slider extends AbstractResizableBlock {

	public static function init () {
		parent::init();

//		wp_enqueue_style('extra-page-builder-block-image', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/image/css/image.less');
//		wp_enqueue_script('extra-page-builder-block-image', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/image/js/image.js', array('jquery'), null, true);
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

	}

	public function the_preview($name_suffix) {
		?>
	<?php
	}

	public static function get_front($block_data, $name_suffix) {
		$html = '';

		return $html;
	}
}