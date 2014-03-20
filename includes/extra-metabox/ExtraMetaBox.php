<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 09:23
 */

require_once 'MetaBox.php';
require_once 'Field.php';

//Require once each fields
foreach (scandir(dirname(__FILE__).'/fields') as $filename) {
	$path = dirname(__FILE__).'/fields/'.$filename;
	if (is_file($path)) {
		require_once $path;
	}
}

class ExtraMetaBox extends WPAlchemy_MetaBox {

	public $fields;

	function __construct ($arr) {
		if (!isset($arr['template']) || empty($arr['template'])) {
			$arr['template'] = EXTRA_INCLUDES_PATH.'/extra-metabox/default_template.php';
		}
		parent::WPAlchemy_MetaBox($arr);
		$this->add_action('init', array($this, 'extra_init'));
	}

	private function initField($properties) {
		$class = $this->construct_class_name($properties);
		$class::init();

		if (isset($properties['subfields'])) {
			foreach ($properties['subfields'] as $child) {
				$this->initField($child);
			}
		}
	}

	public function extra_init() {
		if (isset($this->fields)) {
			foreach ($this->fields as $properties) {
				$this->initField($properties);
			}
		}
	}

	private function construct_class_name($properties) {
		if (!isset($properties['type'])) throw new Exception ('Extra Meta box "type" required');
		$array_type = explode('_', $properties['type']);
		$class = '';
		foreach ($array_type as $type) {
			$class .= ucfirst($type);
		}

		return $class;
	}

	/**
	 * Construct a field object from properties
	 *
	 * @param $properties
	 *
	 * @return Field
	 * @throws Exception
	 */
	private function construct_field_from_properties($properties) {
		$class = $this->construct_class_name($properties);
		/**
		 * @var $field Field
		 */
		$field = new $class($this);
		$field->extract_properties($properties);
		if ($field->getName() == null) throw new Exception ('Extra Meta box "name" required for '.$class);

		return $field;
	}

	public function the_admin($fields) {
		foreach($fields as $properties) {
			$field = $this->construct_field_from_properties($properties);
			$field->the_admin();
		}
	}
}