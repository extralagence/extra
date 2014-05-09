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
//		wp_enqueue_script('extra-page-builder-block-custom-editor', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Accordion/js/accordion.js', array('jquery', 'quicktags'), null, true);

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

						<?php $this->mb->the_field('section_title_'.$name); ?>
						<label for="<?php $this->mb->the_name(); ?>"><?php _e("Titre de la section"); ?></label>
						<input
							class="extra-accordion-title"
							id="<?php $this->mb->the_name(); ?>"
							type="text"
							name="<?php $this->mb->the_name(); ?>"
							value="<?php $this->mb->the_value(); ?>">
						<br>

						<?php $this->mb->the_field('section_content_'.$name); ?>
						<textarea
							class="extra-accordion-content"
							name="<?php $this->mb->the_name(); ?>">
							<?php echo apply_filters('the_content', html_entity_decode( $this->mb->get_the_value(), ENT_QUOTES, 'UTF-8' )); ?>
						</textarea>

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
		//echo '<div class="custom-editor-content">'.apply_filters('the_content', html_entity_decode( $this->mb->get_the_value(), ENT_QUOTES, 'UTF-8' )).'</div>';
	}

	public static function get_front($block_data, $name_suffix) {
		parent::get_front($block_data, $name_suffix);

		$html = apply_filters('the_content', html_entity_decode( $block_data[$name_suffix], ENT_QUOTES, 'UTF-8' ));;

		return $html;
	}
}