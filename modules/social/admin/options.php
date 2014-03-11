<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 04/03/2014
 * Time: 17:06
 */

/**********************
 *
 *
 *
 * GLOBAL OPTIONS
 *
 *
 *
 *********************/

function extra_social_add_global_options_section($sections) {
    $sections[] = array(
        'icon' => 'el-icon-torso',
        'title' => __("Offres d'emploi", "extra-admin"),
        'desc' => null,
        'fields' => array(
            array(
                'id' => 'jobs_page',
                'type' => 'select',
                'data' => 'page',
                'title' => __('Page "Offre d\'emploi"', 'extra-admin'),
            ),
        )
    );

    return $sections;
}
add_filter('extra_add_global_options_section', 'extra_social_add_global_options_section');