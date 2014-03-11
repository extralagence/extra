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

class ExtraMetaBox extends WPAlchemy_MetaBox {

	function __construct ($arr) {
		parent::WPAlchemy_MetaBox($arr);
		$this->add_action('init', array($this, 'extra_init'));
	}

	function extra_init() {
		wp_enqueue_style('extra-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/extra-metabox.less');
		Map::init();
		Gallery::init();
		Redirection::init();
		Image::init();
	}

	public function the_admin_map ($name = '', $bloc_classes = '') {
		$field = new Map($this, $name);
		$field->the_admin($bloc_classes);
	}

	public function get_data_map($name = '') {
		$field = new Map($this, $name);

		return $field->get_data();
	}

	public function the_admin_gallery ($name = '', $bloc_classes = '') {
		$field = new Gallery($this, $name);
		$field->the_admin($bloc_classes);
	}

	public function get_data_gallery($name = '') {
		$field = new Gallery($this, $name);

		return $field->get_data();
	}

	public function the_admin_redirection ($name = '', $bloc_classes = '') {
		$field = new Redirection($this, $name);
		$field->the_admin($bloc_classes);
	}

	public function get_data_redirection($name = '') {
		$field = new Redirection($this, $name);

		return $field->get_data();
	}

	public function the_admin_image ($name = '', $bloc_classes = '') {
		$field = new Image($this, $name);
		$field->the_admin($bloc_classes);
	}

	public function get_data_image($name = '') {
		$field = new Image($this, $name);

		return $field->get_data();
	}
}