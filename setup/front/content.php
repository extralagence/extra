<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 12/02/2014
 * Time: 18:11
 */
?>

<h1 class="main-title"><?php the_second_title(); ?></h1>

<?php
/**********************
 *
 * BEFORE CONTENT
 *
 *********************/
get_template_part(apply_filters("extra-template-before-content", "setup/front/before-content"));

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
get_template_part(apply_filters("extra-template-after-content", "setup/front/after-content"));

/**********************
 *
 * TOTOP
 *
 *********************/
get_template_part(apply_filters("extra-template-totop", "setup/front/totop"));


extra_share();
?>