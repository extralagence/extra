<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 12/02/2014
 * Time: 17:45
 */
?>

<aside>
	<?php
	/**********************
	 *
	 * MENU PAGE
	 *
	 *********************/
	get_extra_module_front_view_part("sidebar/menu-page");
	/**********************
	 *
	 * PROFILES
	 *
	 *********************/
	get_extra_module_front_view_part("profiles", "profiles");
	/**********************
	 *
	 * CONTACT
	 *
	 *********************/
	get_extra_module_front_view_part("contact", "contact");
	?>
</aside>