function ExtraScrollAnimator (options) {
	var self = this;

	self.updatePosition = function () {
		var scrollTop = $window.scrollTop();
		self.options.target.each(function() {
			var coords = $(this).data('coords'),
				tween = $(this).data('tween'),
				percent = Math.max(0, Math.min(1, (scrollTop - coords.min) / (coords.max - coords.min)));
			if(tween) {
				TweenMax.to(tween, 0.3, {progress: percent, ease : Linear.easeNone, lazy: false});
			}
		});
	};

	/*********************************** FIRST INIT ***********************************/
	self.init = function (options) {

		self.options = $.extend({
			target : $(".bottom-fade"),
			attributes : {
				alpha : {
					min : 0,
					max : 1,
					isAttr : false
				},
				top : {
					min : "+=100px",
					max : "0px",
					isAttr : false
				}
			},
			ease : Linear.easeNone,
			fromTop : wHeight/6,
			min : wHeight,
			max : wHeight * ( 2 / 3 )
		}, options);

		var fromTop = parseInt(self.options.fromTop);
		options.target.each(function () {
			var $this = $(this),
				coords = $this.data('coords'),
				twOptions = {};

			if(!$this.css('position') || $this.css('position') == 'static') {
				$this.css('position', 'relative');
			}

			if (coords === undefined) {
				coords = {top:  $this.offset().top};
			}
			coords.min = coords.top - self.options.min;
			coords.max = coords.top - self.options.max;
			$this.data('coords', coords);

			if ($this.data('tween') !== undefined) {
				// vars
				var previousTween = $this.data('tween');

				// set the tween parameters
				$.each(self.options.attributes, function(key, value) {
					if(value.isAttr && value.isAttr === true) {
						if (! twOptions['attr']) {
							twOptions['attr'] = {};
						}
						twOptions['attr'][key] = value.max;
					} else {
						twOptions[key] = value.max;
					}
				});

				// kill previous tween
				previousTween.progress(0);
				previousTween.kill();

				// reset element
				TweenMax.set($this, twOptions);
			}

			// set the tween parameters
			$.each(self.options.attributes, function(key, value) {
				if(value.isAttr && value.isAttr === true) {
					if (! twOptions['attr']) {
						twOptions['attr'] = {};
					}
					twOptions['attr'][key] = value.min;
				} else {
					twOptions[key] = value.min;
				}
			});
			twOptions.ease = self.options.ease;
			twOptions.paused = true;
			$this.data('tween', TweenMax.from($this, 20, twOptions));
		});

		$window.scroll(self.updatePosition);
		self.updatePosition();
	};

	/*********************************** LAUCNH INIT ***********************************/
	self.init(options);
}