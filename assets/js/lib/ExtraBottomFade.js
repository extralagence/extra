function ExtraBottomFade(options) {
	var self = this;
	self.options = $.extend({
			'$elements' : $(".bottom-fade"),
			'ease'      : Linear.easeIn
		}, options);


	self.$window = $(window);

	self.locked = false;
	self.position = function () {
		var scrollTop = self.$window.scrollTop();

		self.options.$elements.each(function() {
			var coords = $(this).data('coords'),
				tween = $(this).data('tween'),
				percent = Math.max(0, Math.min(1, (scrollTop - coords.min) / (coords.max - coords.min)));

			tween.progress(percent);
		});
	};

	self.init = function () {
		self.windowHeight = self.$window.outerHeight();

		var fromTop = parseInt(self.windowHeight/6);

		console.log(fromTop);

		options.$elements.each(function () {
			var $this = $(this);
			$this.data('coords', {
				min: $this.offset().top - self.windowHeight,
				max: $this.offset().top - 2 * (self.windowHeight / 3)
			});

			var tween = TweenMax.from($this, 1, {autoAlpha: 0, top: fromTop, ease: self.options.ease}).pause();
			$this.data('tween', tween);
		});
	};

	self.init();
	self.$window.scroll(self.position);
	self.position();
}