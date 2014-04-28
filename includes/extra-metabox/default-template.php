<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 14/03/2014
 * Time: 16:07
 */

if (empty($mb->fields)) throw new Exception('Extra Meta box "fields" required');
$mb->the_admin($mb->fields);

?>