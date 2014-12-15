/**************************
 *
 *
 * Responsive Navigation
 * @$menu : jquery object
 * @$site : jquery object
 * @$button : jquery object
 * @everySizes : boolean
 *
 *
 *************************/
function ExtraResponsiveMenu(options) {

	var opt = $.extend({
			$menu: $("#mobile-menu-container"),
			$site: $("#wrapper"),
			$button: $("#switch-mobile-menu"),
			everySizes: false,
			moveButton: true,
			prependTimeline: null,
			appendTimeline: null
		}, options),
		menuOpen = true,
		$html = $('html'),
		$wpadminbar = $("#wpadminbar"),
		timeline = new TimelineMax({
			paused: true,
			onReverseComplete: function () {
				$window.trigger('extra.hideResponsiveMenuComplete');
			}
		}),
		$toMove;
	/**************************
	 *
	 *
	 * INIT
	 *
	 *
	 *************************/
	if (!opt.$menu.length || !opt.$site.length || !opt.$button.length) {
		console.log("Missing element to initialize the responsive menu");
		return;
	}
	/**************************
	 *
	 *
	 * CLICK
	 *
	 *
	 *************************/
	opt.$button.click(function () {
		if (small || opt.everySizes) {
			if (!menuOpen) {
				showMenu();
			} else {
				hideMenu();
			}
		}
		return false;
	});
	/**************************
	 *
	 *
	 * ON RESIZE
	 *
	 *
	 *************************/
	$window.on('extra.resize', function () {
		if (menuOpen) {
			showMenu(true);
		} else {
			hideMenu(true);
		}
	});
	/**************************
	 *
	 *
	 * INIT
	 *
	 *
	 *************************/
	opt.$menu.css('visibility', 'visible');
	update();
	/**************************
	 *
	 *
	 * CREATE TIMELINE
	 *
	 *
	 *************************/
	// START OF TIMELINE
	if (opt.prependTimeline) {
		timeline = opt.prependTimeline(timeline);
	}

	// SETTER
	TweenMax.set(opt.$menu, {
		x: -opt.$menu.width() + 'px',
		ease: Strong.EaseIn,
		onComplete: function () {
			$window.trigger('extra.hideResponsiveMenuComplete');
		}
	});
	TweenMax.set($toMove, {
		x: 0,
		ease: Strong.EaseIn
	});

	// TIMELINE
	timeline.to(opt.$menu, 0.4, {
		x: 0,
		ease: Quad.EaseOut
	});
	timeline.to($toMove, 0.5, {
		x: opt.$menu.width() + 'px',
		ease: Quad.EaseOut,
		onComplete: function () {
			$window.trigger('extra.showResponsiveMenuComplete');
		}
	}, '-=0.3');

	// END OF TIMELINE
	if (opt.appendTimeline) {
		timeline = opt.appendTimeline(timeline);
	}
	/**************************
	 *
	 *
	 * SHOW
	 *
	 *
	 *************************/
	function showMenu(fast) {
		if (small || opt.everySizes) {
			opt.$menu.css('visibility', 'visible');
			menuOpen = true;
			$html.addClass('menu-open');
			$window.trigger('extra.showResponsiveMenu');
			if (fast) {
				timeline.totalProgress(1);
			} else {
				timeline.play();
			}
		} else {
			hideMenu(true);
		}
	}

	/**************************
	 *
	 *
	 * HIDE
	 *
	 *
	 *************************/
	function hideMenu(fast) {
		menuOpen = false;
		$html.removeClass('menu-open');
		if (!small && !opt.everySizes) {
			opt.$menu.removeAttr("style");
			opt.$site.removeAttr("style");
			opt.$menu.hide();
		}
		$window.trigger('extra.hideResponsiveMenu');
		if (fast) {
			timeline.totalProgress(0);
		} else {
			timeline.reverse();
		}
	}

	$(document).on('click', function (e) {
		var $target = $(e.target);
		if (menuOpen && $target != opt.$menu && !$target.closest(opt.$menu).length && $target != opt.$button && !$target.closest(opt.$button).length && $target != opt.$button && !$target.closest($wpadminbar).length) {
			hideMenu();
		}
	});

	$(document).on('extra.responsive-resize', function(){
		update();
	});

	function update() {
		if (small) {
			$toMove = opt.moveButton ? opt.$button : opt.$site;
		} else {
			$toMove = opt.moveButton ? [opt.$site, opt.$button] : opt.$site;
		}
		if (!small && !opt.everySizes) {
			opt.$menu.removeAttr("style");
			opt.$site.removeAttr("style");
			opt.$menu.hide();
			$html.removeClass('responsive-menu').addClass('no-responsive-menu');
		} else {
			$html.removeClass('no-responsive-menu').addClass('responsive-menu');
		}
	}

	$window.on('extra.triggerHideResponsiveMenu', function () {
		hideMenu();
	}).on('extra.triggerShowResponsiveMenu', function () {
		showMenu();
	});
}