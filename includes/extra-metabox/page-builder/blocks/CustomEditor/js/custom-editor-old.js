//function extraPageBuilder_custom_editor() {
//	//Declare object
//}
//
//extraPageBuilder_custom_editor.prototype.showFormFor = function ($block, $) {
//	var $element = $block.find('.extra-custom-editor-wrapper');
//	console.log($block);
//	extra_process_editors($element, $);
//
//	extraAdminModal.show('Modifier le bloc', $block.find('.extra-field-form'));
//};

jQuery(document).ready(function ($) {
	$('.extra-page-builder').on('showform.pagebuilder.extra', function (event, $block_type, $block, $form) {
		if ($block_type == 'custom_editor') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			console.log('init for '+$block_type);
			extraAdminModal.show('Modifier le bloc',  $form, {footer: [], size: {height: 587}});
			extra_process_editors($form.find('.extra-custom-editor-wrapper'));
		}
	});

	var firstCreation = false;

	function extra_process_editors(elmt) {

		if(elmt === undefined) {
			elmt = $('.extra-custom-editor-wrapper:not(".extra-editor-processed")');
		}

		if(!elmt.hasClass('extra-custom-editor-wrapper')) {
			elmt = elmt.find('.extra-custom-editor-wrapper');
		}

		elmt.not('.extra-editor-processed').each(function() {

			if($(this).parents('.wpa_group.tocopy').length) {
				return;
			}

			// ELEMENTS
			var $wrapper = $(this),
				$textarea = $wrapper.find("textarea.extra-custom-editor:first").addClass("mceEditor"),
				id = $textarea.attr("id"),
				default_body_class = tinymce.settings.body_class,
				default_id = tinymce.settings.id,
				default_height = tinymce.settings.height,
				default_content_css = tinymce.settings.content_css,
				tempSettings = $.extend({}, tinymce.settings),
				$handle = $("#" + id + "-resize-handle"),
				mce = false,
				editor,
				$document = $(document);

			quicktags({
				id : id,
				buttons : 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'
			});

			// SET NEW VALUES
			tinymce.settings.body_class = tinymce.settings.body_class + " " + $textarea.data("extra-name");
			tinymce.settings.id = id;
			tinymce.settings.height = 300;
			if($textarea.data('custom-css')) {
				tinymce.settings.content_css = tinymce.settings.content_css + ',' + $textarea.data('custom-css');
			}
			tinymce.settings.wpautop = false;

			// SET NEW EDITOR
			tinymce.execCommand('mceAddEditor', false, id);

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

			// RESET DEFAULT VALUES
			tinymce.settings.body_class = default_body_class;
			tinymce.settings.id = default_id;
			tinymce.settings.height = default_height;
			tinymce.settings.content_css = default_content_css;
			tinymce.settings = $.extend({}, tempSettings);

		});
	}
});