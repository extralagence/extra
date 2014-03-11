<?php
/**********************
 *
 *
 *
 * CHANGE GALLERY PARAMETERS
 *
 *
 *
 *********************/
add_action('print_media_templates', 'extra_add_gallery_type');
function extra_add_gallery_type() {
// define your backbone template;
// the "tmpl-" prefix is required, and your input field should have a data-setting attribute matching the shortcode name
	?>
	<script type="text/html" id="tmpl-extra-gallery-type">
		<h3><?php _e('Gallery Settings'); ?></h3>
		<label class="setting" for="extra_gallery_type"><span><?php _e('Type de galerie :', 'extra'); ?></span></label>
		<select id="extra_gallery_type" data-setting="extra_gallery_type">
			<option value="mosaic"> <?php _e('Mosaïque', 'extra-admin'); ?> </option>
			<option value="slider"> <?php _e('Carousel', 'extra-admin'); ?> </option>
		</select>
	</script>

	<script>
		jQuery(document).ready(function () {

			// add your shortcode attribute and its default value to the
			// gallery settings list; $.extend should work as well...
			_.extend(wp.media.gallery.defaults, {
				extra_gallery_type: 'mosaic'
			});

			// merge default gallery settings template with yours
			//wp.media.template('gallery-settings')(view);
			wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
				template: function (view) {
					return wp.media.template('extra-gallery-type')(view);
				}
			});
		});
	</script>
<?php
}
/**********************
 *
 *
 *
 * GALLERY SHORTCODE
 *
 *
 *
 *********************/
function extra_gallery_shortcode() {
	remove_shortcode("gallery");
	add_shortcode('gallery', 'extra_gallery_handler');
}

add_action("init", "extra_gallery_shortcode");
// GALLERY HANDLER
function extra_gallery_handler($atts, $content = null) {

	global $content_width;

	$ids = explode(',', $atts['ids']);
	$type = (!empty($atts['extra_gallery_type']) && $atts['extra_gallery_type'] == 'slider') ? 'slider' : 'mosaic';

	if(empty($ids)) {
		return;
	}

	switch($type) {
		default:
		case 'mosaic':
			$return = '<ul class="extra-mosaic">';
			foreach ($ids as $id):
				$src = wp_get_attachment_image_src($id, 'large');
				$return .= '    <li><a href="'.$src[0].'">';
				$return .= extra_get_responsive_image($id, array(
					'desktop' => array(
						'width' => apply_filters('extra_gallery_slider_desktop_width', $content_width/3),
						'height' => apply_filters('extra_gallery_slider_desktop_height', $content_width/3)
					),
					'tablet' => array(
						'width' => apply_filters('extra_gallery_slider_tablet_width', $content_width/3),
						'height' => apply_filters('extra_gallery_slider_tablet_height', $content_width/3)
					),
					'mobile' => array(
						'width' => apply_filters('extra_gallery_slider_mobile_width', $content_width/3),
						'height' => apply_filters('extra_gallery_slider_mobile_height', $content_width/3)
					)
				));
				$return .= '    </a></li>';
			endforeach;
			$return .= '</ul>';
			break;
		case 'slider':
			$return =  '<div class="extra-slider">';
			$return .= ' <div class="wrapper">';
			$return .= '     <ul>';
			foreach ($ids as $id):
				$src = wp_get_attachment_image_src($id, 'large');
				$return .= '        <li><a href="'.$src[0].'">';
				$return .= extra_get_responsive_image($id, array(
					'desktop' => array(
						'width' => apply_filters('extra_gallery_mosaic_desktop_width', $content_width),
						'height' => apply_filters('extra_gallery_mosaic_desktop_height', 300)
					),
					'tablet' => array(
						'width' => apply_filters('extra_gallery_mosaic_tablet_width', 960),
						'height' => apply_filters('extra_gallery_mosaic_tablet_height', 300)
					),
					'mobile' => array(
						'width' => apply_filters('extra_gallery_mosaic_mobile_width', 690),
						'height' => apply_filters('extra_gallery_mosaic_mobile_height', 300)
					)
				));
				$return .= '        </a></li>';
			endforeach;
			$return .= '     </ul>';
			$return .= ' </div>';
			$return .= apply_filters('extra_gallery_mosaic_navigation', '<div class="navigation"><a class="prev" href="#">' . __('Précédent', 'extra') . '</a><a class="next" href="#">' . __('Suivant', 'extra') . '</a></div>');
			$return .= '</div>';
			break;
	}


	return $return;
}