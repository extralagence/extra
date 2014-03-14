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
		Tabs::init();
		Text::init();
		Textarea::init();
		CustomEditor::init();
		Editor::init();
	}

	public function the_admin($fields) {
		foreach($fields as $properties) {
			if (!isset($properties['type'])) die ('Extra Meta box "type" required');

			$name = (isset($properties['name'])) ? $properties['name'] : null;
			if ($name == null) die('Extra Meta box "name" required');
			$bloc_classes = (isset($properties['bloc_classes'])) ? $properties['bloc_classes'] : null;

			$array_type = explode('_', $properties['type']);
			$class = '';
			foreach ($array_type as $type) {
				$class .= ucfirst($type);
			}

			$field = new $class($this, $name);
			$field->extract_properties($properties);
			$field->the_admin($bloc_classes);
		}
	}

	/**
	 * MAP
	 */
	public function the_admin_map ($name = '', $bloc_classes = '') {
		$field = new Map($this, $name);
		$field->the_admin($bloc_classes);
	}

	public function get_data_map($name = '') {
		$field = new Map($this, $name);

		return $field->get_data();
	}

	/**
	 * GALLERY
	 */
	public function the_admin_gallery ($name = '', $bloc_classes = '') {
		$field = new Gallery($this, $name);
		$field->the_admin($bloc_classes);
	}

	public function get_data_gallery($name = '') {
		$field = new Gallery($this, $name);

		return $field->get_data();
	}

	/**
	 * REDIRECTION
	 */
	public function the_admin_redirection ($name = '', $bloc_classes = '') {
		$field = new Redirection($this, $name);
		$field->the_admin($bloc_classes);
	}

	public function get_data_redirection($name = '') {
		$field = new Redirection($this, $name);

		return $field->get_data();
	}

	/**
	 * IMAGE
	 */
	public function the_admin_image ($name = '', $bloc_classes = '') {
		$field = new Image($this, $name);
		$field->the_admin($bloc_classes);
	}

	public function get_data_image($name = '') {
		$field = new Image($this, $name);

		return $field->get_data();
	}

	/**
	 * TABS
	 */
	public function the_admin_tabs ($template_tab, $add_label = null, $delete_label = null, $bloc_label = null, $name = '', $bloc_classes = '') {
		$field = new Tabs($this, $name);
		$field->setTemplateTab($template_tab);
		$field->setAddLabel($add_label);
		$field->setDeleteLabel($delete_label);
		$field->setBlocLabel($bloc_label);

		$field->the_admin($bloc_classes);
	}

	public function get_data_tabs($name = '') {
		$field = new Tabs($this, $name);

		return $field->get_data();
	}

	/**
	 * TEXT
	 */
	public function the_admin_text ($label = null, $name = '', $isBloc = false, $bloc_classes = '') {
		$field = new Text($this, $name);
		$field->setLabel($label);
		$field->setIsBloc($isBloc);

		$field->the_admin($bloc_classes);
	}

	public function get_data_text($name = '') {
		$field = new Text($this, $name);

		return $field->get_data();
	}
}