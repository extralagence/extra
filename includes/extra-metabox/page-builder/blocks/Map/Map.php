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
class Map extends AbstractBlock {

	public static function init () {
		parent::init();

//		wp_enqueue_style('extra-page-builder-block-image', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/image/css/image.less');
//		wp_enqueue_script('extra-page-builder-block-image', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/image/js/image.js', array('jquery'), null, true);
	}

	public function the_admin($name) {
		?>

		<?php
	}

	public function the_preview($name) {
		$this->mb->the_field($name);

	}

	public function the_front($name) {
		
	}
}