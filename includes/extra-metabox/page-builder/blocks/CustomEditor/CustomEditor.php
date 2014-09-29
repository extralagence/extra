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
 */
class CustomEditor extends AbstractBlock {

	public static function init () {
		parent::init();

		wp_enqueue_style('extra-page-builder-block-custom-editor', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/css/custom-editor.less');
//		wp_enqueue_script('extra-page-builder-block-custom-editor', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/custom-editor.js', array('jquery', 'quicktags'), null, true);

		wp_enqueue_script(
			'extra-page-builder-block-custom-editor-plugin',
			EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/custom-editor-plugin.js',
			array('jquery', 'quicktags'),
			null,
			true
		);

		wp_enqueue_script(
			'extra-page-builder-block-custom-editor-iframe-resizer',
			EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/iframeResizer.min.js',
			array('jquery'),
			null,
			true
		);

		wp_enqueue_script(
			'extra-page-builder-block-custom-editor',
			EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/custom-editor.js',
			array('jquery', 'extra-page-builder-block-custom-editor-plugin', 'extra-page-builder-block-custom-editor-iframe-resizer'),
			null,
			true
		);

		wp_localize_script('extra-page-builder-block-custom-editor', 'customEditorParams', array(
          'iframeFileBase' => EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/IframeContent.php',
          'iframeResizerContentWindow' => EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/iframeResizer.contentWindow.js'
        ));
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		if (empty($this->add_icon)) {
			$this->add_icon = 'icon-extra-page-builder-editor';
		}
		if (empty($this->add_label)) {
			$this->add_label = __("Texte", "extra-admin");
		}
	}

	public function the_admin($name_suffix) {
		$name = $name_suffix;
		$this->custom_css = array(
			THEME_URI.'/assets/css/content.less',
			EXTRA_URI.'/includes/extra-metabox/page-builder/blocks/CustomEditor/css/editor-style.less'
		);
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

				<?php
				$textarea_name = $this->mb->get_the_name();
				$content = $this->mb->get_the_value();
				$content = html_entity_decode( $content, ENT_QUOTES, 'UTF-8' );

				$default_editor = wp_default_editor();
				// 'html' is used for the "Text" editor tab.
				if ( 'html' === $default_editor ) {
					add_filter('the_editor_content', 'wp_htmledit_pre');
				} else {
					add_filter('the_editor_content', 'wp_richedit_pre');
				}
				$content = apply_filters( 'the_editor_content', $content );
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
						name="<?php echo $textarea_name ; ?>">
						<?php echo $content; ?>
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

	public function the_preview($name_suffix, $block_width) {
		$name = $name_suffix;
		$this->mb->the_field($name);

		echo '<div class="custom-editor-content">'.apply_filters('the_content', html_entity_decode( $this->mb->get_the_value(), ENT_QUOTES, 'UTF-8' )).'</div>';
	}

	public static function get_front($block_data, $name_suffix, $block_height, $block_width) {
		$html = apply_filters('the_content', $block_data[$name_suffix]);

		return $html;
	}
}