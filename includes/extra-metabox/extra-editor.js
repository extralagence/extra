var firstCreation = false;
jQuery(document).ready(function($){	
	
	$.wpalchemy.bind('wpa_copy', function(e, elmt){
		if(typeof(tinyMCE) != 'undefined'){
			extra_process_editors($(elmt));
		}	
	});
	
	
	function extra_process_editors(elmt){
		
		$('.wpa_group:not(".tocopy") .extra-custom-editor-wrapper:not(".extra-editor-processed")').each(function(){
			
			// ELEMENTS
			var $wrapper = $(this);
			var $textarea = $wrapper.find("textarea:first").addClass("mceEditor");
			var $id = $textarea.attr("id");		
			
			// ADD BODY CLASSES AND INIT TINYMCE
			// ---			
			// REMEMBER DEFAULTS
	        var init_body_class = tinyMCE.settings.body_class;
	        var init_id = tinyMCE.settings.id;
	        
	        // SET NEW VALUES
	        tinyMCE.settings.body_class = init_body_class + " " + $textarea.attr("class");
	        tinyMCE.settings.id = $id;
	        tinyMCE.settings.height = 300;
	        
	        tinyMCE.settings.wpautop = false;
	       
			tinyMCE.execCommand('mceAddControl', false, $id);
			
			tinyMCE.settings.wpautop = true; 
			
			// RESET DEFAULT VALUES
	        tinyMCE.settings.body_class = init_body_class;
	        tinyMCE.settings.id = init_id;
	        
	        // IF HTML SWITCH
			$wrapper.find(".switch-html").click(function(){
            	tinyMCE.execInstanceCommand($id, 'mceCodeEditor', false); 
            	return false;
			});
			
			// OK, PROCESSED
	    	$(document).trigger("extra-editor-processed", [$wrapper, $id]); 
	    	$wrapper.addClass('extra-editor-processed');  
	        
		});
		
	}
		
	if(typeof(tinyMCE) != 'undefined'){
		tinyMCE.onAddEditor.add(function(mgr,ed) {
			if(!firstCreation) {
				firstCreation = true;
				TweenMax.delayedCall(1, extra_process_editors);
			}
		});
	}
	
	TweenMax.delayedCall(1, function(){
		$("#wp-content-editor-tools .switch-tmce").click();
	});
	
	
});
