/* global confirm, redux, redux_change */

jQuery(document).ready(function($) {
	$('.redux-extra-datetime-wrapper').each(function() {
		var $wrapper = $(this),
			$date = $wrapper.find('input.redux-extra-datetime-date'),
			$timestamp = $wrapper.find('input.redux-extra-datetime-timestamp');

		$date.datetimepicker({
			onSelect: function (selectedDateTime, datetimepicker){
				update();
			}
		});

		function update() {
			if($date.datetimepicker('getDate')) {
				$timestamp.val($date.datetimepicker('getDate').getTime());
			}
		}

		update();

	});
});
