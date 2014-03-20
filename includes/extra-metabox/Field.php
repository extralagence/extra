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
	protected $label;
	protected $icon;
	protected $css_class;

	function __construct(ExtraMetaBox $mb) {
		$this->mb = $mb;
	}

	private function get_field_name($field_name, $pefix_separator) {
		if (!empty($this->name)) {
			if ($pefix_separator != null) {
				return $this->name.$pefix_separator.$field_name;
			} else {
				return $this->name;
			}
		} else {
			return $field_name;
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
		return $this->get_field_name($field_name, $separator);
	}

	protected function get_single_field_name($field_name) {
		return $this->get_field_name($field_name, null);
	}

	public static function init() {
		wp_enqueue_style('extra-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-metabox.less');
	}

	public function extract_properties($properties) {
		$this->name = $properties['name'];
		$this->label = $properties['label'];
		$this->icon = $properties['icon'];
		$this->css_class = $properties['css_class'];
	}

	public abstract function the_admin();
}