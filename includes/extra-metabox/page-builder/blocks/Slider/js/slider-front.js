jQuery(document).ready(function ($) {
	$(".extra-slider").each(function () {
		var $this = $(this),
			properties = $this.data('properties');

		properties = $.parseJSON(properties.replace(/'/g, '"'));

		$this.extraSlider({
			'auto': properties.extra_page_builder_slider_auto,
			'draggable': properties.extra_page_builder_slider_draggable,
			'navigate': properties.extra_page_builder_slider_navigate,
			'paginate': properties.extra_page_builder_slider_paginate,
			'keyboard': properties.extra_page_builder_slider_keyboard,
			'speed': properties.extra_page_builder_slider_speed,
			'type': properties.extra_page_builder_slider_type
		});
	})
});