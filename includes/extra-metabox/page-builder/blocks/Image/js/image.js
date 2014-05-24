jQuery(document).ready(function ($) {
	$('.extra-page-builder').on('showform.pagebuilder.extra', function (event, $block_type, $block) {
		if ($block_type == 'image') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			var $input = $block.find('.extra-page-builder-block-form .extra-page-builder-image-input'),
				$element = $block.find('.extra-page-builder-block-content .extra-page-builder-image'),
				file_frame;

			if ( file_frame ) {
				file_frame.open();
				return;
			}

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
						//$element.removeClass("empty").html("").append('<img src="'+url+'" width="'+width+'" height="'+height+'"/>');
						$element.removeClass("empty").html("").css('background-image', 'url('+url+')');
					}
				}else {
					// CANCEL
					if ($input.val() == '') {
						console.log('Doit virer');
						extraPageBuilder.resetBlockChoice($block);
					}
				}
			});

			file_frame.open();
		}
	});
});