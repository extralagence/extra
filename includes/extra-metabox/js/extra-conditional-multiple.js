/**
 * Created by vincent on 17/03/2014.
 */

jQuery(function ($) {
	'use strict';

	$('.extra-conditional-multiple-container').each(function () {
		var $container = $(this),
			$checkboxes = $container.find('.extra-conditional-multiple-input');

		$('.extra-conditional-multiple-input').on('change', function() {
			$checkboxes.each(function() {
				var $checkbox = $(this);
				if ($checkbox.is(':checked')) {
					$checkbox.closest('.extra-conditional-multiple-input-container').addClass('open');
					$checkbox.next('.extra-conditional-multiple-field').slideDown(300);
				} else {
					$checkbox.closest('.extra-conditional-multiple-input-container').removeClass('open');
					$checkbox.next('.extra-conditional-multiple-field').slideUp(300);
				}
			});
		}).change();
	});
});
