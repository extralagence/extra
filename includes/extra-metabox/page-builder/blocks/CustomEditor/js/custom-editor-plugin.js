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
			height: 400
		};

	// The actual plugin constructor
	function Plugin ( element, options ) {
		var plugin = this;
		plugin.element = $(element);
		// jQuery has an extend method which merges the contents of two or
		// more objects, storing the result in the first object. The first object
		// is generally empty as we don't want to alter the default options for
		// future instances of the plugin
		plugin.settings = $.extend( {}, defaults, options );

		// set all vars
		plugin._editor = plugin.element;
		if(!plugin._editor.hasClass('extra-custom-editor-wrapper')) {
			plugin._editor = plugin._editor.find('.extra-custom-editor-wrapper');
		}

		plugin._textarea = plugin._editor.find("textarea.extra-custom-editor:first").addClass("mceEditor");

		this._id = plugin._textarea.attr("id");
		plugin._default_body_class = tinymce.settings.body_class;
		plugin._default_id = tinymce.settings.id;
		plugin._default_height = plugin.settings.height;
		plugin._default_content_css = tinymce.settings.content_css;
		plugin._default_selector = tinymce.settings.selector;
		plugin._default_wp_autoresize_on = tinymce.settings.wp_autoresize_on;
		plugin._default_wpautop = tinymce.settings.wpautop;
		plugin._default_plugins = tinymce.settings.plugins;

		plugin._tempSettings = $.extend({}, tinymce.settings);
		plugin._handle = $("#" + plugin._id + "-resize-handle");
		plugin._mce = false;
		plugin._offset = 0;
		plugin._mceEditor = tinymce.get(plugin._id);
		plugin._document = $(document);

		plugin.init = function () {
			quicktags({
				id : plugin._id,
				buttons : 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'
			});

			// SET NEW VALUES
			tinymce.settings.body_class = tinymce.settings.body_class + " " + plugin._textarea.data("extra-name");
			tinymce.settings.id = plugin._id;
			tinyMCE.settings.id = plugin._id;
			tinymce.settings.height = plugin.settings.height;
			tinymce.settings.selector = '#'+plugin._id;
			tinymce.settings.wp_autoresize_on = false;
			tinymce.settings.wpautop = true;
			// try to remove ',wpview' for parse video issue...
			tinymce.settings.plugins = tinymce.settings.plugins.replace(',wpview', '');

			if(plugin._textarea.data('custom-css')) {
				tinymce.settings.content_css = tinymce.settings.content_css + ',' + plugin._textarea.data('custom-css');
			}
			tinyMCEPreInit.mceInit[ plugin._id ] = $.extend({}, tinymce.settings);

			// SET NEW EDITOR
			tinymce.execCommand('mceAddEditor', false, plugin._id);

			// MAKE IT RESIZABLE
			plugin._handle.on('mousedown.extra-editor-resize', function(event) {
				if ( typeof tinymce !== 'undefined') {
					plugin._mceEditor = tinymce.get(plugin._id);
				}

				if (plugin._mceEditor && !plugin._mceEditor.isHidden()) {
					plugin._mce = true;
					plugin._offset = $('#' + plugin._id + '_ifr').height() - event.pageY;
				} else {
					plugin._mce = false;
					plugin._offset = plugin._textarea.height() - event.pageY;
					plugin._textarea.blur();
				}

				plugin._document.on('mousemove.extra-editor-resize', plugin.dragging).on('mouseup.extra-editor-resize mouseleave.extra-editor-resize', plugin.endDrag);
				event.preventDefault();
			}).on('mouseup.extra-editor-resize', plugin.endDrag);


			// OK, PROCESSED
			plugin._document.trigger("extra-editor-processed", [plugin._wrapper, plugin._id]);
			plugin._editor.addClass('extra-editor-processed');

			// RESET DEFAULT VALUES
			tinymce.settings.body_class = plugin._default_body_class;
			tinymce.settings.id = plugin._default_id;
			tinymce.settings.selector = plugin._default_selector;
			tinymce.settings.wp_autoresize_on = plugin._default_wp_autoresize_on;
			tinymce.settings.wpautop = plugin._default_wpautop;
			tinymce.settings.height = plugin._default_height;
			tinymce.settings.content_css = plugin._default_content_css;
			tinymce.settings.plugins = plugin._default_plugins;
			tinymce.settings = $.extend({}, plugin._tempSettings);

			plugin._editor.on('enable.extraPageBuilderCustomEditor', plugin.enable);
			plugin._editor.on('disable.extraPageBuilderCustomEditor', plugin.disable);
		};
		plugin.dragging = function(event) {
			if (plugin._mce) {
				plugin._mceEditor.theme.resizeTo(null, plugin._offset + event.pageY);
			} else {
				plugin._textarea.height(Math.max(50, plugin._offset + event.pageY));
			}
			event.preventDefault();
		};
		plugin.endDrag = function() {
			var height,
				toolbarHeight;
			if (plugin._mce) {
				plugin._mceEditor.focus();
				toolbarHeight = $('#wp-' + plugin._id + '-editor-container .mce-toolbar-grp').height();
				if (toolbarHeight < 10 || toolbarHeight > 200) {
					toolbarHeight = 30;
				}
				height = parseInt($('#' + plugin._id + '_ifr').css('height'), 10) + toolbarHeight - 28;
			} else {
				plugin._textarea.focus();
				height = parseInt(plugin._textarea.css('height'), 10);
			}
			plugin._document.off('.extra-editor-resize');
			// sanity check
			if (height && height > 50 && height < 5000) {
				setUserSetting('ed_size', height);
			}
		};
		plugin.enable = function () {
			tinymce.settings.height = plugin.settings.height;
			tinymce.execCommand('mceFocus', false, plugin._id);

			var rawContent = plugin._textarea.val();
//			if (tinymce.get(plugin._id)) {
//				console.log(tinymce.get);
//				rawContent = tinymce.get(plugin._id).getContent();
//			}
			plugin._textarea.val(window.switchEditors.wpautop(rawContent));
			tinymce.execCommand('mceAddEditor', false, plugin._id);
			tinymce.execCommand('mceFocus', false, plugin._id);
		};
		plugin.disable = function () {
			tinymce.execCommand('mceRemoveEditor', false, plugin._id);
		};

		plugin.init();
	}

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

})(jQuery, window, document);