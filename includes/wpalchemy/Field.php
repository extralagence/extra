<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:01
 */

abstract class Field {

	protected $mb;
	protected $name;

	function __construct(ExtraMetaBox $mb, $name) {
		$this->mb = $mb;
		$this->name = $name;
	}

	private function get_field_name($field_name, $is_prefix) {
		if (!empty($this->name)) {
			if ($is_prefix) {
				return $this->name.'_'.$field_name;
			} else {
				return $this->name;
			}
		} else {
			return $field_name;
		}
	}

	/**
	 * @param $name
	 * @return string
	 */
	protected function get_prefixed_field_name($field_name) {
		return $this->get_field_name($field_name, true);
	}

	protected function get_single_field_name($field_name) {
		return $this->get_field_name($field_name, false);
	}

	public abstract static function init ();
	public abstract function the_admin($bloc_classes);
	public abstract function get_data();
	public abstract function extract_properties($properties);
}