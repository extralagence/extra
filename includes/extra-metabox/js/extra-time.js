/**
 * Created by vincent on 17/03/2014.
 */

jQuery(function ($) {
	'use strict';

	$('#post').submit(function(event){ // the form submit function
		var hasError = false;

		$('#post .required').each(function(){
			var value = $(this).children('.extra-timepicker').val();

			if( value == '-1' || value == '' || value == ' ' || value == null ){ // checks if empty or has a predefined string
				//insert error handling here. eg $(this).addClass('error');

				$(this).children('.extra-error-message').show();
				$(this).children('.extra-timepicker').addClass('has-error');
				hasError = true;

				return false; //return false if empty and stop submit event
			}
		});

		if (hasError) {
			event.preventDefault();
			$('#publishing-action input[type="submit"]').removeClass('button-primary-disabled');
			$('#publishing-action .spinner').hide();
		} else {

		}
	});

	$.timepicker.setDefaults( $.timepicker.regional[ "fr" ] );
	var $extraTimePicker = $(".extra-timepicker");
	$extraTimePicker.timepicker({
		altField: ".extra-timepicker-en",
		altFieldTimeOnly: false,
		altFormat: $extraTimePicker.data('format')
	});
});

/* French translation for the jQuery Timepicker Addon */
/* Written by Thomas Lété */
(function($) {
	$.timepicker.regional['fr'] = {
		timeOnlyTitle: 'Choisir une heure',
		timeText: 'Heure',
		hourText: 'Heures',
		minuteText: 'Minutes',
		secondText: 'Secondes',
		millisecText: 'Millisecondes',
		microsecText: 'Microsecondes',
		timezoneText: 'Fuseau horaire',
		currentText: 'Maintenant',
		closeText: 'Terminé',
		timeFormat: 'HH:mm',
		amNames: ['AM', 'A'],
		pmNames: ['PM', 'P'],
		isRTL: false
	};
	$.timepicker.setDefaults($.timepicker.regional['fr']);
})(jQuery);

