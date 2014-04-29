jQuery(document).ready(function($) {
	var selectedSize = null;
	if (typeof selected_size != 'undefined') {
		selectedSize = selected_size;
	}

	function extra_process_images(elmt){

		$('.wpa_group:not(".tocopy") .extra-custom-image:not(".extra-image-processed")').each(function(){

			var $element	 = $(this),
				$thumbnailId = $element.find('.image-input'),
				title        = $element.find("label:first").text(),
				update       = $element.find("label:first").text(),
				file_frame,
				imageId;

			imageId = $thumbnailId.val();

			$element.addClass("extra-image-processed");

			$element.on("click", ".choose-button", function(event) {
				console.log('image choose-button click');

				event.preventDefault();

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
					filterable: 'uploaded'
				});

				file_frame.on( 'select', function() {
					attachment = file_frame.state().get('selection').first().toJSON();

					if(attachment && attachment.type == "image" && attachment.sizes) {
						$thumbnailId.val(attachment.id);
						var size;
						if(attachment.sizes.thumbnail) {
							size = attachment.sizes.thumbnail;
						} else {
							size = attachment.sizes.full;
						}

						if (selectedSize != undefined && selectedSize != 'undefined' && selectedSize != null) {
							size.width = selectedSize.width;
							size.height = selectedSize.height;
						}

						var $img = $("<img />", {
							src: size.url,
							width: size.width,
							height: size.height
						});
						$element.find(".image:first").removeClass("empty").html("").append($img).append('<a class="close" href="#close"><span class="dashicons dashicons-no"></span></a>');
					}

				});
				file_frame.open();

			});


		});
	}

	if ($.wpalchemy !== undefined) {
		$.wpalchemy.bind('wpa_copy', function(e, elmt){
			extra_process_images($(elmt));
		});
	}

	extra_process_images();

	$(document).on('click', '.extra-custom-image-size-input', function () {
		var $this = $(this),
			$image = $this.parents('.extra-custom-image').find('.image > img');

		if (selectedSize == null) {
			selectedSize = {};
		}
		selectedSize.width = $this.data('width');
		selectedSize.height = $this.data('height');
		selectedSize.label = $this.data('label');

		$image.attr('width', selectedSize.width);
		$image.attr('height', selectedSize.height);
	});

	$(document).on('click', '.extra-custom-image > .image > .close', function () {
		var $this = $(this),
			$extraCustomImage = $this.parents('.extra-custom-image'),
			$image = $extraCustomImage.find('.image'),
			$input = $extraCustomImage.find('.image-input')
			sizes = (selectedSize != null ) ? 'width="'+selectedSize.width+'" height="'+selectedSize.height+'"' : '';

		$input.val('');
		$image.addClass('empty').html('<img src="" '+sizes+'/>');
	});
});