<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Text
 *
 * Define a custom metabox
 *
 * type = custom
 *
 * Options :
 * - name (optional)
 * - label (optional)
 * - icon (optional)
 */
class Custom extends AbstractField {

	protected $template;

	public static function init () {
		parent::init();
	}

	public function the_admin() {
		require $this->template;
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->template = isset($properties['template']) ? $properties['template'] : null;

		if ($this->name === null) {
			$this->name = 'custom';
		}
	}

	public function the_admin_column_value() {
		return null;
	}
} 