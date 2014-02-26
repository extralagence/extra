<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 12/02/2014
 * Time: 18:11
 */
?>

<h1 class="content-title"><?php the_second_title(); ?></h1>

<?php
/**********************
 *
 * BEFORE CONTENT
 *
 *********************/
get_extra_module_front_view_part("content/before-content");


/**********************
 *
 * CONTENT
 *
 *********************/
the_content();

/**********************
 *
 * AFTER CONTENT
 *
 *********************/
get_extra_module_front_view_part("content/after-content");

/**********************
 *
 * TOTOP
 *
 *********************/
get_extra_module_front_view_part("footer/totop");

extra_share();
?>