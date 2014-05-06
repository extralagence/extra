/**
 * Created by vincent on 06/05/2014.
 */
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
	var pluginName = "extraPageBuilderMapFront",
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
			if (!this.element.hasClass('extra-map-processed')) {
				if(this.element.parents('.wpa_group.tocopy').length) {
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
				this.lat = this.element.data("lat");
				this.lon = this.element.data("lon");

				this.element.addClass("extra-map-processed");

				/***********************
				 *
				 *
				 * MAP
				 *
				 *
				 ***********************/
				this.mapOptions = {
					center: new google.maps.LatLng(this.lat, this.lon),
					zoom: 15,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				this.map = new google.maps.Map(this.element[0], this.mapOptions);
				/***********************
				 *
				 *
				 * MARKER
				 *
				 *
				 ***********************/
				this.markerLatLng = new google.maps.LatLng(this.lat, this.lon);
				this.marker = new google.maps.Marker({
					position: this.markerLatLng,
					map: this.map,
					draggable:false
				});

				var $this = this;
				this.element.closest('.extra-page-builder-row').on('layoutChange.pagebuilder.extra', function(event, $row, layout) {
					$this.resize($this);
				});

				google.maps.event.addDomListener(window, 'resize', function() {
					$this.resize($this);
				});
			}
		},
		refresh: function () {
			this.lat = this.element.data("lat");
			this.lon = this.element.data("lon");

			this.markerLatLng = new google.maps.LatLng(this.lat, this.lon);
			this.marker.setPosition(this.markerLatLng);

			this.resize(this);
		},
		resize: function (plugin) {
			google.maps.event.trigger(plugin.map,'resize');
			plugin.map.setCenter(plugin.marker.getPosition());
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
	$('.extra-page-builder-map').extraPageBuilderMapFront();
});