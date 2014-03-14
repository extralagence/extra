jQuery(document).ready(function($) {
	
	
	
	function extra_process_gallery(elmt){
		
		$('.wpa_group:not(".tocopy") .extra_custom_gallery:not(".extra_image_processed")').each(function(){
	
			var $element = $(this),
				$input = $element.find('.gallery-input'),
				$thumbs = $element.find('.thumbs');	
				
			$element.addClass("extra_image_processed");			
			
			$element.on("click", ".choose-button", function(event) {

    			event.preventDefault();
    			
    			wp.media.gallery
				.edit('[gallery ids="'+$input.val()+'"]')
				.on('update', function(obj) {
					
					var numberlist = [];
					$thumbs.html(''); 
					$.each(obj.models, function(key, attachment) {
						numberlist.push(attachment.id);
						appendImage(attachment);
					});
					$input.val(numberlist);
    				return false;
				}); 
				
			});
			
			$thumbs.find(".image").on("click", function(){
				deleteImage($(this).find("img"));
			});
			$thumbs.sortable({
				stop: updateList
			});
			
			function updateList(event, ui){
				var values = [];
				$thumbs.find(".image").each(function(){
					values.push($(this).find("img").data("id"));
				});
				$input.val(values.join(","));
			}
			
			function appendImage(attachment) {
				var size;
				if(attachment.attributes.sizes.thumbnail) {
					size = attachment.attributes.sizes.thumbnail;
				} else {
					size = attachment.attributes.sizes[0];
				}
				var image = $('<img data-id="'+attachment.id+'" src="'+size.url+'" width="150" />');
				image.appendTo($thumbs).wrap('<span class="image"></span>');
				image.parent().on("click", function(){
					deleteImage($(this).find("img"));
				});
			}
			
			function deleteImage(image){
				var index = image.parent().index();
				var value = $input.val().split(",");
				value.splice(index, 1);
				image.parent().remove();
				$input.val(value.join(","));
			};
			
			
			
			
		});
	}
	
	
	$.wpalchemy.bind('wpa_copy', function(e, elmt){
		extra_process_gallery($(elmt));	
	});
	
	extra_process_gallery();
	
	
});