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
 * Class Tabs
 *
 * Define a tabs block
 *
 * type = tabs
 */
class Tabs extends AbstractBlock {

	public static function init () {
		parent::init();

		wp_enqueue_style('extra-page-builder-block-tabs-admin', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Tabs/css/tabs-admin.less');
		wp_enqueue_script('jquery-ui-tabs');


		wp_enqueue_script(
			'extra-page-builder-block-custom-editor-plugin',
			EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/CustomEditor/js/custom-editor-plugin.js',
			array('jquery', 'quicktags'),
			null,
			true
		);

		wp_enqueue_script(
			'extra-page-builder-block-tabs-admin',
			EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Tabs/js/tabs-admin.js',
			array('jquery', 'extra-page-builder-block-custom-editor-plugin'),
			null,
			true
		);
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		if (empty($this->add_icon)) {
			$this->add_icon = 'icon-extra-page-builder-tabs';
		}
		if (empty($this->add_label)) {
			$this->add_label = __("Onglets", "extra-admin");
		}
	}

	public function the_admin($name_suffix) {
		$name = $name_suffix;
		?>
		<div class="extra-tabs extra-tabs">
			<!-- TITLE -->
			<?php $this->mb->the_field('title_'.$name_suffix); ?>
			<label for="<?php $this->mb->the_name(); ?>"><?php _e("Titre des onglets", "extra-admin"); ?></label>
			<input class="title" type="text" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" value="<?php echo $this->mb->get_the_value(); ?>"/>

			<div class="repeatable">

				<div class="repeat-actions">
					<a href="#" class="docopy-<?php echo $name; ?> copy-btn"><div class="dashicons dashicons-plus"></div><?php _e("Ajouter un onglet", "extra"); ?></a>
					<a href="#" class="dodelete-<?php echo $name; ?> delete-btn"><div class="icon-extra-page-builder icon-extra-page-builder-cross"></div><?php _e("Tout supprimer", "extra"); ?></a>
				</div>

				<?php while($this->mb->have_fields_and_multi($name)): ?>
					<?php $this->mb->the_group_open(); ?>
					<div class="bloc">

						<h2><?php _e("Onglet", "extra-admin"); ?></h2>
						<a href="#" class="dodelete"><span class="icon-extra-page-builder icon-extra-page-builder-cross"></span></a>

						<?php $this->mb->the_field('section_title'); ?>
						<label for="<?php $this->mb->the_name(); ?>"><?php _e("Titre de l'onglet"); ?></label>
						<input
							class="extra-tabs-title"
							id="<?php $this->mb->the_name(); ?>"
							type="text"
							name="<?php $this->mb->the_name(); ?>"
							value="<?php $this->mb->the_value(); ?>">
						<br>

						<div class="extra-tabs-custom-editor-wrapper">
							<?php
							$editor_name = 'section_content';
							$custom_editor = new CustomEditor($this->mb, 'custom_editor');
							$custom_editor->the_admin($editor_name);
							?>
						</div>
					</div>
					<?php $this->mb->the_group_close(); ?>
				<?php endwhile; ?>
			</div>
		</div>
		<?php
	}

	public function the_preview($name_suffix) {
		$name = $name_suffix;
		$lines = $this->mb->get_the_value($name);
		$title = $this->mb->get_the_value('title_'.$name_suffix);

		$html = '';
		if (!empty($title)) {
			$html .= '<h2 class="tabs-title">'.$title.'</h2>';
		}
		$html .= '<ul class="extra-tabs">';
		if ($lines != null && $lines ) {
			foreach ($lines as $line) {
				$html .= '<li>';
				$html .= '<h3 class="extra-tabs-title">'.$line['section_title'].'</h3>';
				$html .= '</li>';
			}
			$html .= '</ul>';
		}
		echo $html;
	}

	public static function get_front($block_data, $name_suffix, $block_height) {
		$lines = $block_data[$name_suffix];
		$title = $block_data['title_'.$name_suffix];

		$html = '';
		if (!empty($title)) {
			$html .= 	'	<h2 class="tabs-title">'.$title.'</h2>';
		}
		$html .= '	<div class="tabs-wrapper">';
		foreach ($lines as $line) {
			$html .= '	<div class="tabs-element">';
			$html .= '		<h3 class="tab-title">'.$line['section_title'].'</h3>';
			$html .= '		<div class="tab-content">';
			$html .= '			<div class="inner">'.apply_filters('the_content', html_entity_decode( $line['section_content'], ENT_QUOTES, 'UTF-8' )).'</div>';
			$html .= '		</div>';
			$html .= '	</div>';
		}
		$html .= '	</div>';

		return $html;
	}
}