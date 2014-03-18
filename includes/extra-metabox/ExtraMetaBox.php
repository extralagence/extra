<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 09:23
 */

require_once 'MetaBox.php';
require_once 'Field.php';
require_once 'fields/Map.php';
require_once 'fields/Gallery.php';
require_once 'fields/Redirection.php';
require_once 'fields/Image.php';
require_once 'fields/Tabs.php';
require_once 'fields/Text.php';
require_once 'fields/Textarea.php';
require_once 'fields/CustomEditor.php';
require_once 'fields/Editor.php';
require_once 'fields/Bloc.php';
require_once 'fields/Slider.php';
require_once 'fields/Range.php';
require_once 'fields/Hidden.php';

class ExtraMetaBox extends WPAlchemy_MetaBox {

	public $fields;

	function __construct ($arr) {
		if (!isset($arr['template']) || empty($arr['template'])) {
			$arr['template'] = EXTRA_INCLUDES_PATH.'/extra-metabox/default_template.php';
		}
		parent::WPAlchemy_MetaBox($arr);
		$this->add_action('init', array($this, 'extra_init'));
	}

	function extra_init() {
		wp_enqueue_style('extra-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-metabox.less');
		Map::init();
		Gallery::init();
		Redirection::init();
		Image::init();
		Text::init();
		Textarea::init();
		CustomEditor::init();
		Editor::init();
		Hidden::init();

		Slider::init();
		Range::init();

		Bloc::init();
		Tabs::init();
	}

	private function get_name_from_properties($properties) {
		$name = (isset($properties['name'])) ? $properties['name'] : null;
		if ($name == null) throw new Exception ('Extra Meta box "name" required');

		return $name;
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
		$name = $this->get_name_from_properties($properties);

		if (!isset($properties['type'])) throw new Exception ('Extra Meta box "type" required');
		$array_type = explode('_', $properties['type']);
		$class = '';
		foreach ($array_type as $type) {
			$class .= ucfirst($type);
		}

		/**
		 * @var $field Field
		 */
		$field = new $class($this, $name);

		return $field;
	}

	public function the_admin($fields) {
		foreach($fields as $properties) {
			$field = $this->construct_field_from_properties($properties);
			$field->extract_properties($properties);

			$bloc_classes = (isset($properties['bloc_classes'])) ? $properties['bloc_classes'] : null;
			$field->the_admin($bloc_classes);
		}
	}

	/**
	 * !!!! DEPRECATED !!!!
	 */
	/**
	 * MAP
	 */
	public function the_admin_map ($name = '', $bloc_classes = '') {
		$field = new Map($this, $name);
		$field->the_admin($bloc_classes);
	}

	/**
	 * GALLERY
	 */
	public function the_admin_gallery ($name = '', $bloc_classes = '') {
		$field = new Gallery($this, $name);
		$field->the_admin($bloc_classes);
	}

	/**
	 * REDIRECTION
	 */
	public function the_admin_redirection ($name = '', $bloc_classes = '') {
		$field = new Redirection($this, $name);
		$field->the_admin($bloc_classes);
	}

	/**
	 * IMAGE
	 */
	public function the_admin_image ($name = '', $bloc_classes = '') {
		$field = new Image($this, $name);
		$field->the_admin($bloc_classes);
	}
}