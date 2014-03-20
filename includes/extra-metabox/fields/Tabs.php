<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Tabs
 *
 * Define a tabs metabox
 *
 * type = tabs
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - subfields (required): children fields
 * - add_label (optional): label for the add button
 * - delete_label (optional): label for the delete button
 * - bloc_label (optional): title for each child
 */
class Tabs extends Group {

	protected $add_label;
	protected $delete_label;
	protected $bloc_label;

	public static function init () {
		parent::init();
		wp_enqueue_style('extra-tabs-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-tabs.less');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('extra-tabs-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-tabs.js', array('jquery'), null, true);
	}

	public function the_admin() {
		?>
		<div class="bloc <?php echo $this->css_class; ?>">
			<?php if ($this->label != null) : ?>
				<h2><?php
					echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
					echo $this->label; ?>
				</h2>
			<?php endif; ?>

			<div class="repeatable extra-tabs">

				<div class="repeat-actions">
					<a href="#" class="docopy-<?php echo $this->get_single_field_name("tab"); ?> copy-btn"><div class="dashicons dashicons-plus"></div><?php echo ($this->add_label == null) ? __("Ajouter un onglet", "extra") : $this->add_label; ?></a>
					<a href="#" class="dodelete-<?php echo $this->get_single_field_name("tab"); ?> delete-btn"><div class="dashicons dashicons-dismiss"></div><?php _e("Tout supprimer", "extra"); ?></a>
				</div>

				<?php while($this->mb->have_fields_and_multi($this->get_single_field_name("tab"))): ?>
					<?php $this->mb->the_group_open(); ?>
					<div class="bloc">

						<h2><?php echo ($this->bloc_label == null) ? __("Détail", "extra-admin") : $this->bloc_label; ?></h2>

						<a href="#" class="dodelete"><span class="label"><?php echo ($this->delete_label == null) ? __("Supprimer l'onglet", "extra") : $this->delete_label; ?></span><div class="dashicons dashicons-dismiss"></div></a>

						<?php
						$this->mb->the_admin($this->subfields);
						?>

					</div>
					<?php $this->mb->the_group_close(); ?>
				<?php endwhile; ?>
			</div>
		</div>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->add_label = $properties['add_label'];
		$this->bloc_label = $properties['bloc_label'];
		$this->delete_label = $properties['delete_label'];
	}
}