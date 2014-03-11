jQuery(document).ready(function($) {

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
						var $img = $("<img />", {
							src: size.url,
							width: size.width,
							height: size.height
						});	
						$element.find(".image:first").removeClass("empty").html("").append($img);
					}
					
			    });
			    file_frame.open();
				
			});
			
			
		});
	}
	
	
	$.wpalchemy.bind('wpa_copy', function(e, elmt){
		extra_process_images($(elmt));	
	});
	
	extra_process_images();
});