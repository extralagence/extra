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

	$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );
	$(".extra-datepicker").datepicker({
		altField: ".extra-datepicker-en",
		altFieldTimeOnly: false,
		altFormat: "yy-mm-dd"
	});
});
