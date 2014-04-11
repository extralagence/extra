var firstCreation = false;
jQuery(document).ready(function($) {

	function extra_process_editors(elmt) {

		$('.extra-editor-wrapper:not(".extra-editor-processed")').each(function() {

			// ELEMENTS
			var $wrapper = $(this), 
                $textarea = $wrapper.find("textarea.wp-editor-area"), 
                id = $textarea.attr("id"), 
                $handle = $("#" + id + "-resize-handle"), 
                mce = false, 
                editor, 
                $document = $(document);

			// MAKE IT RESIZABLE
			$handle.on('mousedown.extra-editor-resize', function(event) {
				if ( typeof tinymce !== 'undefined') {
					editor = tinymce.get(id);
				}

				if (editor && !editor.isHidden()) {
					mce = true;
					offset = $('#' + id + '_ifr').height() - event.pageY;
				} else {
					mce = false;
					offset = $textarea.height() - event.pageY;
					$textarea.blur();
				}

				$document.on('mousemove.extra-editor-resize', dragging).on('mouseup.extra-editor-resize mouseleave.extra-editor-resize', endDrag);
				event.preventDefault();
			}).on('mouseup.extra-editor-resize', endDrag);

			// DRAGGING
			function dragging(event) {
				if (mce) {
					editor.theme.resizeTo(null, offset + event.pageY);
				} else {
					$textarea.height(Math.max(50, offset + event.pageY));
				}

				event.preventDefault();
			}

			function endDrag() {
				var height, toolbarHeight;

				if (mce) {
					editor.focus();
					toolbarHeight = $('#wp-' + id + '-editor-container .mce-toolbar-grp').height();

					if (toolbarHeight < 10 || toolbarHeight > 200) {
						toolbarHeight = 30;
					}

					height = parseInt($('#' + id + '_ifr').css('height'), 10) + toolbarHeight - 28;
				} else {
					$textarea.focus();
					height = parseInt($textarea.css('height'), 10);
				}

				$document.off('.extra-editor-resize');

				// sanity check
				if (height && height > 50 && height < 5000) {
					setUserSetting('ed_size', height);
				}
			}
			$wrapper.addClass('extra-editor-processed');

		});

	}

	if ( typeof (tinyMCE) != 'undefined') {
		tinyMCE.onAddEditor.add(function(mgr, ed) {
			if (!firstCreation) {
				firstCreation = true;
				setTimeout(extra_process_editors, 500);
			}
		});
	}
}); 