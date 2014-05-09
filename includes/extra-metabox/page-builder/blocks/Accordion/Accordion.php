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
class Accordion extends AbstractBlock {

	public static function init () {
		parent::init();

		wp_enqueue_style('extra-page-builder-block-accordion', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Accordion/css/accordion.less');
		wp_enqueue_script('jquery-ui-tabs');

//		wp_enqueue_script(
//			'extra-page-builder-block-custom-editor-iframe-resizer',
//			EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/iframeResizer.js',
//			array('jquery'),
//			null,
//			true
//		);
//
		wp_enqueue_script(
			'extra-page-builder-block-accordion',
			EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Accordion/js/accordion.js',
			array(
				'jquery',
				'quicktags',
				//'extra-page-builder-block-custom-editor-iframe-resizer'
			),
			null,
			true
		);

		//wp_localize_script('extra-page-builder-block-accordion', 'iframeResizerContentWindow', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Accordion/js/iframeResizer.contentWindow.js');
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		if (empty($this->add_icon)) {
			$this->add_icon = 'icon-extra-page-builder-accordion';
		}
		if (empty($this->add_label)) {
			$this->add_label = __("Accordéon", "extra-admin");
		}
	}

	public function the_admin($name_suffix) {
		$name = $name_suffix;

		//var_dump($this->mb->have_fields_and_multi($name));

//		$this->custom_css = array(
//			THEME_URI.'/assets/css/content.less',
//			EXTRA_URI.'/includes/extra-metabox/page-builder/blocks/CustomEditor/css/editor-style.less'
//		);
		?>
		<div class="extra-accordion">
			<div class="repeatable">

				<div class="repeat-actions">
					<a href="#" class="docopy-<?php echo $name; ?> copy-btn"><div class="dashicons dashicons-plus"></div><?php _e("Ajouter une section", "extra"); ?></a>
					<a href="#" class="dodelete-<?php echo $name; ?> delete-btn"><div class="icon-extra-page-builder icon-extra-page-builder-cross"></div><?php _e("Tout supprimer", "extra"); ?></a>
				</div>

				<?php while($this->mb->have_fields_and_multi($name)): ?>
					<?php $this->mb->the_group_open(); ?>
					<div class="bloc">

						<h2><?php _e("Section", "extra-admin"); ?></h2>
						<a href="#" class="dodelete"><span class="icon-extra-page-builder icon-extra-page-builder-cross"></span></a>

						<?php $this->mb->the_field('section_title_'.$name_suffix); ?>
						<label for="<?php $this->mb->the_name(); ?>"><?php _e("Titre de la section"); ?></label>
						<input
							class="extra-accordion-title"
							id="<?php $this->mb->the_name(); ?>"
							type="text"
							name="<?php $this->mb->the_name(); ?>"
							value="<?php $this->mb->the_value(); ?>">
						<br>

<!--						--><?php //$this->mb->the_field('section_content_'.$name_suffix); ?>
<!--						<textarea-->
<!--							class="extra-accordion-content"-->
<!--							name="--><?php //$this->mb->the_name(); ?><!--">-->
<!--							--><?php //echo apply_filters('the_content', html_entity_decode( $this->mb->get_the_value(), ENT_QUOTES, 'UTF-8' )); ?>
<!--						</textarea>-->

						<?php
						$editor_name = 'section_content_'.$name_suffix;
						$this->custom_css = array(
							THEME_URI.'/assets/css/content.less',
						);
						?>
						<div class="extra-accordion-custom-editor-wrapper">

							<?php
							// SETUP
							$this->mb->the_field($editor_name);
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
									$stylesheets = $this->extract_stylesheets($this->custom_css, $editor_name);
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



					</div>
					<?php $this->mb->the_group_close(); ?>
				<?php endwhile; ?>
			</div>
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
		$lines = $this->mb->get_the_value();

		$html = '<ul class="extra-accordion">';
		if ($lines != null && $lines ) {
			foreach ($lines as $line) {
				$html .= '<li>';
				$html .= '<h3 class="extra-accordion-title">'.$line['section_title_'.$name_suffix].'</h3>';
				$html .= '</li>';
			}
			$html .= '</ul>';
		}
		echo $html;
	}

	public static function get_front($block_data, $name_suffix) {
		parent::get_front($block_data, $name_suffix);

		$html = 'Accordion';

		return $html;
	}
}