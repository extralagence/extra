function ExtraScrollAnimator (options) {
	var self = this;

	self.updatePosition = function (fast) {
		var time = (fast === undefined || !fast) ? 0.3 : 0,
			scrollTop = $window.scrollTop();
		self.options.target.each(function() {
			var $this = $(this),
				coords = $this.data('coords'),
				tween = $this.data('tween'),
				percent = Math.max(0, Math.min(1, (scrollTop - coords.min) / (coords.max - coords.min)));

			if (self.options.autoAlpha) {
				if (percent == 0) {
					TweenMax.set($this, {autoAlpha: 0});
				} else {
					TweenMax.set($this, {autoAlpha: 1});
				}
			}
			TweenMax.to(tween, time, {progress: percent, ease:Linear.easeNone, lazy: false});
		});
	};

	/*********************************** FIRST INIT ***********************************/
	self.init = function (_options) {

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
			min : "100%",
			max : "66%",
			autoAlpha : false
		}, _options);

		self.options.target.each(function () {
			var $this = $(this),
				coords,
				twOptions = {};

			if(!$this.css('position') || $this.css('position') == 'static') {
				$this.css('position', 'relative');
			}

			if ($this.data('coords') === undefined) {
				coords = $this.data('coords', self.updateCoords($this));
			}

			if ($this.data('tween') !== undefined) {
				// vars
				var previousTween = $this.data('tween');

				// set the tween parameters
				$.each(self.options.attributes, function(key, value) {
					if(value.isAttr && value.isAttr === true) {
						if (!twOptions['attr']) {
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
					if (!twOptions['attr']) {
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

		$window.on('scroll', function(event) {
			self.updatePosition();
		});
		self.updatePosition(true);
	};

	/*********************************** UPDATE COORDS ***********************************/
	self.updateCoords = function($element) {
		var coords = {},
			min = self.options.min,
			max = self.options.max;

		if(typeof min === 'string' && min.slice(-1) === '%') {
			min = wHeight * (parseFloat(min)/100);
		}
		if(typeof max === 'string' && max.slice(-1) === '%') {
			max = wHeight * (parseFloat(max)/100);
		}

		$element.data('extraSA-top', $element.offset().top);
		coords.top = $element.data('extraSA-top');
		coords.min = coords.top - min;
		coords.max = coords.top - max;
		return coords;
	};

	/*********************************** RESIZE ***********************************/
	$window.on('extra.resize complete.extra.responsiveImage', function() {
		self.options.target.each(function() {
			// kill previous tween
			$(this).data('tween').progress(1).kill();
			$(this).removeData('coords').removeData('tween');
		});
		self.init(self.options);
	});

	/*********************************** LAUCNH INIT ***********************************/
	self.init(options);
}