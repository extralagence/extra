<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:01
 */

namespace ExtraPageBuilder;

abstract class AbstractBlock {

	protected $mb;
	protected $type;
	protected $add_label;
	protected $add_icon;
	protected $resizable = false;
	protected $editable = true;

	function __construct(\ExtraPageBuilder $mb, $type) {
		$this->mb = $mb;
		$this->type = $type;
	}

	public static function init() {
	}

	public function extract_properties($properties) {
		$this->add_label = isset($properties['add_label']) ? $properties['add_label'] : null;
		$this->add_icon = isset($properties['add_icon']) ? $properties['add_icon'] : null;
	}

	public abstract function the_admin($name_suffix);

	public abstract function the_preview($name_suffix, $block_width);

	public static function get_front($block_data, $name_suffix, $block_height, $block_width) {
		// To be override
	}

	/**
	 * @return mixed
	 */
	public function get_add_icon() {
		return $this->add_icon;
	}

	/**
	 * @return mixed
	 */
	public function get_add_label() {
		return $this->add_label;
	}

	/**
	 * @return mixed
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @return boolean
	 */
	public function is_resizable($name_suffix = null, $block_data = null) {
		return $this->resizable !== false;
	}

	/**
	 * @return boolean
	 */
	public function is_editable() {
		return $this->editable !== false;
	}
}