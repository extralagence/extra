/****************
*
*
*
* EXTRA TOOLTIP
*
*
*				
****************/
(function($){

	$.fn.tooltip = function (options) {
		
		var opt = $.extend( {
			'content': '',
			'centerReference': null,
			'offsetX': 0,
			'classes': '',
			'time': 0
		}, options);
		
		var flag = false;
		
		this.each(function () {
		
			var $this = $(this);
			if(opt.centerReference == null || !opt.centerReference.length)  {
				opt.centerReference = $this;
			}
			var $position = opt.centerReference.position();
			var toolTip = $('<span class="extra-tooltip '+opt.classes+'">'+opt.content+'</span>');
			
			opt.centerReference.after(toolTip);
			
			TweenMax.set(toolTip, {css:{autoAlpha:0}});
			
			if(!Modernizr.touch) {
				$this.bind("mouseenter", show).bind("mouseleave", hide);
			} else {
				$this.bind("touchstart", touchStart).bind("touchend touchcancel", touchEnd);
			}
			
			// NO TOUCH
			function show(){
				$position = opt.centerReference.position();
				toolTip.css({
					"marginLeft":  opt.offsetX - toolTip.outerWidth()/2
				});
				TweenMax.to(toolTip, opt.time, {css:{autoAlpha:1}});
			}
			
			function hide(){
				TweenMax.to(toolTip, opt.time, {css:{autoAlpha:0}});
			}
			
			// NO TOUCH
			var running = false;
			var timer;
			function touchStart(){
				running = true;
    			timer = setTimeout(function(){
    				running = false;
					show();
    			}, 200);
				return false;
			}
			
			function touchEnd(){
				if(running) {
					clearTimeout(timer);
    				running = false;
					$this.click();
				} else {
					hide();
				}
				return false;
			}
			
		});
		
		return this;
		
		
	};
	
})(jQuery);