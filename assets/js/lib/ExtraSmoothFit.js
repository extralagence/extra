function ExtraSmoothFit (options) {
	var self = this;

	/*********************************** FIRST INIT ***********************************/
	self.init = function (_options) {
		self.options = $.extend({
			target : null,
			childrenSelector: ' > *',
			readyClass: 'fitted'
		}, _options);

		if (self.options.target) {
			self.options.target.each(function () {
				var $this = $(this),
					$children = $this.find(self.options.childrenSelector),
					parentWidth = 0,
					childrenWidth = 0;

				TweenLite.set($children, {paddingLeft: 0, paddingRight: 0});
				parentWidth = $this.outerWidth();

				$children.each(function () {
					childrenWidth += $(this).outerWidth();
				});

				if (parentWidth > childrenWidth) {
					var padding = Math.floor(((parentWidth - childrenWidth) / $children.length) / 2);
					TweenLite.set($children, {paddingLeft: padding, paddingRight: padding});
				}
				$this.addClass(self.options.readyClass);
			});
		}
	};

	/*********************************** RESIZE ***********************************/
	$window.on('extra.resize complete.extra.responsiveImage', function() {
		self.init(self.options);
	});

	/*********************************** LAUCNH INIT ***********************************/
	self.init(options);
}