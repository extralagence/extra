jQuery(document).ready(function($) {
	var selectedSize = null;
	if (typeof selected_size != 'undefined') {
		selectedSize = selected_size;
	}

	function extra_process_images(elmt){

		if(elmt === undefined) {
			elmt = $('.extra-custom-image:not(".extra-custom-image-processed")');
		}

		if(!elmt.hasClass('extra-custom-image')) {
			elmt = elmt.find('.extra-custom-image');
		}

		elmt.each(function(){

			if($(this).closest('.wpa_group.tocopy').length) {
				return;
			}

			var $element	 = $(this),
				$extraImageID = $element.find('.image-input'),
				extra_image_frame;

			$element.addClass("extra-custom-image-processed");

			$element.on("click", ".choose-button", function(event) {
				event.preventDefault();

				if ( extra_image_frame ) {
					extra_image_frame.open();
					return;
				}

				extra_image_frame = wp.media.frames.suce_ma_frame = wp.media({
					id: 'extra-image-frame',
					frame: 'post',
					title: 'Sélectionner une image',
					button: {
						text: "Ajouter l'image"
					},
					multiple: false,
					filterable: 'uploaded',
					state: 'insert'
				});

				extra_image_frame.on( 'insert', function() {
					console.log("insert");
					attachment = extra_image_frame.state().get('selection').first().toJSON();

					if(attachment && attachment.type == "image" && attachment.sizes) {
						$extraImageID.val(attachment.id);
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
				extra_image_frame.open();

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
			$input = $extraCustomImage.find('.image-input');
			sizes = (selectedSize != null ) ? 'width="'+selectedSize.width+'" height="'+selectedSize.height+'"' : '';

		$input.val('');
		$image.addClass('empty').html('<img src="" '+sizes+'/>');
	});
});