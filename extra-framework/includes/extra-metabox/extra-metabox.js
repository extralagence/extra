jQuery(document).ready(function($){
	
	var wpautop;
	
	if(typeof(tinyMCE) != 'undefined') {
		wpautop = tinyMCE.settings.wpautop;
	} else {
		wpautop = true;
	}
	
	var textareaID;
	var selectedEd;
	$(".wpa_loop").sortable({
		axis: "y",
		forcePlaceholderSize: true,
		handle: "h2",
		opacity: 0.5,
		placeholder: "wpalchemy-placeholder",
		start: function(event, ui) { // turn TinyMCE off while sorting (if not, it won't work when resorted)
			if(ui.item.has(".extra_custom_editor_wrapper").size() > 0) {
				textareaID = $(ui.item).find('.extra_custom_editor_wrapper textarea').attr('id');
				tinyMCE.settings.wpautop = false;
				tinyMCE.execCommand('mceRemoveControl', false, textareaID);
			}
		},
		stop: function(event, ui) { // re-initialize TinyMCE when sort is completed
			if(ui.item.has(".extra_custom_editor_wrapper").size() > 0) {
				tinyMCE.execCommand('mceAddControl', false, textareaID);
				tinyMCE.settings.wpautop = wpautop;
			}
		}
	});
	
});
