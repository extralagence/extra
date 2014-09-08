jQuery(document).ready(function ($) {
	//var $editors = $('.extra-custom-editor-wrapper').extraPageBuilderCustomEditor();
	var $pageBuilder = $('.extra-page-builder'),
	uniqueID = 0;

	$pageBuilder.on('showform.pagebuilder.extra', function (event, $block_type, $block, $form) {
		if ($block_type == 'custom_editor') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			var $editor = $form.find('.extra-custom-editor-wrapper');

			if ($editor.data('extraPageBuilderCustomEditor') === undefined) {
				$editor.extraPageBuilderCustomEditor({height: 400});
			}

			$editor.data('extraPageBuilderCustomEditor').disable();
			extraAdminModal
				.options({footer: ['extra-admin-modal-save'], header: ['extra-admin-modal-title'], size: {height: 687}})
				.show('Modifier le texte',  $form);
			$editor.data('extraPageBuilderCustomEditor').enable();
		}
	});

	$pageBuilder.on('hideForm.pagebuilder.extra', function (event, block_type, $block, $form) {
		if (block_type == 'custom_editor') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			var $editor = $form.find('.extra-custom-editor-wrapper'),
				$textarea = $editor.find('textarea.extra-custom-editor'),
				tinymceContent = tinyMCE.activeEditor.getContent(),
				tinymceContentRaw = tinyMCE.activeEditor.getContent({format: 'raw'}),
				$content = $block.find('.extra-page-builder-block-content'),
				$iframe = $content.find('iframe');

			$editor.data('extraPageBuilderCustomEditor').disable();
			$block.find('.extra-page-builder-block-form').append($form);
			$editor.data('extraPageBuilderCustomEditor').enable();



			if ($iframe.length > 0) {
				$iframe[0].parentNode.removeChild($iframe[0]);
			}

			$block.find('.custom-editor-content').html(tinymceContentRaw);
			createIframe($block);
			$editor.data('extraPageBuilderCustomEditor').disable();

			$block.find('textarea.extra-custom-editor').val(tinymceContent);
		}
	});

	$pageBuilder.on('refreshPreview.pagebuilder.extra', function (event, block_type, $block, $form) {
		if (block_type == 'custom_editor') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			var $editor = $form.find('.extra-custom-editor-wrapper'),
				$content = $block.find('.extra-page-builder-block-content'),
				$iframe = $content.find('iframe');

			if ($iframe.length > 0) {
				$iframe[0].parentNode.removeChild($iframe[0]);
			}
			$block.find('.custom-editor-content').html($editor.find('textarea').val());
			createIframe($block);
		}
	});

	function createIframe($block) {
		var $content = $block.find('.custom-editor-content'),
			customCss = $block.find('.extra-page-builder-block-form .extra-custom-editor-wrapper textarea').data('custom-css'),
			cssFiles = tinymce.settings.content_css.split(','),
			cssLinks = '';

		if (customCss != undefined) {
			cssFiles = cssFiles.concat(customCss.split(','));
		}
		$.each(cssFiles, function(index, element) {
			cssLinks += '<link type="text/css" rel="stylesheet" href="' + element + '" />\n';
		});

		$('<iframe></iframe>')
		.attr({
			'src' : customEditorParams.iframeFileBase,
			'width': '100%',
			'height': '60',
			'scrolling': 'no'
		})
		.insertAfter($content)
		.on("load", function() {
			$(this).contents().find('body').find('.extra-page-builder-inner').html($content.html() + '\n' + cssLinks);
		})
		.iFrameResize({
			checkOrigin				: true,
			log                     : false,                  // Enable console logging
			enablePublicMethods     : false                  // Enable methods within iframe hosted page
		});
	}

	// SET IFRAME CONTENT AND STYLES
	var $editorWrappers = $pageBuilder.find('.extra-field-form > .extra-custom-editor-wrapper');
	$editorWrappers.each(function () {
		createIframe($(this).closest('.extra-page-builder-block'));
	});
});