$(document).ready(function(){
	/*********************
	 *
	 * ACCORDEON
	 *
	 *********************/

	$(document).on('click', 'a.tab-title', function (event) {
		event.preventDefault();

		var $title = $(this),
			slug = $title.attr('href').substring(1),
			$wrapper = $(this).closest('.tabs-wrapper')
		;

		$wrapper.find('.active').removeClass('active');
		$title.addClass('active');
		$wrapper.find('#'+slug+'.tab-content').addClass('active');
	});
});
