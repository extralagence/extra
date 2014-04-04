<?php

// JS MAP
wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBpFeTSnmCMi1Vb3LuLoAivc4D4CeA2YJs&sensor=false', array('jquery'), null, true);
wp_enqueue_script('extra-patrimony-map', get_template_directory_uri().'/assets/js/template-patrimony-map.js', array('jquery'), false, true);
wp_localize_script( 'extra-patrimony-map', 'test_localize', 'pouet' );