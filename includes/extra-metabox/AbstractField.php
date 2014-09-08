<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:01
 */

abstract class AbstractField {

	protected $mb;
	protected $name;
	protected $name_suffix = '';
	protected $label;
	protected $icon;
	protected $css_class;

	protected $show_in_admin_column;
	protected $admin_column_label;

	function __construct(WPAlchemy_MetaBox $mb) {
		$this->mb = $mb;
	}

	protected static  function get_field_name($name, $field_name, $pefix_separator, $name_suffix = '') {
		if (!empty($name)) {
			if ($pefix_separator != null) {
				return $name.$pefix_separator.$field_name.$name_suffix;
			} else {
				return $name.$name_suffix;
			}
		} else {
			return $field_name.$name_suffix;
		}
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param $name
	 * @return string
	 */
	protected function get_prefixed_field_name($field_name, $separator = '_') {
		return AbstractField::get_field_name($this->name, $field_name, $separator, $this->name_suffix);
	}

	protected function get_single_field_name($field_name) {
		return AbstractField::get_field_name($this->name, $field_name, null, $this->name_suffix);
	}

	public static function init() {
		wp_enqueue_style('extra-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-metabox.less');
	}

	public function extract_properties($properties) {
		$this->name = isset($properties['name']) ? $properties['name'] : null;
		$this->label = isset($properties['label']) ? $properties['label'] : null;
		$this->title = isset($properties['title']) ? $properties['title'] : null;
		$this->icon = isset($properties['icon']) ? $properties['icon'] : null;
		$this->css_class = isset($properties['css_class']) ? $properties['css_class'] : null;

		$this->show_in_admin_column = isset($properties['show_in_admin_column']) ? $properties['show_in_admin_column'] : null;
		$this->admin_column_label = isset($properties['admin_column_label']) ? $properties['admin_column_label'] : null;
	}

	/**
	 * Used by ExtraPageBuilder
	 * @param $name_suffix
	 */
	public function set_name_suffix($name_suffix) {
		$this->name_suffix = '_'.$name_suffix;
	}

	public function the_admin_column_value() {
		return null;
	}

	public abstract function the_admin();


}