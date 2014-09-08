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
 * Class Fields
 *
 * Define a Fields block
 *
 * type = fields
 */
class MetaBox extends AbstractBlock {

	protected $fields = null;
	protected $name = null;

	public static function init () {
		parent::init();
		wp_enqueue_style('extra-page-builder-block-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/MetaBox/css/metabox.less');
	}

	function __construct($mb, $type) {
		parent::__construct($mb, $type);
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		if (empty($this->add_icon)) {
			//TODO prevoir une icon par défaut
			$this->add_icon = 'icon-extra-page-builder-image';
		}
		if (empty($this->add_label)) {
			$this->add_label = __('Custom', 'extra-admin');
		}
		$this->name = isset($properties['name']) ? $properties['name'] : null;
		$this->fields = isset($properties['fields']) ? $properties['fields'] : null;
	}

	public function the_admin($name_suffix) {
		if (!empty($this->fields)) {
			$this->mb->the_admin_from_field($this->fields, $name_suffix);
		}
	}

	public function the_preview($name_suffix, $block_width) {
		$html = '<div class="extra-page-builder-meta-box"><h1>'.$this->add_label.'</h1></div>';
		echo $html;
	}

	public static function get_front($block_data, $name_suffix, $block_height, $block_width) {

		// Find meta block name in name_suffix
		$meta_box_name = substr($name_suffix, strlen('meta_block_'));
		$meta_box_name_array = explode('_', $meta_box_name);
		array_pop($meta_box_name_array);
		$meta_box_name = implode('_', $meta_box_name_array);

		$html = apply_filters('extra_page_builder_meta_box_front_'.$meta_box_name, '', $block_data, $name_suffix, $block_height, $block_width);

		return $html;
	}
}