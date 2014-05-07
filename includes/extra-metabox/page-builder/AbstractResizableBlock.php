<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 06/05/2014
 * Time: 14:22
 */

namespace ExtraPageBuilder;

abstract class AbstractResizableBlock extends AbstractBlock {

	function __construct(\ExtraPageBuilder $mb, $type) {
		parent::__construct($mb, $type);
		$this->resizable = true;
	}
}