/*********************
 *
 * WINDOW VARS
 *
 *********************/
var $window,
	wWidth,
	wHeight,
	resizeTimer;
/*********************
 *
 * RESPONSIVE
 *
 *********************/
var tabletCondition = 'only screen and (max-width: 960px)',
	mobileCondition = 'only screen and (max-width: 690px)',
	small = "",
	tablet = "",
	mobile = "";
/*********************
 *
 * JQUERY START
 *
 *********************/
$(document).ready(function () {
	/**************************
	 *
	 *
	 * REAL RESIZE
	 *
	 *
	 *************************/
	$window = $(window);
	wWidth = $window.width();
	wHeight = $window.height();
	$window.on('resize', function () {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(resizeHandler, 300);
	});
	function resizeHandler() {
		if ($window.width() !== wWidth || $window.height() !== wHeight) {
			wWidth = $window.width();
			wHeight = $window.height();
			$window.trigger('extra.resize');
		}
	}

	/*********************
	 *
	 * MOBILE OR NOT MOBILE
	 *
	 *********************/
	$(window).on('extra.resize',function () {
		// IF STATE CHANGE, UPDATE
		if (matchMedia(tabletCondition).matches != tabletCondition || matchMedia(mobileCondition).matches != small) {
			small = tablet = matchMedia(tabletCondition).matches;
			mobile = matchMedia(mobileCondition).matches;
			$(document).trigger("extra.responsive-resize");
		}
	}).trigger('extra.resize');
	/**************************
	 *
	 *
	 * GET SCREEN SIZE
	 *
	 *
	 *************************/
	var getImageVersion = function () {
		if (!small) {
			return "desktop"; // default version
		} else if (small && mobile) {
			return "mobile";
		} else if (small && tablet) {
			return "tablet";
		}
	};
	/**************************
	 *
	 *
	 * RESPONSIVE IMAGE
	 *
	 *
	 *************************/
	$(".responsiveImagePlaceholder").each(function () {
		initResponsiveImage($(this).data("size", ""));
	});
	function initResponsiveImage(container) {

		var datas = container.find("noscript");
		var altTxt = datas.data("alt");
		var size = getImageVersion();
		var addImage = function (size) {

			// SET NEW IMAGE
			if (datas && container.data("size") != size) {
				container.data("size", size);
				var imgSrc = datas.data("src-" + size);
				if (imgSrc) {
					var imgElement = $("<img>");
					imgElement.load(function () {
						// REMOVE EXISTING IMAGE
						imgElement.attr({
							'width': this.width,
							'height': this.height
						}).appendTo(container);
						container.find("img").not(imgElement).css('position', 'absolute');
						TweenMax.from(imgElement, 1, {autoAlpha: 0, onComplete: function() {
							container.find("img").not(imgElement).remove();
						}});
					});
					imgElement.attr({
						"alt": altTxt,
						"src": imgSrc
					});
				}
			}
		};

		$window.on("extra.responsive-resize", function () {
			size = getImageVersion();
			addImage(size);
		});
		addImage(size);

	}

	$window.on('extra.responsiveImage', function(event, obj) {
		obj.each(function() {
			var $elem = $(this);
			if($elem.hasClass('responsiveImagePlaceholder')) {
				initResponsiveImage($elem.data("size", ""));
			} else {
				initResponsiveImage($elem.find('.responsiveImagePlaceholder').data("size", ""));
			}
		});
	});

	/*********************
	 *
	 * LOGO HOVER
	 *
	 *********************/
	var homeBtn = $("#main-menu .menu-item-home > a");
	$(".site-title a").hover(function () {
		homeBtn.addClass("hover");
	}, function () {
		homeBtn.removeClass("hover");
	});
	/*********************
	 *
	 * ALL LINKS TO IMAGES
	 *
	 *********************/
	var zoomIcon = $("<span />", {
		"class": "zoom-icon"
	});
	$("a[href$='.jpg'], a[href$='.png'], a[href$='.gif'], .fancybox").not('.no-fancybox').filter(function () {
		return $(this).attr("target") != "_blank";
	}).attr("data-fancybox-group", "gallery").fancybox({
		margin: 50,
		padding: 0,
		type: 'image',
		helpers: {
			title: {
				type: 'over'
			}
		}
	}).each(function () {
		var $this = $(this);
		var $img = $this.find(" > img");
		var $icon = zoomIcon.clone();
		$(this).addClass("zoom");
		$(this).width($img.outerWidth()).height($img.outerHeight());
		if ($img.length) {
			$this.append($icon);
			TweenMax.set($icon, {css: {opacity: 0}});
			$this.hover(function () {
				TweenMax.to($icon, 0.3, {css: {opacity: 1}});
			}, function () {
				TweenMax.to($icon, 0.3, {css: {opacity: 0}});
			});
		}
		if ($img.hasClass("alignleft")) {
			$this.addClass("alignleft");
		}
		if ($img.hasClass("alignright")) {
			$this.addClass("alignright");
		}
	});
	/*********************
	 *
	 * BACK TO TOP
	 *
	 *********************/
	$(".totop").click(function () {
		TweenMax.to($window, 0.5, {scrollTo: {y: 0}});
		return false;
	});
});
$(function () {
	var $menu = $("#mobile-menu-container");
	var $wrapper = $("#wrapper");
	var $switcher = $("#switch-mobile-menu");
	var menuOpen = true;
	var transform3d = $("html").hasClass('csstransforms3d');

	if($menu.length && $wrapper.length && $switcher.length) {

		// EVENTS
		$switcher.click(function () {
			if (small) {
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
		if ($("body").hasClass("admin-bar")) {
			var wpadminbar = $("#wpadminbar");
			$(window).on('extra.resize', function () {
				$switcher.css("top", wpadminbar.height());
			});
		}
		// SHOW
		function showMenu() {
			if (small) {
				menuOpen = true;
				$("html").addClass('menu-open');
				if (transform3d) {
					TweenMax.to($menu, 0.4, {x: 0, y: 0, z: 1, ease: Quad.EaseOut});
					TweenMax.to([$wrapper, $switcher], 0.5, {x: $menu.width() + 'px', y: 0, z: 2, ease: Quad.EaseOut});
				} else {
					TweenMax.to($menu, 0.4, {x: 0, ease: Quad.EaseOut});
					TweenMax.to([$wrapper, $switcher], 0.5, {x: $menu.width() + 'px', ease: Quad.EaseOut});
				}
				$(document).swipe("enable");
			} else {
				hideMenu(true);
			}
		}

		// HIDE
		function hideMenu(fast) {
			if (!small) {
				fast = true;
			}
			menuOpen = false;
			$("html").removeClass('menu-open');
			if (transform3d) {
				TweenMax.to($menu, (fast ? 0 : 0.6), {x: -$menu.width() + 'px', y: 0, z: 0, ease: Strong.EaseIn});
				TweenMax.to([$wrapper, $switcher], (fast ? 0 : 0.5), {x: 0, y: 0, z: 0, ease: Strong.EaseIn});
			} else {
				TweenMax.to($menu, (fast ? 0 : 0.6), {x: -$menu.width() + 'px', ease: Strong.EaseIn});
				TweenMax.to($wrapper, (fast ? 0 : 0.5), {x: 0, ease: Strong.EaseIn});
			}
			if (!small) {
				$menu.removeAttr("style");
				$wrapper.removeAttr("style");
			}
			$(document).swipe("disable");
		}

		// TAP
		function bodyTap(event, target) {
			if (menuOpen && small) {
				hideMenu();
			}
		}

		// SWIPE LEFT
		function bodySwipeLeft(event, direction, distance, duration) {
			if (menuOpen && small) {
				hideMenu();
			}
		}

		// SWIPE RIGHT
		function bodySwipeRight(event, direction, distance, duration) {
			if (!menuOpen && small) {
				showMenu();
			}
		}

		// INIT
		hideMenu(true);

	}

});
/*********************
 *
 * NO BUG CONSOLE
 *
 *********************/
(function () {
	var method;
	var noop = function () {
	};
	var methods = [
		'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
		'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
		'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
		'timeStamp', 'trace', 'warn'
	];
	var length = methods.length;
	var console = (window.console = window.console || {});

	while (length--) {
		method = methods[length];

		// Only stub undefined methods.
		if (!console[method]) {
			console[method] = noop;
		}
	}
}());
/*********************
 *
 * MATCH MEDIA
 *
 *********************/
window.matchMedia = window.matchMedia || (function (doc, undefined) {

	"use strict";

	var bool,
		docElem = doc.documentElement,
		refNode = docElem.firstElementChild || docElem.firstChild,
	// fakeBody required for <FF4 when executed in <head>
		fakeBody = doc.createElement("body"),
		div = doc.createElement("div");

	div.id = "mq-test-1";
	div.style.cssText = "position:absolute;top:-100em";
	fakeBody.style.background = "none";
	fakeBody.appendChild(div);

	return function (q) {

		div.innerHTML = "&shy;<style media=\"" + q + "\"> #mq-test-1 { width: 42px; }</style>";

		docElem.insertBefore(fakeBody, refNode);
		bool = div.offsetWidth === 42;
		docElem.removeChild(fakeBody);

		return {
			matches: bool,
			media: q
		};

	};
}(document));