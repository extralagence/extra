<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 20/03/2014
 * Time: 12:01
 */

abstract class AbstractGroup extends AbstractField {
	protected $subfields;

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->subfields = $properties['subfields'];
		if (empty($this->subfields)) throw new Exception('Extra Meta box subfields properties required for'.get_class($this));
	}
} 