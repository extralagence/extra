jQuery(document).ready(function ($) {
	var $pageBuilder = $('.extra-page-builder');

	$pageBuilder.on('showform.pagebuilder.extra', function (event, $block_type, $block) {
		if ($block_type == 'image') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			var $input = $block.find('.extra-page-builder-block-form .extra-page-builder-image-input'),
				$inputSize = $block.find('.extra-page-builder-block-form .extra-page-builder-image-input-size'),
				$element = $block.find('.extra-page-builder-block-content .extra-page-builder-image'),
				file_frame,
				$body = $('body'),
				lastSelectionId = $input.val(),
				lastSelectionSize = $inputSize.val();

			$body.addClass('extra-page-builder-image-edition');

			if ( file_frame ) {
				file_frame.open();
				return;
			}

			$(document).on('click', '.compat-field-extra_image_size input[type="radio"]', function () {
				var $radio = $(this),
					name = $radio.attr('name'),
					pattern = /attachments\[(\d+)\]/g;
				lastSelectionSize = $radio.val();

				var matches = pattern.exec(name);
				if (matches.length > 0) {
					lastSelectionId = matches[1];
				}
			});

			file_frame = wp.media.frames.file_frame = wp.media({
				title: 'Sélectionner une image',
				button: {
					text: "Ajouter l'image"
				},
				multiple: false,
				filterable: 'uploaded',
				library: {
					type: 'image'
				}
			});



			if (lastSelectionId != '') {
				file_frame.on( 'open', function() {
					//Preselect image
					var selection = file_frame.state().get('selection');
					selection.add(wp.media.attachment(lastSelectionId));

					var name = 'attachments['+lastSelectionId+'][extra_image_size]';

					if (lastSelectionSize != '') {
						var max_waiting = 1000;
						function checkInputs () {
							var $container = $('.compat-field-extra_image_size');
							if ($container.length == 0) {
								max_waiting = max_waiting - 100;
								if (max_waiting > 0) {
									setTimeout(checkInputs, 100);
								}
							}else {
								var $input = $('.compat-field-extra_image_size input').filter(function () {
									return $(this).attr('name') == name && $(this).val() == lastSelectionSize;
								});
								console.log($input);
								$input.attr('checked', 'checked');
							}

						}
						setTimeout(checkInputs, 100);
					}
				});
			}

			file_frame.on( 'close', function() {
				if (file_frame.state().get('selection').first() !== undefined) {
					//SELECT
					attachment = file_frame.state().get('selection').first().toJSON();

					if(attachment && attachment.type == "image" && attachment.sizes) {
						var id = attachment.id,
							url = attachment.url,
							width =  attachment.sizes.full.width,
							height = attachment.sizes.full.height;

						$input.val(id);
						if (lastSelectionId == id) {
							$inputSize.val(lastSelectionSize);
						} else {
							$inputSize.val('auto');
						}
						$element.removeClass("empty").html("").css('background-image', 'url('+url+')');
						$element.data('original-height', height);
						$element.data('original-width', width);

						console.log($inputSize.val());
						switch ($inputSize.val()) {
							case 'auto':
								extraPageBuilder.disableResizable($block);
								$element.removeClass('size-custom');
								$element.addClass('size-auto');

								var block_width = $element.outerWidth();
								var newH = 0;
								if (width < block_width) {
									newH = width;
								} else {
									newH = (block_width * height) / width;
								}

								$element.css('height', newH);
								break;
							case 'custom':
								extraPageBuilder.enableResizable($block);
								$element.removeClass('size-auto');
								$element.addClass('size-custom');


								$element.css('height', '');
								// Start height = full height
								var $lastHeight = $block.find('input.extra-page-builder-block-height').val();
									console.log('$lastHeight : ');
									console.log($lastHeight);
								if ($lastHeight == '') {
									//$element.css('height', height);
									$block.css('height', height);
									$block.find('input.extra-page-builder-block-height').val(height);
								}

								break;
						}
					}
				}else {
					// CANCEL
					if ($input.val() == '') {
						extraPageBuilder.resetBlockChoice($block);
					}
				}

				$body.removeClass('extra-page-builder-image-edition');
			});

			file_frame.open();
		}
	});

	$pageBuilder.on('refreshPreview.pagebuilder.extra', function (event, $block_type, $block) {
		var $element = $block.find('.extra-page-builder-block-content .extra-page-builder-image');
		if ($element.hasClass('size-auto')) {
			console.log('must-refresh');

			var height = $element.data('original-height');
			var width = $element.data('original-width');

			var block_width = $element.outerWidth();
			var newH = 0;
			if (width < block_width) {
				newH = width;
			} else {
				newH = (block_width * height) / width;
			}
			$element.css('height', newH);
		}
	});
});