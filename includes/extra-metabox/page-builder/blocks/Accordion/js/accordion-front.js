$(document).ready(function(){
	/*********************
	 *
	 * ACCORDEON
	 *
	 *********************/
	$(".accordeon-element").each(function(){
		var wrapper = $(this),
			title = wrapper.find(" > .tab-title"),
			content = wrapper.find(" > .tab-content").height(0),
			inner = content.find(" > .inner"),
			height = inner.outerHeight();
		content.height(0);
		title.bind("click", function(){
			if(content.height() > 0) {
				wrapper.removeClass("open");
				TweenMax.to(content, 0.3, {css:{height:0}});
			} else {
				wrapper.addClass("open");
				height = inner.outerHeight();
				TweenMax.to(content, 0.5, {css:{height:height}});
			}
		});
	});
});
