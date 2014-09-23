/**************************
 *
 *
 * Responsive Navigation
 * @$menu : jquery object
 * @$site : jquery object
 * @$switch : jquery object
 * @everySizes : boolean
 *
 *
 *************************/
function ExtraResponsiveMenu(options) {

	var opt = $.extend({
			'$menu' : $("#mobile-menu-container"),
			'$site' : $("#wrapper"),
			'$switch': $("#switch-mobile-menu"),
			'everySizes' : false,
			'moveButton' : true
		}, options),
		menuOpen = true,
		$html = $('html'),
		transform3d = $html.hasClass('csstransforms3d'),
		wpadminbar = $("#wpadminbar"),
		$toMove = opt.moveButton ? [opt.$site, opt.$switch] : opt.$site;
	/**************************
	 *
	 *
	 * INIT
	 *
	 *
	 *************************/
	if(!opt.$menu.length || !opt.$site.length || !opt.$switch.length) {
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
	opt.$switch.click(function () {
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
		if (wpadminbar.length) {
			opt.$switch.css("top", wpadminbar.height());
		}

		if (menuOpen) {
			showMenu(true);
		} else {
			hideMenu(true);
		}
	});
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
			if (transform3d) {
				TweenMax.to(opt.$menu, (fast ? 0 : 0.4), {x: 0, force3D: true, ease: Quad.EaseOut});
				TweenMax.to($toMove, (fast ? 0 : 0.5), {x: opt.$menu.width() + 'px', force3D: true, ease: Quad.EaseOut});
			} else {
				TweenMax.to(opt.$menu, (fast ? 0 : 0.4), {x: 0, ease: Quad.EaseOut});
				TweenMax.to($toMove, (fast ? 0 : 0.5), {x: opt.$menu.width() + 'px', ease: Quad.EaseOut});
			}
			$window.trigger('extra.showResponsiveMenu');
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
		fast = (fast !== undefined) ? fast : false;
		menuOpen = false;
		$html.removeClass('menu-open');
		if (transform3d) {
			TweenMax.to(opt.$menu, (fast ? 0 : 0.6), {x: -opt.$menu.width() + 'px', force3D: true, ease: Strong.EaseIn});
			TweenMax.to($toMove, (fast ? 0 : 0.5), {x: 0, force3D: true, ease: Strong.EaseIn});
		} else {
			TweenMax.to(opt.$menu, (fast ? 0 : 0.6), {x: -opt.$menu.width() + 'px', ease: Strong.EaseIn});
			TweenMax.to($toMove, (fast ? 0 : 0.5), {x: 0, ease: Strong.EaseIn});
		}
		if (!small && !opt.everySizes) {
			opt.$menu.removeAttr("style");
			opt.$site.removeAttr("style");
			opt.$menu.hide();
		}
		$window.trigger('extra.hideResponsiveMenu');
	}

	// INIT
	opt.$menu.css('visibility', 'visible');
	hideMenu(true);
}