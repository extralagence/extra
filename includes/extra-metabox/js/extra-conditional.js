/**
 * Created by vincent on 17/03/2014.
 */

jQuery(function ($) {
	'use strict';

	$(document).on('click', '.extra-conditional-input', function () {
		var $checkbox = $(this),
			$container = $checkbox.parent('.extra-conditional-container'),
			$containerFalse = $container.find('.extra-conditional-field-false'),
			$containerTrue = $container.find('.extra-conditional-field-true');

		if ($checkbox.is(':checked')) {
			$containerFalse.hide();
			$containerTrue.show();
		} else {
			$containerFalse.show();
			$containerTrue.hide();
		}
	});
});
