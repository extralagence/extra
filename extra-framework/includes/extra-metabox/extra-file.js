/***************************
 *
 *
 * FILE MANAGER 
 *  
 *  
<div class="wpa_group">
<?php $mb->the_field('file'); ?>
	<div class="extra_custom_file">
		<input name="<?php $mb->the_name(); ?>" id="<?php $mb->the_name(); ?>" type="text" value="<?php $mb->the_value(); ?>" />
		<input class="choose-button button" type="button" value="<?php _e("Ouvrir le gestionnaire de fichiers", "extra-admin"); ?>" />
	</div>
</div>
**************************/
jQuery(document).ready(function($) {
	
	
	
	function extra_process_file(elmt){
		
		$('.wpa_group:not(".tocopy") .extra_custom_file:not(".extra_file_processed")').each(function(){
	
			var $element	= $(this),
				$input		= $element.find("input[type='text']"),
				file_frame;
				
			$element.addClass("extra_file_processed");
			
			$element.on("click", ".choose-button", function(event) {

    			event.preventDefault();
    			
				if ( file_frame ) {
					file_frame.open();
					return;
				}
				
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Sélectionner un fichier',
					button: {
						text: 'Ajouter le fichier',
					},
					multiple: false
				});

			    file_frame.on( 'select', function() {
			      attachment = file_frame.state().get('selection').first().toJSON();
			      $input.val(attachment.url);
			    });
			    file_frame.open();
				
			});
			
		});
	}
	
	extra_process_file();
	
});