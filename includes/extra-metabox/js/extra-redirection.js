jQuery(function ($) {


	var $inputs = $('.extra-checkbox > input'),
		$conditionals = $('.extra-checkbox .extra-conditional'),
		$choiceTitle = $('#extra-search-choice-title'),
		$choiceUrl = $('#extra-search-choice-url'),
		$choice = $('.extra-search-choice');

	$inputs.on('click', function () {
		$conditionals.hide();
		$(this).parent().find('.extra-conditional').show();
	});
	$('.extra-checkbox > input:checked').trigger('click');

	var $input = $("#content-autocomplete"),
		$hiddenInput = $("#hidden-content-autocomplete");

	$input.autocomplete({
		source: function(req, callback){
			$.ajax({
				url: ajax.url,
				type: 'get',
				dataType: 'json',
				data: {
					'action': 'extra-redirection',
					'term': req.term
				},
				async: true,
				cache: true,
				success: function(data){
					var suggestions = [];
					//process response
					$.each(data, function(i, val){
						suggestions.push({"id": val.ID, "highlight_title": accent_folded_hilite(val.post_title, req.term), "title": val.post_title, "type": val.post_type, "url": val.url});
					});
					//pass array to callback
					callback(suggestions);
				}
			})
		},
		select: function (event, ui) {
			$hiddenInput.val(ui.item.id);
			//$input.val(ui.item.title);

			$choice.show();
			$choiceTitle.html(ui.item.title);
			$choiceUrl.html(ui.item.url);

			return false;
		},
		focus: function( event, ui ) {
			//$input.val(ui.item.title);

			return false;
		},
		minLength: 2
	});

	if($input.data( "ui-autocomplete" ) != null) {
		$input.data( "ui-autocomplete")._renderItem = function( ul, item ) {
			return $( "<li>" )
				.append( "<a class=\"extra-search-link\" href=\"#\"><span class=\"extra-search\">" + item.highlight_title + "</span> <span class=\"extra-search-type\"><strong>" + item.type + "</strong></span></a>" )
				.appendTo( ul );
		};
	}

	$input.focus(function () {
		$input.autocomplete("search");
	});

	$(document).on('click', '.extra-search-link', function (event) {
		event.preventDefault();
	})
});