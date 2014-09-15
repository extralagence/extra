// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other thiss which may not be closed properly.
;(function ( $, window, document, undefined ) {

	// undefined is used here as the undefined global variable in ECMAScript 3 is
	// mutable (ie. it can be changed by someone else). undefined isn't really being
	// passed in so we can ensure the value of it is truly undefined. In ES5, undefined
	// can no longer be modified.

	// window and document are passed through as local variable rather than global
	// as this (slightly) quickens the resolution process and can be more efficiently
	// minified (especially when both are regularly referenced in your this).

	// Create the defaults once
	var pluginName = "extraAdminModal",
		defaults = {
			size: null,
			footer: ['extra-admin-modal-save'],
			header: ['extra-admin-modal-title', 'extra-admin-modal-close']
		};

	// The actual this constructor
	function Plugin ( element, options ) {
		this.element = $(element);
		// jQuery has an extend method which merges the contents of two or
		// more objects, storing the result in the first object. The first object
		// is generally empty as we don't want to alter the default options for
		// future instances of the this
		this.settings = $.extend( {}, defaults, options );
		this._defaults = defaults;
		this._name = pluginName;
		this.init();

		this.modal = this.element.find('.extra-admin-modal');
		this.modalTitle = this.element.find('.extra-admin-modal-title');
		this.modalContent = this.element.find('.extra-admin-modal-content');
		this.modalFooter = this.element.find('.extra-admin-modal-footer');
		this.modalFooterElements = this.modalFooter.children();
		this.modalHeader = this.element.find('.extra-admin-modal-header');
		this.modalHeaderElements = this.modalHeader.children();
	}

	Plugin.prototype = {
		init: function () {
			var plugin = this;
			this.element.append(
				'<div class="extra-admin-modal">' +
				'	<div class="extra-admin-modal-header">' +
				'		<h1 class="extra-admin-modal-title"></h1>' +
				'		<a href="#" class="extra-admin-modal-close">' +
				'			<span class="extra-admin-modal-close-icon"></span>' +
				'		</a>' +
				'	</div>' +
				'	<div class="extra-admin-modal-content-wrapper"><div class="extra-admin-modal-content extra-metabox"></div></div>' +
				'	<div class="extra-admin-modal-footer">' +
				'		<a href="#" class="extra-admin-modal-save button button-primary right">Valider</a>' +
				'	</div>' +
				'</div>'
			);

			this.element.on('click', '.extra-admin-modal-close', function (event) {
				$(event.delegateTarget).data(pluginName).hide();
				return false;
			});

			this.element.on('click', '.extra-admin-modal-save', function (event) {
				$(event.delegateTarget).data(pluginName).save();
				return false;
			});
		},
		options: function (options) {
			if (options) {
				this.settings = $.extend( {}, defaults, options );
			}

			this.modal.attr('style', '');
			this.modalFooterElements.attr('style', '');
			this.modalFooter.attr('style', '');
			this.modalHeaderElements.attr('style', '');
			this.modalHeader.attr('style', '');

			if (this.settings.size != null) {
				if (this.settings.size.width != null) {
					this.modal.css('margin-left', 'auto');
					this.modal.css('margin-right', 'auto');
					this.modal.css('width', this.settings.size.width);
				}
				if (this.settings.size.height != null) {
					this.modal.css('margin-top', 'auto');
					this.modal.css('margin-bottom', 'auto');
					this.modal.css('height', this.settings.size.height);
				}
			}

			this.modalFooterElements.css('display', 'none');
			if (this.settings.footer.length > 0) {
				for(var i= 0; i < this.settings.footer.length; i++) {
					this.modalFooter.find('> .'+this.settings.footer[i]).css('display', 'block');
				}
				this.element.addClass('has-footer');
			} else {
				this.modalFooter.css('display', 'none');
				this.element.removeClass('has-footer');
			}

			this.modalHeaderElements.css('display', 'none');
			if (this.settings.header.length > 0) {
				for(var i= 0; i < this.settings.header.length; i++) {
					this.modalHeader.find('> .'+this.settings.header[i]).css('display', 'block');
				}
				this.element.addClass('has-header');
			} else {
				this.modalHeader.css('display', 'none');
				this.element.removeClass('has-header');
			}

			return this;
		},
		show: function (title, $content) {
			this.modalTitle.html(title);
			this.modalContent.append($content);

			this.element.show();
			this.element.trigger('open.adminmodal.extra', [title, $content]);

			$('html').css('overflow', 'hidden');

			setTimeout(function() {
				jQuery('.extra-admin-modal-content').scrollTop(0);
			}, 100);

			return this;
		},
		hide: function () {
			this.element.trigger('close.adminmodal.extra', [this.modalContent.children()]);

			//Reset default settings;
			this.options([]);

			this.modalTitle.html('');
			this.modalContent.html('');

			this.element.hide();
			$('html').css('overflow', '');

			return this;
		},
		save: function () {
			this.element.trigger('save.adminmodal.extra', [this.modalContent.children()]);

			return this;
		}
	};

	// A really lightweight this wrapper around the constructor,
	// preventing against multiple instantiations
	$.fn[ pluginName ] = function ( options ) {
		this.each(function() {
			if ( !$.data( this, pluginName ) ) {
				$.data( this, pluginName, new Plugin( this, options ) );
			}
		});

		// chain jQuery functions
		return this;
	};

})( jQuery, window, document );