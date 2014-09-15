/**
 * Created by vincent on 17/03/2014.
 */

jQuery(function ($) {
	'use strict';

	$('.extra-text-container .extra-text-input').on('keypress', function () {
		var regex = $(this).data('regex');// /^\d+$/;
		if (regex != null && regex != 'undefined' && regex != '') {
			regex = new RegExp(regex);
			if (!regex.test(String.fromCharCode(event.charCode))) {
				event.preventDefault();
			}
		}
	});
});
