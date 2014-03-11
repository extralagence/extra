$.fn.fancySelect = function (opts) {

	var isiOS,
		settings;

	if (opts == null) {
		opts = {};
	}

	settings = $.extend({
		forceiOS: false,
		includeBlank: false
	}, opts);

	isiOS = !!navigator.userAgent.match(/iP(hone|od|ad)/i);

	return this.each(function () {

		var copyOptionsToList,
			disabled,
			options,
			sel,
			trigger,
			updateTriggerText,
			wrapper;

		sel = $(this);

		if (sel.hasClass('fancified') || sel[0].tagName !== 'SELECT') {
			return;
		}

		sel.addClass('fancified').wrap('<div class="fancy-select">');

		wrapper = sel.parent();

		if (sel.data('class')) {
			wrapper.addClass(sel.data('class'));
		}

		wrapper.append('<div class="trigger">');

		if (!(isiOS && !settings.forceiOS)) {
			wrapper.append('<ul class="options">');
		}

		trigger = wrapper.find('.trigger');
		options = wrapper.find('.options');
		disabled = sel.prop('disabled');

		if (disabled) {
			wrapper.addClass('disabled');
		}

		updateTriggerText = function () {
			return trigger.text(sel.find(':selected').text());
		};

		sel.on('blur', function () {
			console.log("coucou 1");
			if (wrapper.hasClass('open')) {
				console.log("coucou 2");
				return setTimeout(function () {
					console.log("coucou 3");
					return trigger.trigger('close');
				}, 250);
			}
		});

		trigger.on('close', function () {
			return wrapper.removeClass('open');
		});

		trigger.on('click', function () {

			var offParent,
				parent;

			if (!disabled) {
				wrapper.toggleClass('open');
				if (isiOS && !settings.forceiOS) {
					if (wrapper.hasClass('open')) {
						return sel.focus();
					}
				} else {
					if (wrapper.hasClass('open')) {
						parent = trigger.parent();
						offParent = parent.offsetParent();
						if ((parent.offset().top + parent.outerHeight() + options.outerHeight() + 20) > $(window).height() + $(window).scrollTop()) {
							options.addClass('overflowing');
						} else {
							options.removeClass('overflowing');
						}
					}
					if (!isiOS) {
						return sel.focus();
					}
				}
			}
		});

		sel.on('enable', function () {
			sel.prop('disabled', false);
			wrapper.removeClass('disabled');
			disabled = false;
			return copyOptionsToList();
		});

		sel.on('disable', function () {
			sel.prop('disabled', true);
			wrapper.addClass('disabled');
			return disabled = true;
		});

		sel.on('change', function (e) {
			if (e.originalEvent && e.originalEvent.isTrusted) {
				return e.stopPropagation();
			} else {
				return updateTriggerText();
			}
		});

		sel.on('keydown', function (e) {
			var hovered, newHovered, w;
			w = e.which;
			hovered = options.find('.hover');
			hovered.removeClass('hover');
			if (!wrapper.hasClass('open')) {
				if (w === 13 || w === 32 || w === 38 || w === 40) {
					e.preventDefault();
					return trigger.trigger('click');
				}
			} else {
				if (w === 38) {
					e.preventDefault();
					if (hovered.length && hovered.index() > 0) {
						hovered.prev().addClass('hover');
					} else {
						options.find('li:last-child').addClass('hover');
					}
				} else if (w === 40) {
					e.preventDefault();
					if (hovered.length && hovered.index() < options.find('li').length - 1) {
						hovered.next().addClass('hover');
					} else {
						options.find('li:first-child').addClass('hover');
					}
				} else if (w === 27) {
					e.preventDefault();
					trigger.trigger('click');
				} else if (w === 13 || w === 32) {
					e.preventDefault();
					hovered.trigger('click');
				} else if (w === 9) {
					if (wrapper.hasClass('open')) {
						trigger.trigger('close');
					}
				}
				newHovered = options.find('.hover');
				if (newHovered.length) {
					options.scrollTop(0);
					return options.scrollTop(newHovered.position().top - 12);
				}
			}
		});

		options.on('click', 'li', function (e) {
			sel.val($(this).data('value'));
			if (!isiOS) {
				sel.trigger('blur').trigger('focus');
			}
			options.find('.selected').removeClass('selected');
			$(e.currentTarget).addClass('selected');
			trigger.trigger('close');
			return sel.val($(this).data('value')).trigger('change').trigger('blur').trigger('focus');
		});

		options.on('mouseenter', 'li', function () {
			var hovered, nowHovered;
			nowHovered = $(this);
			hovered = options.find('.hover');
			hovered.removeClass('hover');
			return nowHovered.addClass('hover');
		});

		options.on('mouseleave', 'li', function () {
			return options.find('.hover').removeClass('hover');
		});

		copyOptionsToList = function () {
			var selOpts;
			updateTriggerText();
			if (isiOS && !settings.forceiOS) {
				return;
			}
			selOpts = sel.find('option');
			return sel.find('option').each(function (i, opt) {
				opt = $(opt);
				if (!opt.prop('disabled') && (opt.val() || settings.includeBlank)) {
					if (opt.prop('selected')) {
						return options.append("<li data-value=\"" + (opt.val()) + "\" class=\"selected\">" + (opt.text()) + "</li>");
					} else {
						return options.append("<li data-value=\"" + (opt.val()) + "\">" + (opt.text()) + "</li>");
					}
				}
			});
		};

		sel.on('update', function () {
			wrapper.find('.options').empty();
			return copyOptionsToList();
		});

		return copyOptionsToList();
	});
};