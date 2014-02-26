/****************
 *
 *
 *
 * EXTRA SLIDER 1.0
 *
 *
 *
 * Minimum html needed

 <div id="slider-id">
 <div class="slider">
 <div class="wrapper">
 <ul>
 <li>some html</li>
 <li>some html</li>
 <li>some html</li>
 <li>some html</li>
 <li>...</li>
 </ul>
 </div>
 <div class="navigation">
 <a href="#" class="prev">Précédent</a>
 <a href="#" class="next">Suivant</a>
 </div>
 <div class="pagination"></div>
 </div>
 </div>

 */


(function ($) {

	$.fn.extraSlider = function (options) {

		function repeat(str, n) {
			return new Array(n + 1).join(str);
		}

		var opt = $.extend({
			'draggable': false,
			'margin': 0,
			'navigate': false,
			'paginate': false,
			'paginateContent': '',
			'speed': 1,
			'type': 'slide',
			'onInit': null,
			'onMoveStart': null,
			'onMoveEnd': null
		}, options);

		this.each(function () {

			/*********************************** SETUP VARS ***********************************/
			var $this = $(this),
				$wrapper = $('> .wrapper', this),
				$slider = $wrapper.find('> ul'),
				$items = $slider.find('> li'),
				$navigation = $this.find('.navigation'),
				$pagination = $this.find('.pagination'),
				singleWidth = getDimension('width'),
				singleHeight = getDimension('height'),
				total = $items.length,
				visible = Math.ceil($wrapper.width() / singleWidth),
				currentItem = parseInt(1 + opt.margin),
				pages = Math.ceil($items.length / visible);

			/*********************************** INITIALIZE ***********************************/
			switch (opt.type) {
				default:
				case "slide":

					// CLONE BEFORE
					$items.first().before($items.slice(-(visible + opt.margin)).clone().addClass('cloned'));

					// CLONE AFTER
					$items.last().after($items.slice(0, visible + opt.margin).clone().addClass('cloned'));

					// GET ALL ITEMS (clones included)
					$items = $slider.find('> li');

					break;


				case "fade":
					$items.each(function (i) {
						if (i > 0) {
							TweenMax.set($(this), {css: {autoAlpha: 0, zIndex: 1}});
						} else {
							$(this).css("zIndex", 2);
						}
					});
					break;
			}

			/*********************************** GO TO PAGE ***********************************/
			function gotoPage(_page, time) {

				time = typeof time !== 'undefined' ? time : opt.speed;

				var dir = _page < currentItem ? -1 : 1,
					n = Math.abs(currentItem - _page),
					left = -(singleWidth * dir * n);

				if (!TweenMax.isTweening($slider) && !TweenMax.isTweening($items)) {

					currentItem = parseInt(_page);

					if (opt.onMoveStart && time > 0) {
						opt.onMoveStart(currentItem, pages);
					}

					if (opt.paginate) {
						$pagination.find("a").removeClass("active").eq(currentItem - 1).addClass("active");
					}

					switch (opt.type) {
						default:
						case "slide":
							TweenMax.to($slider, time, {css: {left: '+=' + left}, onComplete: endHandler, onCompleteParams:[time]});
							break;
						case "fade":
							if ($items.eq(currentItem - 1).length) {
								TweenMax.to($items.eq(currentItem - 1).css("zIndex", 1), time, {css: {autoAlpha: 0}});
							}
							if ($items.eq(currentItem - 1).length) {
								TweenMax.to($items.eq(currentItem - 1).css("zIndex", 2), time, {css: {autoAlpha: 1}, onComplete: endHandler, onCompleteParams:[time]});
							}
							break;
					}
				}
			}
			/*********************************** HELPER FUNCTIONS ***********************************/
			function gotoNext(time) {
				time = typeof time !== 'undefined' ? time : opt.speed;
				gotoPage(currentItem + 1, time);
			}
			function gotoPrev(time) {
				time = typeof time !== 'undefined' ? time : opt.speed;
				gotoPage(currentItem - 1, time);
			}

			/*********************************** UPDATE ***********************************/
			function update() {
				// RESET DIMENSIONS
				$slider.css('width', '100%');
				$items.css('width', '').css('height', '');
				$wrapper.css('width', '').css('height', '');

				// GET DIMENSIONS
				singleWidth = getDimension('width');
				singleHeight = getDimension('height');

				if (opt.type == 'slide') {
					// SET DIMENSIONS
					$slider.width(9999);
					TweenMax.set($slider, {css: {left: -(singleWidth * currentItem * visible)}});
				}
				// SET DIMENSIONS
				$items.css({
					'width': singleWidth + 'px',
					'height': singleHeight + 'px'
				});
				$wrapper.css({
					'width': (singleWidth * visible) + 'px',
					'height': singleHeight + 'px'
				});
			}

			/*********************************** FUNCTIONS ***********************************/
			// when the first animation is finished
			function endHandler(time) {
				if (opt.type === "slide") {
					adjustPosition();
				}
				if (opt.onMoveEnd && time > 0) {
					opt.onMoveEnd(currentItem, total);
				}
			}
			// adjust the slider position
			function adjustPosition() {
				// too far on the left (previous)
				if (currentItem === opt.margin) {
					currentItem = parseInt(total + opt.margin);
					$slider.css("left", -(singleWidth * (total + opt.margin + (visible - 1))));
				}
				// too far on the right (next)
				else if (currentItem > total + opt.margin) {
					currentItem = parseInt(1 + opt.margin);
					$slider.css("left", -(singleWidth * (visible + opt.margin)));
				}
			}
			// get the blocs dimensions
			function getDimension(type) {
				var max = 0;
				$items.each(function () {
					var item = $(this);
					var current = type == 'height' ? item.outerHeight(true) : item.outerWidth(true);
					if (current > max) {
						max = current;
					}
				});
				return max;
			}

			/*********************************** LISTENERS ***********************************/
			$(this).bind('update', function () {
				update();
			});
			// Bind next
			$(this).bind('next', function (event, time) {
				gotoNext(time);
			});
			// Bind prev
			$(this).bind('prev', function (event, time) {
				gotoPrev(time);
			});
			// bind goto page
			$(this).bind('goto', function (event, page, time) {
				time = typeof time !== 'undefined' ? time : opt.speed;
				gotoPage(page, time);
			});

			/*********************************** NAVIGATION ***********************************/
			if (opt.navigate && $navigation.length) {
				$('a.prev', $navigation).click(function () {
					gotoPrev();
					return false;
				});
				$('a.next', $navigation).click(function () {
					gotoNext();
					return false;
				});
			}

			/*********************************** PAGINATION ***********************************/
			if (opt.paginate && $pagination.length) {
				for (var i = 0; i < pages; i++) {
					$("<a>", {'href': '#'}).html(opt.paginateContent != '' ? opt.paginateContent : i + 1).appendTo($pagination);
				}
				$pagination.find("a").removeClass("active").eq(currentItem - 1).addClass("active");
				$('a', $pagination).each(function (i) {
					$(this).click(function () {
						if (i + 1 != currentItem) gotoPage(i + 1);
						return false;
					});
				});
			}

			/*********************************** DRAGGABLE ***********************************/
			if (opt.draggable && opt.type == 'slide') {

				$items.find('a').on('click', function(){
					return false;
				});

				var reference = 0,
					margin = 0,
					tween;

				$wrapper.swipe({
					tap: function (event, target) {
						if($(target).has('href')) {
							return false;
						}
					},
					swipeStatus: function (event, phase, direction, distance, duration) {

						if (phase == 'start') {
							if (tween && tween.isActive()) {
								margin = parseFloat($slider.css('left')) - reference;
								tween.kill();
							} else {
								reference = parseFloat($slider.css('left'));
								margin = 0;
							}
						}

						if (phase == 'move' && (direction == 'left' || direction == 'right')) {
							var dir = (direction === 'left') ? -1 : 1,
								left = reference + (dir * distance) + margin;
							TweenMax.set($slider, {css: {'left': left}});
						}

						if (phase == 'end' || phase == 'cancel') {
							if (direction == 'left') {
								if (tween) {
									tween.kill();
								}
								tween = TweenMax.to($slider, 0.5, {css: {left: '-=' + (singleWidth - distance + margin)}, onComplete: function () {
									TweenMax.set($slider, {css: {left: '+=' + (singleWidth)}});
									gotoNext(0);
									if (opt.onMoveStart) {
										opt.onMoveStart(currentItem, total);
									}
									if (opt.onMoveEnd) {
										opt.onMoveEnd(currentItem, total);
									}
								}});
							} else if (direction == 'right') {
								if (tween) {
									tween.kill();
								}
								tween = TweenMax.to($slider, 1, {css: {left: '+=' + (singleWidth - distance - margin)}, onComplete: function () {
									TweenMax.set($slider, {css: {left: '-=' + (singleWidth)}});
									gotoPrev(0);
									if (opt.onMoveStart) {
										opt.onMoveStart(currentItem, total);
									}
									if (opt.onMoveEnd) {
										opt.onMoveEnd(currentItem, total);
									}
								}});
							}
						}
					},
					threshold: 50,
					excludedElements: '.noSwipe',
					triggerOnTouchEnd: true,
					triggerOnTouchLeave: true
				});
			}

			/*********************************** ON INIT ***********************************/
			if (opt.onInit) {
				opt.onInit(total, $(this));
			}

			/*********************************** FIRST UPDATE ***********************************/
			update();
			$window.trigger('extra.responsiveImage', [$items.filter('.cloned')]);

		});

		return this;

	};
})(jQuery);