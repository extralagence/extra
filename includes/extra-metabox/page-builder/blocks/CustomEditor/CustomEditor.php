<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

namespace ExtraPageBuilder\Blocks;

use ExtraPageBuilder\AbstractBlock;

/**
 * Class CustomEditor
 *
 * Define a Custom Editor block
 *
 * type = custom_editor
 *
 * Options :
 * - name (required)
 */
class CustomEditor extends AbstractBlock {

	public static function init () {
		parent::init();

		wp_enqueue_style('extra-page-builder-block-custom-editor', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/css/custom-editor.less');
		wp_enqueue_script('extra-page-builder-block-custom-editor', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/custom-editor.js', array('jquery', 'quicktags'), null, true);

//		wp_enqueue_script('extra-page-builder-block-custom-editor-browser', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/jquery.browser.js', array('jquery'), null, true);
//		wp_enqueue_script('extra-page-builder-block-custom-editor-iframe-auto-height', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/jquery.iframe-auto-height.js', array('jquery', 'extra-page-builder-block-custom-editor-browser'), null, true);
//
//		wp_enqueue_script('extra-page-builder-block-custom-editor', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/custom-editor.js', array('jquery', 'quicktags', 'extra-page-builder-block-custom-editor-iframe-auto-height'), null, true);
	}

	public function the_admin($name_suffix) {
		$name = $name_suffix;
		?>
		<div class="extra-custom-editor-wrapper">

			<?php
			// SETUP
			$this->mb->the_field($name);
			$editor_id = $this->mb->get_the_name();
			$editor_id = str_replace('[', '_', $editor_id);
			$editor_id = str_replace(']', '-', $editor_id);
			?>

			<div id="wp-<?php echo $editor_id; ?>-wrap" class="wp-core-ui wp-editor-wrap tmce-active extra-custom-editor">

				<div id="wp-<?php echo $editor_id; ?>-editor-tools" class="wp-editor-tools hide-if-no-js">

					<?php
					if (!function_exists('media_buttons')) {
						include(ABSPATH . 'wp-admin/includes/media.php');
					}
					?>

					<div id="wp-<?php echo $editor_id; ?>-media-buttons" class="wp-media-buttons">
						<?php do_action( 'media_buttons', $editor_id ); ?>
					</div>

					<div class="wp-editor-tabs">
						<a id="<?php echo $editor_id; ?>-html" class="wp-switch-editor switch-html" onclick="switchEditors.switchto(this);"><?php _e("Text"); ?></a>
						<a id="<?php echo $editor_id; ?>-tmce" class="wp-switch-editor switch-tmce" onclick="switchEditors.switchto(this);"><?php _e("Visual"); ?></a>
					</div>
				</div>
				<?php
				if(isset($this->custom_css) && !empty($this->custom_css)) {
					$stylesheets = $this->extract_stylesheets($this->custom_css, $name);
				}
				?>

				<div id="wp-<?php echo $editor_id; ?>-editor-container" class="wp-editor-container">
					<textarea
						class="wp-editor-area extra-custom-editor"
						<?php if(isset($stylesheets)): ?>
							data-custom-css="<?php echo $stylesheets; ?>"
						<?php endif; ?>
						data-extra-name="<?php
						echo $name;
						echo (isset($this->editor_class) && !empty($this->editor_class)) ? ' ' . $this->editor_class : '';
						?>"
						id="<?php echo $editor_id; ?>"
						name="<?php $this->mb->the_name(); ?>">
						<?php echo apply_filters('the_content', html_entity_decode( $this->mb->get_the_value(), ENT_QUOTES, 'UTF-8' )); ?>
					</textarea>
				</div>
			</div>
			<table class="post-status-info">
				<tbody>
				<tr>
					<td data-id="<?php echo $editor_id; ?>" id="<?php echo $editor_id; ?>-resize-handle" class="content-resize-handle hide-if-no-js"><br /></td>
				</tr>
				</tbody>
			</table>
		</div>
		<?php
	}

	private function extract_stylesheets($custom_css, $name) {
		$stylesheets = array();
		$counter = 0;
		$less = \wp_less::instance();
		if(is_array($custom_css)) {
			foreach($custom_css as $css) {
				$path = pathinfo($css);
				if($path['extension'] === 'less') {
					$css = $less->parse_stylesheet($css, $name . '-' . $counter);
				}
				$stylesheets[] = $css;
				$counter++;
			}
		} else {
			$path = pathinfo($custom_css);
			if($path['extension'] === 'less') {
				$custom_css = $less->parse_stylesheet($custom_css, $name);
			}
			$stylesheets[] = $custom_css;
		}
		$stylesheets = implode(',', $stylesheets);
		return $stylesheets;
	}

	public function the_preview($name_suffix) {
		$name = $name_suffix;
		$this->mb->the_field($name);

		echo apply_filters('the_content', html_entity_decode( $this->mb->get_the_value(), ENT_QUOTES, 'UTF-8' ));

//		echo '<iframe class="extra-page-builder-block-custom-editor-content" width="100%"></iframe>';
//		wp_localize_script('extra-page-builder-block-custom-editor', 'custom_editor_content', apply_filters('the_content', html_entity_decode( $this->mb->get_the_value(), ENT_QUOTES, 'UTF-8' )));
	}

	public static function get_front($block_data, $name_suffix) {
		parent::get_front($block_data, $name_suffix);

		$html = apply_filters('the_content', html_entity_decode( $block_data[$name_suffix], ENT_QUOTES, 'UTF-8' ));;

		return $html;
	}
}