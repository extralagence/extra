$(document).ready(function(){
	/*********************
	 *
	 * ACCORDEON
	 *
	 *********************/
	$(".accordeon-element").each(function(){
		var wrapper = $(this);
		var title = wrapper.find(" > .tab-title");
		var content = wrapper.find(" > .tab-content");
		var height = content.innerHeight();
		content.height(0);
		title.bind("click", function(){
			if(content.height() > 0) {
				wrapper.removeClass("open");
				TweenMax.to(content, 0.3, {css:{height:0}});
			} else {
				wrapper.addClass("open");
				TweenMax.to(content, 0.3, {css:{height:height}});
			}
		});
	});
});
