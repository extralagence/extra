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
	extra.getImageVersion = function () {
		// default value
		var toReturn = 'desktop';
		$.each(extraResponsiveSizesTests, function(index, value) {
			if(value === true) {
				toReturn = index;
			}
		});
		return toReturn;
	};
	/*********************
	*
	* EXTRA SLIDERS
	*
	*********************/
	$window.on('updateClones.extra.slider', function(event, currentItem, total, slider) {
		slider.find('.cloned .responsiveImagePlaceholder').each(function() {
			$window.trigger('extra.responsiveImage', [$(this).data("size", "")]);
		});
	});
	$window.on('init.extra.slider', function(event, items, numItems, slider) {
		slider.on('complete.extra.responsiveImage', function() {
			slider.trigger('update');
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
		"class": "zoomIcon"
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
				/*if(!$img[0].complete) {
					$img.load(function() {
						width = $img.outerWidth();
						height = $img.outerHeight();
						$(this).width($img.outerWidth()).height($img.outerHeight());
					});
				} else {
					$(this).width($img.outerWidth()).height($img.outerHeight());
				}*/
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
	/*********************
	 *
	 * WINDOW LOAD
	 *
	 *********************/
	$(window).load(function () {
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
				size = extra.getImageVersion(),
				addImage = function (size) {

					// SET NEW IMAGE
					if (datas && container.data("size") != size) {
						container.data("size", size);
						var imgSrc = datas.data("src-" + size);
						if (imgSrc) {
							var imgElement = $("<img />");
							imgElement.load(function () {
								// CORRECT IMAGE SIZE
								imgElement.attr({
									'width': this.width,
									'height': this.height
								});
	              				// APPEND
	              				imgElement.appendTo(container);
								// REMOVE EXISTING IMAGE
								container.find("img").not(imgElement).remove();
	              				container.trigger('complete.extra.responsiveImage');
							}).attr({
								alt: altTxt,
								src: imgSrc
							});
						}
					}
				};

			$window.on("extra.responsive-resize", function () {
				size = extra.getImageVersion();
				addImage(size);
			});
			addImage(size);

			container.data('responsiveImageProcessed', true);

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
	});
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