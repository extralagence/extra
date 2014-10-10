function ExtraBottomFade (options) {
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
			'$elements' : $(".bottom-fade"),
			'ease'      : Linear.easeIn,
			'fromTop'   : self.windowHeight/6,
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
				TweenMax.set($this, {autoAlpha: 1, top: 0});
			}
			var tween = TweenMax.from($this, 1, {autoAlpha: 0, top: fromTop, ease: self.options.ease}).pause();
			$this.data('tween', tween);
		});

		self.$window.scroll(self.position);
		self.position();
	};

	self.init(options);
}