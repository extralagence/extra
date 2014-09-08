/**
 * Created by vincent on 17/03/2014.
 */

function addSpacesToInteger(i) {
	return i;

	//var s = i + '';
	//s = s.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');

	//return s;
}

jQuery(function ($) {
	'use strict';

	var max = parseInt($('.extra-range-container').data('max')),
		intRegex = /^\d+$/;

	$('.extra-range-container .extra-range').slider({
		min: 0,
		max: max,
		range: true,
		slide: function (event, ui) {
			var $slider = $(this),
				$inputMin = $slider.parents('.extra-range-container').find('.extra-range-input-min'),
				$inputMax = $slider.parents('.extra-range-container').find('.extra-range-input-max');
			$inputMin.val(addSpacesToInteger(ui.values[0]));
			$inputMax.val(addSpacesToInteger(ui.values[1]));

			$slider.trigger('extra-range-change', [ui.values[0], ui.values[1]]);
		}
	});

	function updateRange ($inputMin, $inputMax, $slider) {
		var values = new Array(0, max);

		var currentMin = parseInt($inputMin.val().replace(/\s+/g, ''));
		if (currentMin >= 0 && currentMin <= max) {
			values[0] = currentMin;
		}

		var currentMax = parseInt($inputMax.val().replace(/\s+/g, ''));
		if (currentMax >= 0 && currentMax <= max) {
			values[1] = currentMax;
		}

		$slider.slider('values', values);

		$slider.trigger('extra-range-change', [values[0], values[1]]);
	}

	$('.extra-range-container .extra-range-input').on('keypress', function () {
		if (!intRegex.test(String.fromCharCode(event.charCode))) {
			event.preventDefault();
		}
	});
	$('.extra-range-container .extra-range-input').on('keyup', function () {
		var $container = $(this).parents('.extra-range-container'),
			$inputMin = $container.find('.extra-range-input-min'),
			$inputMax = $container.find('.extra-range-input-max'),
			$slider = $container.find('.extra-range');

		updateRange($inputMin, $inputMax, $slider);
	});
	$('.extra-range-container').each(function () {
		var $container = $(this),
			$inputMin = $container.find('.extra-range-input-min'),
			$inputMax = $container.find('.extra-range-input-max'),
			$slider = $container.find('.extra-range');
		updateRange($inputMin, $inputMax, $slider);
	});
});
