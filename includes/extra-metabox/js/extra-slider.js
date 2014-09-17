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

	$('.extra-slider-container').each(function () {
		var $container = $(this);

		var max = parseInt($container.data('max')),
			min = parseInt($container.data('min')),
			intRegex = /^\d+$/;

		$container.find('.extra-slider').slider({
			min: min,
			max: max,
			slide: function( event, ui ) {
				var $slider = $(this),
					$input = $slider.parents('.extra-slider-container').find('.extra-slider-input');
				$input.val(addSpacesToInteger(ui.value));

				$slider.trigger('extra-slider-change', [ui.value]);
			}
		});

		function updateSlider ($input, $slider) {
			var value = parseInt($input.val().replace(/\s+/g, ''));
			if (value >= 0 && value <= max) {
				$slider.slider('value', value);
			} else {
				$slider.slider('value', 0);
			}

			$slider.trigger('extra-slider-change', [value]);
		}

		$container.find('.extra-slider-input').on('keypress', function () {
			if (!intRegex.test(String.fromCharCode(event.charCode))) {
				event.preventDefault();
			}
		});

		$container.find('.extra-slider-input').on('keyup', function () {
			var $input = $(this),
				$slider = $input.parents('.extra-slider-container').find('.extra-slider');
			updateSlider($input, $slider);
		});

		$container.each(function () {
			var $container = $(this),
				$input = $container.find('.extra-slider-input'),
				$slider = $container.find('.extra-slider');
			updateSlider($input, $slider);
		});
	});
});
