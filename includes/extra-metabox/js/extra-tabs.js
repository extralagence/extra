jQuery(document).ready(function($){	
		
	$('.extra-tabs .wpa_loop').each(function(){
		
		var $wrapper = $(this),
			$nav = $('<ul class="extra-tab-navigation"></ul>').prependTo($wrapper),
			idBase = $wrapper.attr("id"),
			first = true,
            wpautop = true,
			textareaID, selectedEd, wpautop;
	
	
		$.wpalchemy.bind('wpa_copy wpa_delete', function(e, elmt){
			updateMenu();
			if($nav.children().size()) {
				$nav.find(">li:last a").click();
			}
		});
		
		function updateMenu() {
			
			// IS IT FIRST INIT
			if(!first) {
				$wrapper.tabs("destroy");
				$nav.sortable("destroy");
			}
			
			// EMPTY THE LIST
			$nav.empty();
			
			
			// LOOP THROUGH THE ITEMS 
			$wrapper.find(".wpa_group:not('.tocopy')").each(function(i){
				
				var elmt = $(this);
				var title = elmt.find("h2").first();
				elmt.attr("id", idBase+i);
				
				if(!elmt.data("processed") || elmt.data("processed") != "processed") {					
					title.text(title.text() + " " + (i+1));
					elmt.data("processed", "processed");
				}
				
				var link = $("<a></a>", {
					"href": "#"+(idBase+""+i),
					"text": title.text()
				});
				
				$nav.append(link);
				link.wrap("<li />");
				
			});
			
			
			// SET WRAPPER 
			$wrapper.tabs();
	
			// MAKE IT SORTABLE
			$nav.sortable({
				containment: "parent",
				forcePlaceholderSize: true,
				opacity: 1,
				placeholder: "extra-tabs-placeholder",
				start: function(event, ui) { // turn tinymce off while sorting (if not, it won't work when resorted)
					var item = ui.item;
					var target = $("#"+item.attr("aria-controls"));
					if(target.has(".extra-custom-editor-wrapper").size() > 0) {
						textareaID = target.find('.extra-custom-editor-wrapper textarea').attr('id');
						tinymce.settings.wpautop = false;
						tinymce.execCommand('mceRemoveEditor', false, textareaID);
					}
				},
				stop: function(event, ui) { // re-initialize tinymce when sort is completed
					var item = ui.item;
					var target = $("#"+item.attr("aria-controls"));
					target.insertAfter($wrapper.children().eq(item.index()));
					if(target.has(".extra-custom-editor-wrapper").size() > 0) {
						tinymce.execCommand('mceAddEditor', false, textareaID);
						tinymce.settings.wpautop = wpautop;
					}
					$wrapper.tabs( "refresh" );
				}
			}).disableSelection();
			
			// NO MORE FIRST
			if(first) {
				first = false;
			}
			
		}
		
		updateMenu();
	        
	});
	
});
