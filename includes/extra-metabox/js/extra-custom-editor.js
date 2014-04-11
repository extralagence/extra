var firstCreation = false;
jQuery(document).ready(function($) {

	$.wpalchemy.on('wpa_copy', function(e, elmt) {
        extra_process_editors($(elmt));
	});

	function extra_process_editors(elmt) {
	    
	    if(elmt === undefined) {
	        elmt = $('.wpa_group:not(".tocopy") .extra-custom-editor-wrapper:not(".extra-editor-processed")'); 
	    }
        
		elmt.not('.extra-editor-processed').each(function() {
            
            if($(this).parents('.wpa_group.tocopy').length) {
                return;
            }

			// ELEMENTS
			var $wrapper = $(this), 
                $textarea = $wrapper.find("textarea.extra-custom-editor:first").addClass("mceEditor"),
                id = $textarea.attr("id"), 
                init_body_class = tinymce.settings.body_class, 
                init_id = tinymce.settings.id, 
                $handle = $("#" + id + "-resize-handle"), 
                mce = false, 
                editor, 
                $document = $(document);
			
			quicktags({
				id : id,
				buttons : 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'
			});

			// SET NEW VALUES
			tinymce.settings.body_class = init_body_class + " " + $textarea.attr("class");
			tinymce.settings.id = id;
			tinymce.settings.height = 300;

			tinymce.settings.wpautop = false;
			tinymce.execCommand('mceAddEditor', false, id);
			tinymce.settings.wpautop = true;

			// RESET DEFAULT VALUES
			tinymce.settings.body_class = init_body_class;
			tinymce.settings.id = init_id;

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

			// OK, PROCESSED
			$(document).trigger("extra-editor-processed", [$wrapper, id]);
			$wrapper.addClass('extra-editor-processed');

		});

	}
	
	extra_process_editors($('.extra-custom-editor-wrapper'));
}); 