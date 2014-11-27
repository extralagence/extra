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
			$menu : $("#mobile-menu-container"),
			$site : $("#wrapper"),
			$button: $("#switch-mobile-menu"),
			everySizes : false,
			moveButton : true,
			prependTimeline: null,
			appendTimeline: null
		}, options),
		menuOpen = true,
		$html = $('html'),
		transform3d = $html.hasClass('csstransforms3d'),
		$wpadminbar = $("#wpadminbar"),
		timeline = new TimelineMax({paused: true, onReverseComplete: function() {
			$window.trigger('extra.hideResponsiveMenuComplete');
		}}),
		$toMove;
	if(small) {
		$toMove = opt.moveButton ? opt.$button : [];
	} else {
		$toMove = opt.moveButton ? [opt.$site, opt.$button] : opt.$site;
	}
	/**************************
	 *
	 *
	 * INIT
	 *
	 *
	 *************************/
	if(!opt.$menu.length || !opt.$site.length || !opt.$button.length) {
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
	 * CREATE TIMELINE
	 *
	 *
	 *************************/
	if(opt.prependTimeline) {
		timeline = opt.prependTimeline(timeline);
	}
	if (transform3d) {
		TweenMax.set(opt.$menu, {x: -opt.$menu.width() + 'px', force3D: true, ease: Strong.EaseIn, onComplete: function() {
			$window.trigger('extra.hideResponsiveMenuComplete');
		}});
		TweenMax.set($toMove, {x: 0, force3D: true, clearProps:"all", ease: Strong.EaseIn});
	} else {
		TweenMax.set(opt.$menu, {x: -opt.$menu.width() + 'px', ease: Strong.EaseIn});
		TweenMax.set($toMove, {x: 0, clearProps:"all", ease: Strong.EaseIn});
	}
	if (transform3d) {
		timeline.to(opt.$menu, 0.4, {x: 0, force3D: true, ease: Quad.EaseOut});
		timeline.to($toMove, 0.5, {x: opt.$menu.width() + 'px', force3D: true, ease: Quad.EaseOut, onComplete: function() {
			$window.trigger('extra.showResponsiveMenuComplete');
		}}, '-=0.3');
	} else {
		timeline.to(opt.$menu, 0.4, {x: 0, ease: Quad.EaseOut});
		timeline.to($toMove, 0.5, {x: opt.$menu.width() + 'px', ease: Quad.EaseOut, onComplete: function() {
			$window.trigger('extra.showResponsiveMenuComplete');
		}}, '-=0.3');
	}
	if(opt.appendTimeline) {
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
			if(fast) {
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
		if(fast) {
			timeline.totalProgress(0);
		} else {
			timeline.reverse();
		}
	}

	$(document).on('click', function(e) {
		var $target = $(e.target);
		if(menuOpen && $target != opt.$menu && !$target.closest(opt.$menu).length && $target != opt.$button && !$target.closest(opt.$button).length && $target != opt.$button && !$target.closest($wpadminbar).length) {
			hideMenu();
		}
	});

	$(document).on('extra.responsive-resize', function(){
		if(small) {
			$toMove = opt.moveButton ? opt.$button : [];
		} else {
			$toMove = opt.moveButton ? [opt.$site, opt.$button] : opt.$site;
		}
		if (!small && !opt.everySizes) {
			opt.$menu.removeAttr("style");
			opt.$site.removeAttr("style");
			opt.$menu.hide();
		} else {
			opt.$menu.show();
		}
	});

	$window.on('extra.triggerHideResponsiveMenu', function(){
		hideMenu();
	}).on('extra.triggerShowResponsiveMenu', function(){
		showMenu();
	});

	// INIT
	opt.$menu.css('visibility', 'visible');
	hideMenu(true);
}