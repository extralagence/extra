/**
 * Created by vincent on 17/03/2014.
 */

jQuery(function ($) {
	'use strict';

	$('#post').submit(function(event){ // the form submit function
		var hasError = false;

		$('#post .required').each(function(){
			var value = $(this).children('.extra-datepicker').val();

			if( value == '-1' || value == '' || value == ' ' || value == null ){ // checks if empty or has a predefined string
				//insert error handling here. eg $(this).addClass('error');

				$(this).children('.extra-error-message').show();
				$(this).children('.extra-datepicker').addClass('has-error');
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

	$(".extra-datepicker").each(function () {
		var $extraDatePicker = $(this),
			timeFormat = $extraDatePicker.data('time-format'),
			$altField = $extraDatePicker.closest('.extra-date-container ').find('.extra-datepicker-en');

		$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );
		if (timeFormat != '') {
			$extraDatePicker.datetimepicker({
				altField: $altField,
				altFieldTimeOnly: false,
				altFormat: $extraDatePicker.data('format'),
				altTimeFormat: timeFormat
			});
		} else {
			$extraDatePicker.datepicker({
				altField: $altField,
				altFieldTimeOnly: false,
				altFormat: $extraDatePicker.data('format')
			});
		}
	});
});


/* French initialisation for the jQuery UI date picker plugin. */
/* Written by Keith Wood (kbwood{at}iinet.com.au),
 Stéphane Nahmani (sholby@sholby.net),
 Stéphane Raimbault <stephane.raimbault@gmail.com> */
jQuery(function($){
	$.datepicker.regional['fr'] = {
		closeText: 'Fermer',
		prevText: 'Précédent',
		nextText: 'Suivant',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
			'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
		monthNamesShort: ['Janv.','Févr.','Mars','Avril','Mai','Juin',
			'Juil.','Août','Sept.','Oct.','Nov.','Déc.'],
		dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
		dayNamesShort: ['Dim.','Lun.','Mar.','Mer.','Jeu.','Ven.','Sam.'],
		dayNamesMin: ['D','L','M','M','J','V','S'],
		weekHeader: 'Sem.',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['fr']);
});
