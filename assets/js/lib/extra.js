/*********************
 *
 * WINDOW VARS
 *
 *********************/
var extra = {},
	$window,
	wWidth,
	wHeight,
	resizeTimer;
/*********************
 *
 * RESPONSIVE
 *
 *********************/
var extraResponsiveSizesTests = {},
	small = null;
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
		var _tmpExtraResponsiveSizesTests = $.extend({}, extraResponsiveSizesTests);
		$.each(extraResponsiveSizes, function(index, value) {
			_tmpExtraResponsiveSizesTests[index] = matchMedia(value).matches;
		});
		if(extraResponsiveSizes['desktop'] !== undefined) {
			small = !_tmpExtraResponsiveSizesTests['desktop'];
		}
		if(JSON.stringify(_tmpExtraResponsiveSizesTests) !== JSON.stringify(extraResponsiveSizesTests)) {
			extraResponsiveSizesTests = $.extend({}, _tmpExtraResponsiveSizesTests);
			$(document).trigger("extra.responsive-resize");
		}
	}).trigger('extra.resize');
	/*********************
	 *
	 * EXTRA SLIDER RESIZE
	 *
	 *********************/
	extra.resizeEvent = 'extra.resize';
	/**************************
	 *
	 *
	 * GET SCREEN SIZE
	 *
	 *
	 *************************/
	var getImageVersion = function () {
		var toReturn = null;
		$.each(extraResponsiveSizesTests, function(index, value) {
			if(value === true) {
				toReturn = index;
			}
		});
		return toReturn;
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

		var datas = container.find("noscript"),
			altTxt = datas.data("alt"),
			size = getImageVersion(),
			addImage = function (size) {

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
							container.find("img").not(imgElement).remove();
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

		container.data('responsiveImageProcessed', true);

	}


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
        var $this = $(this),
            $img = $this.find(" > img").first(),
            $icon = zoomIcon.clone(),
            width = 0,
            height = 0;
        if ($img.length) {
            width = $img.outerWidth();
            height = $img.outerHeight();
            $(this).addClass("zoom");
            if(!$img[0].complete) {
                $img.load(function() {
                   width = $img.outerWidth();
                   height = $img.outerHeight();
                    $(this).width($img.outerWidth()).height($img.outerHeight());
                });
            } else {
                $(this).width($img.outerWidth()).height($img.outerHeight());
            }
                $this.append($icon);
                TweenMax.set($icon, {css: {opacity: 0}});
                $this.hover(function () {
                    TweenMax.to($icon, 0.3, {css: {opacity: 1}});
                }, function () {
                    TweenMax.to($icon, 0.3, {css: {opacity: 0}});
                });
            if ($img.hasClass("alignleft")) {
                $this.addClass("alignleft");
            }
            if ($img.hasClass("alignright")) {
                $this.addClass("alignright");
            }
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
		var wpadminbar = $("#wpadminbar");
		$(window).on('extra.resize', function () {
			if (wpadminbar.length) {
				$switcher.css("top", wpadminbar.height());
			}

			if (menuOpen) {
				showMenu(true);
			} else {
				hideMenu(true);
			}
		});


		// SHOW
		function showMenu(fast) {
			$menu.css('visibility', 'visible');
			if (small) {
				menuOpen = true;
				$("html").addClass('menu-open');
				if (transform3d) {
					TweenMax.to($menu, (fast ? 0 : 0.4), {x: 0, y: 0, z: 1, ease: Quad.EaseOut});
					TweenMax.to([$wrapper, $switcher], (fast ? 0 : 0.5), {x: $menu.width() + 'px', y: 0, z: 2, ease: Quad.EaseOut});
				} else {
					TweenMax.to($menu, (fast ? 0 : 0.4), {x: 0, ease: Quad.EaseOut});
					TweenMax.to([$wrapper, $switcher], (fast ? 0 : 0.5), {x: $menu.width() + 'px', ease: Quad.EaseOut});
				}
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
				TweenMax.to([$wrapper, $switcher], (fast ? 0 : 0.5), {x: 0, ease: Strong.EaseIn});
			}
			if (!small) {
				$menu.removeAttr("style");
				$wrapper.removeAttr("style");
			}
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
		$menu.css('visibility', 'visible');
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