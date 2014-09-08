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
 * Class Separator
 *
 * Define a separator block
 *
 * type = separator
 */
class Separator extends AbstractBlock {

	public static function init () {
		parent::init();

		wp_enqueue_style('extra-page-builder-block-separator', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Separator/css/separator.less');
	}

	function __construct($mb, $type) {
		parent::__construct($mb, $type);
		$this->editable = false;
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		if (empty($this->add_icon)) {
			$this->add_icon = 'icon-extra-page-builder-separator';
		}
		if (empty($this->add_label)) {
			$this->add_label = __("Séparateur", "extra-admin");
		}
	}

	public function the_admin($name_suffix) {
		// Nothing ahah
	}

	public function the_preview($name_suffix, $block_width) {
		echo '<div class="extra-page-builder-separator"></div>';
	}

	public static function get_front($block_data, $name_suffix, $block_height, $block_width) {
		$html = '<div class="extra-page-builder-separator"></div>';

		return $html;
	}
}