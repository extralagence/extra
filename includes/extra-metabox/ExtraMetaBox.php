<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 09:23
 */

require_once 'MetaBox.php';
require_once 'AbstractField.php';
require_once 'AbstractGroup.php';

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
			$arr['template'] = EXTRA_INCLUDES_PATH.'/extra-metabox/default-template.php';
		}
		parent::WPAlchemy_MetaBox($arr);
		$this->add_action('init', array($this, 'extra_init'));

		foreach ($this->types as $type) {
			$add_admin_columns_filter = 'manage_'.$type.'_posts_columns';
			add_filter($add_admin_columns_filter, array($this, 'add_admin_columns'));

			$display_admin_columns_filter = 'manage_'.$type.'_posts_custom_column';
			add_filter($display_admin_columns_filter, array($this, 'display_admin_columns'), 10, 2);
		}
	}

	private function init_field($properties) {
		$class = $this->construct_class_name($properties);
		$class::init();

		$subfields = $this->get_subfields($properties);
		foreach ($subfields as $child) {
			$this->init_field($child);
		}
	}

	private function get_subfields($properties) {
		$subfields = array();
		if (isset($properties['subfields']) && !empty($properties['subfields'])) {
			$subfields = array_merge($subfields, $properties['subfields']);
		}
		if (isset($properties['subfields_false']) && !empty($properties['subfields_false'])) {
			$subfields = array_merge($subfields, $properties['subfields_false']);
		}
        if (isset($properties['subfields_true']) && !empty($properties['subfields_true'])) {
            $subfields = array_merge($subfields, $properties['subfields_true']);
        }
        if (isset($properties['multiple_subfields']) && !empty($properties['multiple_subfields'])) {
            foreach($properties['multiple_subfields'] as $subfield) {
                $subfields = array_merge($subfields, $subfield);
            }
        }

		return $subfields;
	}

	public function extra_init() {
		if (isset($this->fields) && !empty($this->fields)) {
			foreach ($this->fields as $properties) {
				$this->init_field($properties);
			}
		}
	}

	private function add_admin_column($fields, $columns) {
		foreach ($fields as $properties) {
			if (isset($properties['show_in_admin_column']) && $properties['show_in_admin_column'] == true && !empty($properties['name'])) {
				$column_label = $properties['admin_column_label'];
				if (empty($column_label)) {
					$column_label = $properties['label'];
					if (empty($column_label)) {
						$column_label = $properties['name'];
					}
				}
				$columns[$properties['name']] = $column_label;
			}

			$subfields = $this->get_subfields($properties);
			$columns = array_merge($columns, $this->add_admin_column($subfields, $columns));
		}

		return $columns;
	}

	public function add_admin_columns($columns) {
		$columns = array_merge($columns, $this->add_admin_column($this->fields, $columns));

		return $columns;
	}

	/**
	 * @param $fields
	 * @param $field_name
	 * @param $post_id
	 *
	 * @return $field AbstractField
	 */
	private function get_field_from_properties($fields, $field_name, $post_id) {
		$field = null;
		$i = 0;
		while ($i < count($fields) && $field == null) {
			$properties = $fields[$i];
			if (isset($properties['name']) && $properties['name'] === $field_name) {
				if ($properties['show_in_admin_column'] && !empty($properties['name'])) {
					$field = $this->construct_field_from_properties($properties);

					break;
				}
			} else {
				$subfields = $this->get_subfields($properties);
				$field = $this->get_field_from_properties($subfields, $field_name, $post_id);
			}
			$i++;
		}

		return $field;
	}

	public function get_meta($meta_name, $metas) {
		$meta = null;
		if (is_array($metas) && !empty($metas)) {
			foreach ($metas as $current_name => $current_value) {
				if ($current_name == $meta_name) {
					$meta = $current_value;
				}
				if ($meta == null && is_array($current_value) ) {
					$meta = $this->get_meta($meta_name, $current_value);
				}
				if ($meta != null) {
					break;
				}
			}
		}

		return $meta;
	}

	public function display_admin_columns($column, $post_id) {
		$field = $this->get_field_from_properties($this->fields, $column, $post_id);
		if ($field != null) {
			$this->the_meta($post_id);
			$field->the_admin_column_value();
		}
	}

	private function construct_class_name($properties) {
		if (!isset($properties['type'])) {
			var_dump($properties);
			throw new Exception ('Extra Meta box "type" required');
		}
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
	 * @return AbstractField
	 * @throws Exception
	 */
	protected function construct_field_from_properties($properties) {
		$class = $this->construct_class_name($properties);
		/**
		 * @var $field AbstractField
		 */
		$field = new $class($this);
		$field->extract_properties($properties);
		if ($field->getName() == null) throw new Exception ('Extra Meta box "name" required for '.$class);

		return $field;
	}

	public function the_admin($current_level) {
		foreach($current_level as $properties) {
			$field = $this->construct_field_from_properties($properties);
			$field->the_admin();
		}
	}

	public function the_admin_from_field($current_level, $name_suffix) {
		$this->the_admin($current_level);
	}
}