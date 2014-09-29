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

	console.log('ExtraResponsiveMenu');

	var opt = $.extend({
			'$menu' : $("#mobile-menu-container"),
			'$site' : $("#wrapper"),
			'$button': $("#switch-mobile-menu"),
			'everySizes' : false,
			'moveButton' : true
		}, options),
		menuOpen = true,
		$html = $('html'),
		transform3d = $html.hasClass('csstransforms3d'),
		$wpadminbar = $("#wpadminbar"),
		$toMove = opt.moveButton ? [opt.$site, opt.$button] : opt.$site;

	console.log('opt.moveButton');
	console.log(opt.moveButton);

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
		if ($wpadminbar.length) {
			opt.$button.css("top", $wpadminbar.height());
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

	$(document).on('click', function(e) {
		var $target = $(e.target);
		if(menuOpen && $target != opt.$menu && !$target.closest(opt.$menu).length && $target != opt.$button && !$target.closest(opt.$button).length && $target != opt.$button && !$target.closest($wpadminbar).length) {
			console.log(e);
			hideMenu();
		}
	});

	$(document).on('extra.responsive-resize', function(){
		if (!small && !opt.everySizes) {
			opt.$menu.removeAttr("style");
			opt.$site.removeAttr("style");
			opt.$menu.hide();
		} else {
			opt.$menu.show();
		}
	});

	// INIT
	opt.$menu.css('visibility', 'visible');
	hideMenu(true);
}