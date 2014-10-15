function ExtraBottomDraw (options) {
	var self = this;

	self.position = function () {
		var scrollTop = self.$window.scrollTop();
		self.options.$elements.each(function() {
			var coords = $(this).data('coords'),
				tween = $(this).data('tween'),
				percent = Math.max(0, Math.min(1, (scrollTop - coords.min) / (coords.max - coords.min)));
			tween.progress(percent);
		});
	};

	self.init = function (options) {
		self.$window = $(window);
		self.windowHeight = self.$window.outerHeight();

		self.options = $.extend({
			'$elements' : $(".bottom-draw"),
			'ease'      : Linear.easeIn,
			'initialStrokeDashOffset'   : 1000,
			'initialStrokeDashArray'   : 1000,
			'toStrokeDashOffset'      : 0,
			'min'       : self.windowHeight,
			'max'       : self.windowHeight * ( 2 / 3)
		}, options);

		var fromTop = parseInt(self.options.fromTop);
		options.$elements.each(function () {
			var $this = $(this),
				coords = $this.data('coords');

			if (coords === undefined) {
				coords = {top:  $this.offset().top};
			}
			coords.min = coords.top - self.options.min;
			coords.max = coords.top - self.options.max;
			$this.data('coords', coords);

			if ($this.data('tween') !== undefined) {
				var previousTween = $this.data('tween');
				previousTween.progress(0);
				previousTween.kill();
			}
			TweenMax.set($this, {attr: {'stroke-dashoffset': self.options.initialStrokeDashOffset, 'stroke-dasharray': self.options.initialStrokeDashArray}});
			var tween = TweenMax.to($this, 3, {attr: {'stroke-dashoffset': self.options.toStrokeDashOffset}}).pause();
			$this.data('tween', tween);
		});

		self.$window.scroll(self.position);
		self.position();
	};

	self.init(options);
}