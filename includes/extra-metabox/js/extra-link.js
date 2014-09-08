jQuery(function ($) {

	$('.extra-link-container').each(function () {
		var $this = $(this),
			$manualRadio = $this.find('.extra-link-manual .extra-link-radio'),
			$contentRadio = $this.find('.extra-link-content .extra-link-radio'),

			$autocompleteInput  = $this.find('.extra-link-autocomplete'),
			$autocompleteInputHidden  = $this.find('.extra-link-autocomplete-hidden'),
			$extraLinkChoice = $this.find('.extra-link-choice'),

			$extraLinkTitleInput = $this.find('.extra-link-title'),
			$extraLinkUrlInput = $this.find('.extra-link-url')
		;

		$manualRadio.on('click', function () {
			$autocompleteInput.prop('disabled', true);
			$extraLinkUrlInput.prop('disabled', false);
			$extraLinkChoice.hide();

			$extraLinkUrlInput.focus();
		});
		$contentRadio.on('click', function () {
			$extraLinkUrlInput.prop('disabled', true);
			$autocompleteInput.prop('disabled', false);
			$extraLinkChoice.show();

			$autocompleteInput.focus();
		});

		$autocompleteInput.autocomplete({
			source: function(req, callback){
				$.ajax({
					url: ajax.url,
					type: 'get',
					dataType: 'json',
					data: {
						'action': 'extra-link',
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
				$extraLinkChoice.show();
				$extraLinkChoice.html(ui.item.url);
				$extraLinkTitleInput.val(ui.item.title);
				$autocompleteInputHidden.val(ui.item.id);
				$autocompleteInput.val(ui.item.title);

				return false;
			},
			focus: function( event, ui ) {

				return false;
			},
			minLength: 2
		});

		if($autocompleteInput.data( "ui-autocomplete" ) != null) {
			$autocompleteInput.data( "ui-autocomplete")._renderItem = function( ul, item ) {
				return $( "<li>" )
					.append( "<a class=\"extra-link-search-link\" href=\"#\"><span class=\"extra-link-search\">" + item.highlight_title + "</span> <span class=\"extra-link-search-type\"><strong>" + item.type + "</strong></span></a>" )
					.appendTo( ul );
			};
		}

		$autocompleteInput.focus(function () {
			$autocompleteInput.autocomplete("search");
		});

		$(document).on('click', '.extra-link-search-link', function (event) {
			event.preventDefault();
		});
	});
});