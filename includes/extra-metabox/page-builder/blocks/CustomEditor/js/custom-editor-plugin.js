// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ( $, window, document, undefined ) {

	// undefined is used here as the undefined global variable in ECMAScript 3 is
	// mutable (ie. it can be changed by someone else). undefined isn't really being
	// passed in so we can ensure the value of it is truly undefined. In ES5, undefined
	// can no longer be modified.

	// window and document are passed through as local variable rather than global
	// as this (slightly) quickens the resolution process and can be more efficiently
	// minified (especially when both are regularly referenced in your plugin).

	// Create the defaults once
	var pluginName = "extraPageBuilderCustomEditor",
		defaults = {
			height: 300
		},
		$this;

	// The actual plugin constructor
	function Plugin ( element, options ) {
		$this = this;
		this.element = $(element);
		// jQuery has an extend method which merges the contents of two or
		// more objects, storing the result in the first object. The first object
		// is generally empty as we don't want to alter the default options for
		// future instances of the plugin
		this.settings = $.extend( {}, defaults, options );
		this._defaults = defaults;
		this._name = pluginName;



		// set all vars
		this._editor = this.element;
		if(!this._editor.hasClass('extra-custom-editor-wrapper')) {
			this._editor = this._editor.find('.extra-custom-editor-wrapper');
		}
		this._textarea = this._editor.find("textarea.extra-custom-editor:first").addClass("mceEditor"),
			this._id = this._textarea.attr("id"),
			this._default_body_class = tinymce.settings.body_class,
			this._default_id = tinymce.settings.id,
			this._default_height = this.settings.height,
			this._default_content_css = tinymce.settings.content_css,
			this._tempSettings = $.extend({}, tinymce.settings),
			this._handle = $("#" + this._id + "-resize-handle"),
			this._mce = false,
			this._offset = 0,
			this._mceEditor,
			this._document = $(document);

		this.init();
	}

	Plugin.prototype = {
		init: function () {

			quicktags({
				id : this._id,
				buttons : 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'
			});

			// SET NEW VALUES
			tinymce.settings.body_class = tinymce.settings.body_class + " " + this._textarea.data("extra-name");
			tinymce.settings.id = this._id;
			tinymce.settings.height = this.settings.height;
			if(this._textarea.data('custom-css')) {
				tinymce.settings.content_css = tinymce.settings.content_css + ',' + this._textarea.data('custom-css');
			}
			tinymce.settings.wpautop = false;

			// SET NEW EDITOR
			tinymce.execCommand('mceAddEditor', false, this._id);

			// MAKE IT RESIZABLE
			this._handle.on('mousedown.extra-editor-resize', function(event) {
				if ( typeof tinymce !== 'undefined') {
					this._mceEditor = tinymce.get(this._id);
				}

				if (this._mceEditor && !this._mceEditor.isHidden()) {
					this._mce = true;
					this._offset = $('#' + this._id + '_ifr').height() - event.pageY;
				} else {
					this._mce = false;
					this._offset = this._textarea.height() - event.pageY;
					this._textarea.blur();
				}

				this._document.on('mousemove.extra-editor-resize', this.dragging).on('mouseup.extra-editor-resize mouseleave.extra-editor-resize', this.endDrag);
				event.preventDefault();
			}).on('mouseup.extra-editor-resize', this.endDrag);


			// OK, PROCESSED
			this._textarea.data('tinymceSettings', tinymce.settings);
			this._document.trigger("extra-editor-processed", [this._wrapper, this._id]);
			this._editor.addClass('extra-editor-processed');

			// RESET DEFAULT VALUES
			tinymce.settings.body_class = this._default_body_class;
			tinymce.settings.id = this._default_id;
			tinymce.settings.height = this._default_height;
			tinymce.settings.content_css = this._default_content_css;
			tinymce.settings = $.extend({}, this._tempSettings);

			this._editor.on('enable.extraPageBuilderCustomEditor', this.enable);
			this._editor.on('disable.extraPageBuilderCustomEditor', this.disable);
		},
		dragging: function(event) {
			if (this._mce) {
				this._mceEditor.theme.resizeTo(null, this._offset + event.pageY);
			} else {
				this._textarea.height(Math.max(50, this._offset + event.pageY));
			}
			event.preventDefault();
		},
		endDrag: function() {
			var height,
				toolbarHeight;
			if (this._mce) {
				this._mceEditor.focus();
				toolbarHeight = $('#wp-' + this._id + '-editor-container .mce-toolbar-grp').height();
				if (toolbarHeight < 10 || toolbarHeight > 200) {
					toolbarHeight = 30;
				}
				height = parseInt($('#' + this._id + '_ifr').css('height'), 10) + toolbarHeight - 28;
			} else {
				this._textarea.focus();
				height = parseInt(this._textarea.css('height'), 10);
			}
			this._document.off('.extra-editor-resize');
			// sanity check
			if (height && height > 50 && height < 5000) {
				setUserSetting('ed_size', height);
			}
		},
		enable: function () {
			tinymce.settings = this._textarea.data('tinymceSettings');
			tinymce.execCommand('mceAddEditor', false, this._id);
		},
		disable: function () {
			tinymce.settings.wpautop = false;
			tinymce.execCommand('mceRemoveEditor', false, this._id);
		}
	};

	// A really lightweight plugin wrapper around the constructor,
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