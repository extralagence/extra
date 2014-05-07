jQuery(document).ready(function ($) {
	$('.extra-page-builder').on('showform.pagebuilder.extra', function (event, $block_type, $block) {
		if ($block_type == 'slider') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			var $input = $block.find('.extra-page-builder-gallery-input'),
				$inputProperties = $block.find('.extra-page-builder-gallery-input-properties'),
				$element = $block.find('.extra-slider-preview .image'),
				gallery;

			gallery = wp.media.gallery;
			var defaultProperties = $.parseJSON($inputProperties.val().replace(/'/g, '"'));

			for (var defaultProperty in defaultProperties) {
				gallery.defaults[defaultProperty] = defaultProperties[defaultProperty];
			}
			gallery.defaults.is_extra_slider = true;

			gallery
				.edit('[gallery ids="' + $input.val() + '"]')
				.on('update', function (obj) {
					var properties = {};
					for (var galleryAttribute in obj.gallery.attributes) {
						if (galleryAttribute.substring(0, "extra_page_builder_slider_".length) == "extra_page_builder_slider_") {
							properties[galleryAttribute] = obj.gallery.attributes[galleryAttribute];
						}
					}
					var json = JSON.stringify(properties).replace(/"/g, "'");

					$inputProperties.val(json);

					if(obj.models.length > 0) {
						$element.removeClass("empty").html("").css('background-image', 'url('+obj.models[0].attributes.url+')');
					} else {
						$element.addClass('empty').html('').css('background-image', '');
					}

					var numberlist = [];
					$.each(obj.models, function (key, attachment) {
						numberlist.push(attachment.id);
					});
					$input.val(numberlist);
					return false;
				});
		}
	});
});