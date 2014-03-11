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

	/**
	 * @param $name
	 * @return string
	 */
	protected function get_prefixed_field_name($field_name) {
		if (!empty($this->name)) {
			return $this->name.'_'.$field_name;
		} else {
			return $field_name;
		}
	}

	public abstract static function init ();
	public abstract function the_admin($bloc_classes);
	public abstract function get_data();
}