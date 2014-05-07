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
	var pluginName = "extraPageBuilderMapAdmin",
		defaults = {};

	// The actual plugin constructor
	function Plugin ( element, options ) {
		this.element = $(element);
		this.mapElement = this.element;
		if(!this.element.hasClass('extra-map')) {
			this.mapElement = this.element.find('.extra-map');
		}
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
			if (!this.mapElement.hasClass('extra-map-processed')) {
				if(this.mapElement.parents('.wpa_group.tocopy').length) {
					return;
				}

				/***********************
				 *
				 *
				 * VARS
				 *
				 *
				 ***********************/
				google.maps.visualRefresh = true;
				this.geocoder = new google.maps.Geocoder();
				this.mapContainer = this.mapElement.find(".map-container");
				this.address = this.mapElement.find(".address");
				this.lat = this.mapElement.find(".lat");
				this.lon = this.mapElement.find(".lon");

				this.mapElement.addClass("extra-map-processed");

				/***********************
				 *
				 *
				 * MAP
				 *
				 *
				 ***********************/
				this.mapOptions = {
					center: new google.maps.LatLng(this.lat.val(), this.lon.val()),
					zoom: 15,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				this.map = new google.maps.Map(this.mapContainer[0], this.mapOptions);
				/***********************
				 *
				 *
				 * MARKER
				 *
				 *
				 ***********************/
				this.markerLatLng = new google.maps.LatLng(this.lat.val(), this.lon.val());
				this.marker = new google.maps.Marker({
					position: this.markerLatLng,
					map: this.map,
					draggable:true
				});

				/***********************
				 *
				 *
				 * EVENTS POUR LE MARKER
				 *
				 *
				 ***********************/
				var $this = this;

				google.maps.event.addListener(this.map, "click", function(event){
					$this.marker.setPosition(event.latLng);
					$this.updatePosition($this);
				});
				google.maps.event.addListener(this.marker, "dragend", function(){
					$this.updatePosition($this);
				});

				/***********************
				 *
				 *
				 * UPDATE DU MARKER
				 *
				 *
				 ***********************/

				if(this.address.val() == "") {
					this.updatePosition($this);
				}

				/***********************
				 *
				 *
				 * ADDRESS BUTTON
				 *
				 *
				 ***********************/
				this.addressBtn = $("<a>Afficher sur la carte</a>").attr({
					"href":"#",
					"class":"addressBtn button"
				}).insertAfter(this.address);
				this.addressMsg = $("<span />").attr({
					"class":"addressMsg"
				}).insertAfter(this.addressBtn);
				/***********************
				 *
				 *
				 * UPDATE DU CHAMP DE TEXTE DE L'ADRESSE
				 *
				 *
				 ***********************/
				this.addressBtn.click(function(){
					$this.updateAddress($this);
					return false;
				});
			}
		},
		updatePosition: function (plugin) {
			// some logic
			var latlng = plugin.marker.getPosition();
			plugin.lat.val(latlng.lat());
			plugin.lon.val(latlng.lng());
		},
		updateAddress: function (plugin) {
			plugin.geocoder.geocode({'address': plugin.address.val()}, function(results, status) {
				if(status == "OK" && results && results[0]) {
					plugin.lat.val(results[0].geometry.location.lat());
					plugin.lon.val(results[0].geometry.location.lng());
					var latLng = new google.maps.LatLng(plugin.lat.val(), plugin.lon.val());
					plugin.marker.setPosition(latLng);
					plugin.map.panTo(latLng);
					plugin.map.setZoom(15);
					plugin.addressMsg.text("");
				} else {
					plugin.addressMsg.text(status);
				}
			});
		},
		resize: function () {
			google.maps.event.trigger(this.map,'resize');
			this.map.setCenter(this.marker.getPosition());
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


jQuery(document).ready(function($) {
	var $pageBuilder = $('.extra-page-builder');
	$pageBuilder.on('showform.pagebuilder.extra', function (event, $block_type, $block, $form) {
		if ($block_type == 'map') {
			// We stop propagation to change default behavior
			event.stopPropagation();

			var $map = $form.find('.extra-map-wrapper');

			if ($map.data('extraPageBuilderMapAdmin') === undefined) {
				$map.extraPageBuilderMapAdmin({height: 400});
			}

			extraAdminModal
				.options({footer: ['extra-admin-modal-save'], header: ['extra-admin-modal-title'], size: {width: 800, height: 760}})
				.show('Modifier le bloc',  $form);

			$map.data('extraPageBuilderMapAdmin').resize();
		}
	});

	$pageBuilder.on('refreshPreview.pagebuilder.extra', function (event, $block_type, $block, $form) {
		var $map = $block.find('.extra-page-builder-map'),
			plugin = $map.data('extraPageBuilderMapFront');
		$map.data('lat', $form.find('.lat').val());
		$map.data('lon', $form.find('.lon').val());

		if (plugin != null) {
			plugin.refresh();
		}
	});
});