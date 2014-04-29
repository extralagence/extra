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
	protected $add_label;
	protected $add_icon;

	function __construct(\ExtraPageBuilder $mb) {
		$this->mb = $mb;
	}

	public static function init() {
	}

	public function extract_properties($properties) {
		$this->add_label = isset($properties['add_label']) ? $properties['add_label'] : null;
		$this->add_icon = isset($properties['add_icon']) ? $properties['add_icon'] : null;
	}

	public abstract function the_admin($name);

	public abstract function the_front($name);

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
}