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
	var pluginName = "extraPageBuilderAccordion",
		defaults = {};

	// The actual plugin constructor
	function Plugin ( element, options ) {
		this.element = $(element);
		// jQuery has an extend method which merges the contents of two or
		// more objects, storing the result in the first object. The first object
		// is generally empty as we don't want to alter the default options for
		// future instances of the plugin
		this.settings = $.extend( {}, defaults, options );
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	}

	Plugin.prototype = {
		init: function () {
			this.wrapper = this.element.find('.wpa_loop');
			this.navigation = this.wrapper.find('ul.extra-accordion-navigation');
			if (this.navigation.length == 0) {
				this.navigation = $('<ul class="extra-accordion-navigation"></ul>').prependTo(this.wrapper);
			}
			this.idBase = this.wrapper.attr("id");
			this.wpautop = true;
			this.textareaID = null;
			this.selectedEd = null;

			var plugin = this;

			$.wpalchemy.on('wpa_copy', function(e, elmt) {
				$(elmt).find('.extra-accordion-custom-editor-wrapper').each(function () {
					var $editor = $(this);
					if ($editor.data('extraPageBuilderAccordionCustomEditor') === undefined) {
						$editor.extraPageBuilderAccordionCustomEditor({height: 210});
					}
				});
				plugin.updateMenu(plugin);
				if(plugin.navigation.children().size()) {
					plugin.navigation.find(">li:last a").click();
				}
			});

			$.wpalchemy.bind('wpa_delete', function(e, elmt){
				plugin.updateMenu(plugin);
				if(plugin.navigation.children().size()) {
					plugin.navigation.find(">li:last a").click();
				}
			});

			this.updateMenu(plugin, true);

			//Init editors
			this.element.find('.extra-accordion-custom-editor-wrapper').each(function () {
				if($(this).closest('.wpa_group.tocopy').length) {
					return;
				}
				var $editor = $(this);

				if ($editor.data('extraPageBuilderAccordionCustomEditor') === undefined) {
					$editor.extraPageBuilderAccordionCustomEditor({height: 210});
				}
			});
		},
		updateMenu: function (plugin, first) {
			// IS IT FIRST INIT
			if(!first) {
				plugin.wrapper.tabs("destroy");
				plugin.navigation.sortable("destroy");
			}

			// EMPTY THE LIST
			plugin.navigation.empty();


			// LOOP THROUGH THE ITEMS
			plugin.wrapper.find(".wpa_group:not('.tocopy')").each(function(i){

				var elmt = $(this);
				var title = elmt.find("h2").first();
				elmt.attr("id", plugin.idBase+i);

				if(!elmt.data("processed") || elmt.data("processed") != "processed") {
					title.text(title.text() + " " + (i+1));
					elmt.data("processed", "processed");
				}

				var link = $("<a></a>", {
					"href": "#"+(plugin.idBase+""+i),
					"text": title.text()
				});

				plugin.navigation.append(link);
				link.wrap("<li />");
			});


			// SET WRAPPER
			plugin.wrapper.tabs();

			// MAKE IT SORTABLE
			plugin.navigation.sortable({
				containment: "parent",
				forcePlaceholderSize: true,
				opacity: 1,
				placeholder: "extra-accordion-placeholder",
				start: function(event, ui) { // turn tinymce off while sorting (if not, it won't work when resorted)
					var item = ui.item,
						target = $("#"+item.attr("aria-controls")),
						editors = target.find('.extra-editor-processed');

					if(editors.length) {
						editors.each(function() {
							var textarea = $(this).find('textarea.extra-custom-editor'),
								textareaId = textarea.attr('id'),
								editor = tinymce.EditorManager.get(textareaId);
							textarea.data('tinymceSettings', editor.settings);
							tinymce.settings.wpautop = false;
							tinymce.execCommand('mceRemoveEditor', false, textarea.attr('id'));
						});
					}
				},
				stop: function(event, ui) { // re-initialize tinymce when sort is completed
					var item = ui.item,
						$accordion = $(this).closest('.extra-accordion');
						target = $accordion.find("#"+item.attr("aria-controls")),
						editors = target.find('.extra-editor-processed');

					// move the target
					target.insertAfter(plugin.wrapper.children().eq(item.index()));

					// reset the editors
					if(editors.length) {
						editors.each(function() {
							var textarea = $(this).find('textarea.extra-custom-editor'),
								textareaId = textarea.attr('id');
							tinymce.settings = textarea.data('tinymceSettings');
							tinymce.execCommand('mceAddEditor', false, textareaId);
						});
					}

					// refresh the tabs
					plugin.wrapper.tabs( "refresh" );
				}
			}).disableSelection();
		},
		enableEditors: function () {
			this.element.find('.extra-accordion-custom-editor-wrapper').each(function () {
				var $editor = $(this);
				if ($editor.data('extraPageBuilderAccordionCustomEditor') !== undefined) {
					$editor.data('extraPageBuilderAccordionCustomEditor').enable();
				}
			});
		},
		disableEditors: function () {
			this.element.find('.extra-accordion-custom-editor-wrapper').each(function () {
				var $editor = $(this);
				if ($editor.data('extraPageBuilderAccordionCustomEditor') !== undefined) {
					$editor.data('extraPageBuilderAccordionCustomEditor').disable();
				}
			});
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




;(function ( $, window, document, undefined ) {

	// undefined is used here as the undefined global variable in ECMAScript 3 is
	// mutable (ie. it can be changed by someone else). undefined isn't really being
	// passed in so we can ensure the value of it is truly undefined. In ES5, undefined
	// can no longer be modified.

	// window and document are passed through as local variable rather than global
	// as this (slightly) quickens the resolution process and can be more efficiently
	// minified (especially when both are regularly referenced in your plugin).

	// Create the defaults once
	var pluginName = "extraPageBuilderAccordionCustomEditor",
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
		if(!this._editor.hasClass('extra-accordion-custom-editor-wrapper')) {
			this._editor = this._editor.find('.extra-accordion-custom-editor-wrapper');
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

			this._editor.on('enable.extraPageBuilderAccordionCustomEditor', this.enable);
			this._editor.on('disable.extraPageBuilderAccordionCustomEditor', this.disable);
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



jQuery(document).ready(function($){
	//$('.extra-accordion').extraPageBuilderAccordion();

	var $pageBuilder = $('.extra-page-builder');

	$pageBuilder.on('showform.pagebuilder.extra', function (event, $block_type, $block, $form) {
		if ($block_type == 'accordion') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			var $accordion = $form.find('.extra-accordion');

			if ($accordion.data('extraPageBuilderAccordion') === undefined) {
				$accordion.extraPageBuilderAccordion({height: 400});
			}

			$accordion.data('extraPageBuilderAccordion').disableEditors();
			extraAdminModal
				.options({footer: ['extra-admin-modal-save'], header: ['extra-admin-modal-title'], size: {height: 687}})
				.show("Modifier l'accordéon",  $form);
			$accordion.data('extraPageBuilderAccordion').enableEditors();
		}
	});

	$pageBuilder.on('hideForm.pagebuilder.extra', function (event, block_type, $block, $form) {
		if (block_type == 'accordion') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			var $accordion = $form.find('.extra-accordion'),
				$accordionContent = $block.find('.extra-page-builder-block-content ul.extra-accordion');

			$accordion.data('extraPageBuilderAccordion').disableEditors();
			$block.find('.extra-page-builder-block-form').append($form);
			$accordion.data('extraPageBuilderAccordion').enableEditors();

			$accordionContent.empty();
			$accordion.find('.wpa_group:not(.tocopy) input.extra-accordion-title').each(function () {
				$accordionContent.append('<li><h3 class="extra-accordion-title">'+$(this).val()+'</h3></li>');
			});

			$accordion.data('extraPageBuilderAccordion').disableEditors();
		}
	});

	$pageBuilder.on('refreshPreview.pagebuilder.extra', function (event, block_type, $block, $form) {
		if (block_type == 'accordion') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			var $accordion = $form.find('.extra-accordion'),
				$accordionContent = $block.find('.extra-page-builder-block-content ul.extra-accordion');

			$accordionContent.empty();
			$accordion.find('.wpa_group:not(.tocopy) input.extra-accordion-title').each(function () {
				$accordionContent.append('<li><h3 class="extra-accordion-title">'+$(this).val()+'</h3></li>');
			});
		}
	});
});

