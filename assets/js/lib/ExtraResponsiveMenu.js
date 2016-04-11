/****************
 *
 *
 *
 * EXTRA RESPONSIVE MENU 1.0
 * Documentation and examples on http://menu.extralagence.com
 *
 *
 *
 ****************/
(function ($) {
	'use strict';
	/*global console, jQuery, $, window, TweenMax, Draggable, Quad */
	var $window = window.$window || $(window);

	$.fn.extraMenu = function (options) {

		var opt = $.extend({
			$site: $("#wrapper"),
			$button: $(".extra-menu-button").first(),
			resizeEvent: 'resize',
			everySizes: true,
			smallBreakpointCondition: null,
			breakPointWidth: 960,
			autoHide: true,
			moveButton: true,
			moveSite: true,
			hideTriggerEvent: 'extra:menu:hideTrigger',
			showTriggerEvent: 'extra:menu:showTrigger',
			prependTimeline: null,
			appendTimeline: null
		}, options);

		this.each(function (index, element) {

			/*********************************** SETUP VARS ***********************************/
			var $menu = $(this),
				menuOpen = false,
				$html = $('html'),
				smallBreakpoint = false,
				timeline,
				$toMove;

			/*********************************** FUNCTIONS ***********************************/
			// show the menu
			// @fast : false = hide with animation, true = hide instantaneously
			function showMenu(fast) {
				if ((smallBreakpoint && !opt.everySizes) || opt.everySizes) {
					menuOpen = true;
					$html.addClass('extra-menu-open');
					$html.removeClass('extra-menu-close');
					if (fast) {
						timeline.totalProgress(1);
						$window.trigger('extra:menu:ShowComplete');
					} else {
						timeline.play();
					}
				} else {
					hideMenu(true);
				}
			}

			// hide menu
			// @fast : false = hide with animation, true = hide instantaneously
			function hideMenu(fast) {
				menuOpen = false;
				$html.removeClass('extra-menu-open');
				$html.addClass('extra-menu-close');
				$window.trigger('extra:menu:HideStart');
				if (fast) {
					timeline.totalProgress(0);
					$window.trigger('extra:menu:HideComplete');
				} else {
					timeline.reverse();
				}
			}

			// update the menu
			function update() {
				if (!smallBreakpoint && !opt.everySizes) {
					$html.removeClass('responsive-menu').addClass('no-responsive-menu');
				} else {
					//$html.removeClass('no-responsive-menu').addClass('responsive-menu');
				}
			}

			// Update the menu animation timeline
			function updateTimeline() {

				// Delete current timelinemax
				if (timeline) {
					TweenMax.set([$menu, opt.$site], {
						clearProps: "all"
					});
					timeline.kill();
				}

				// Recreate a new timelinemax
				timeline = new TimelineMax({
					paused: true,
					onStart: function () {
						$window.trigger('extra:menu:ShowStart');
					},
					onComplete: function () {
						$window.trigger('extra:menu:ShowComplete');
					},
					onReverseComplete: function () {
						$window.trigger('extra:menu:HideComplete');
					}
				});

				if(!smallBreakpoint && !opt.everySizes) {
					return;
				}

				// Prepended timeline
				if (opt.prependTimeline) {
					timeline = opt.prependTimeline(timeline);
				}

				// setter
				TweenMax.set($menu, {
					x: -$menu.outerWidth() + 'px',
					ease: Strong.EaseIn
				});
				if ($toMove) {
					TweenMax.set($toMove, {
						x: 0,
						ease: Strong.EaseIn
					});
				}

				// TIMELINE
				timeline.to($menu, 0.4, {
					x: 0,
					ease: Quad.EaseOut
				});
				if ($toMove) {
					timeline.to($toMove, 0.5, {
						x: $menu.outerWidth() + 'px',
						ease: Quad.EaseOut,
						onComplete: function () {
							update();
						}
					}, '-=0.3');
				} else {
					timeline.addCallback(function () {
						update();
					});
				}

				// Appended timeline
				if (opt.appendTimeline) {
					timeline = opt.appendTimeline(timeline);
				}
			}

			// breakpoint condition tester
			function defaultSmallBreakpointTester() {
				return $window.width() < opt.breakPointWidth;
			}

			/*********************************** TRIGGER EVENTS ***********************************/
			$window.
				// @fast: false = hide with animation, true = hide instantaneously
				on('extra:menu:hide', function (event, fast) {
					hideMenu(fast);
				})
				// @fast: false = hide with animation, true = hide instantaneously
				.on('extra:menu:show', function (event, fast) {
					showMenu(fast);
				});

			$window.on(opt.resizeEvent, function () {
				smallBreakpoint = opt.smallBreakpointCondition();
				updateTimeline();
				menuOpen ? showMenu(true) : hideMenu(true);
			});

			opt.$button.on("click", function () {
				menuOpen ? hideMenu() : showMenu();
			});


			/*********************************** DEFAULT BREAK POINT CHECKER VALUE ***********************************/
			if (opt.smallBreakpointCondition === null) {
				opt.smallBreakpointCondition = defaultSmallBreakpointTester;
			}

			smallBreakpoint = opt.smallBreakpointCondition();


			/*********************************** INITIALIZE ***********************************/
			updateTimeline();
			opt.autoHide ? hideMenu(true) : showMenu(true);

		});

		return this;

	};
}(jQuery));