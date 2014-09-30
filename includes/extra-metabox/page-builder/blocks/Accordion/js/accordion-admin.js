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
		this.init(this);
	}

	Plugin.prototype = {
		init: function (plugin) {
			plugin.wrapper = plugin.element.find('.wpa_loop');
			plugin.navigation = plugin.wrapper.find('ul.extra-accordion-navigation');
			if (plugin.navigation.length == 0) {
				plugin.navigation = $('<ul class="extra-accordion-navigation"></ul>').prependTo(plugin.wrapper);
			}
			plugin.idBase = plugin.wrapper.attr("id");

			$.wpalchemy.on('wpa_copy', function(e, elmt) {
				$(elmt).find('.extra-accordion-custom-editor-wrapper > .extra-custom-editor-wrapper').each(function () {
					var $editor = $(this);
					$editor.extraPageBuilderCustomEditor({height: 210});
					$editor.data('extraPageBuilderCustomEditor').disable();
					$editor.data('extraPageBuilderCustomEditor').enable();
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

			//Init editors
			plugin.element.find('.extra-custom-editor-wrapper').each(function () {
				if($(this).closest('.wpa_group.tocopy').length) {
					return;
				}
				$(this).extraPageBuilderCustomEditor({height: 210});
			});

			plugin.updateMenu(plugin, true);
			plugin.enableEditors(plugin);
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
							$(this).data('extraPageBuilderCustomEditor').disable();
						});
					}
				},
				stop: function(event, ui) { // re-initialize tinymce when sort is completed
					var item = ui.item,
						$accordion = $(this).closest('.extra-accordion'),
						target = $accordion.find("#"+item.attr("aria-controls")),
						editors = target.find('.extra-editor-processed');

					// move the target
					target.insertAfter(plugin.wrapper.children().eq(item.index()));

					// reset the editors
					if(editors.length) {
						editors.each(function() {
							$(this).data('extraPageBuilderCustomEditor').enable();
						});
					}

					// refresh the tabs
					plugin.wrapper.tabs( "refresh" );
				}
			}).disableSelection();
		},
		enableEditors: function (plugin) {
			plugin.element.find('.extra-accordion-custom-editor-wrapper > .extra-custom-editor-wrapper.extra-editor-processed').each(function () {
				var $currentEditor = $(this);
				$currentEditor.data('extraPageBuilderCustomEditor').enable();
			});
		},
		disableEditors: function (plugin) {
			plugin.element.find('.extra-accordion-custom-editor-wrapper > .extra-custom-editor-wrapper.extra-editor-processed').each(function () {
				var $currentEditor = $(this);
				$currentEditor.data('extraPageBuilderCustomEditor').disable();
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

jQuery(document).ready(function($){
	var $pageBuilder = $('.extra-page-builder');

	$pageBuilder.on('showform.pagebuilder.extra', function (event, $block_type, $block, $form) {
		if ($block_type == 'accordion') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			var $accordion = $form.find('.extra-accordion');

			if ($accordion.data('extraPageBuilderAccordion') === undefined) {
				$accordion.extraPageBuilderAccordion({height: 400});
			}

			$accordion.data('extraPageBuilderAccordion').disableEditors($accordion.data('extraPageBuilderAccordion'));
			extraAdminModal
				.options({footer: ['extra-admin-modal-save'], header: ['extra-admin-modal-title'], size: {height: 737}})
				.show("Modifier l'accordéon",  $form);
			$accordion.data('extraPageBuilderAccordion').enableEditors($accordion.data('extraPageBuilderAccordion'));
		}
	});

	$pageBuilder.on('hideForm.pagebuilder.extra', function (event, block_type, $block, $form) {
		if (block_type == 'accordion') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			var $accordion = $form.find('.extra-accordion'),
				$accordionContent = $block.find('.extra-page-builder-block-content ul.extra-accordion');

			$accordion.data('extraPageBuilderAccordion').disableEditors($accordion.data('extraPageBuilderAccordion'));
			$block.find('.extra-page-builder-block-form').append($form);
			$accordion.data('extraPageBuilderAccordion').enableEditors($accordion.data('extraPageBuilderAccordion'));

			$accordionContent.empty();
			$accordion.find('.wpa_group:not(.tocopy) input.extra-accordion-title').each(function () {
				$accordionContent.append('<li><h3 class="extra-accordion-title">'+$(this).val()+'</h3></li>');
			});

			$block.find('.accordeon-title').html($form.find('.title').val());
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

			$block.find('.accordeon-title').html($form.find('.title').val());
		}
	});
});

