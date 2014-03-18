<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

class Tabs extends Field {

	protected $subfields;
	protected $label;
	protected $add_label;
	protected $delete_label;
	protected $bloc_label;

	public static function init () {
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('extra-tabs-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-tabs.js', array('jquery'), null, true);
	}

	public function the_admin($bloc_classes) {
		?>
		<div class="bloc <?php echo $bloc_classes; ?>">
			<?php if ($this->label != null) : ?>
				<h2><?php echo $this->label; ?></h2>
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
		$this->label = $properties['label'];
		$this->add_label = $properties['add_label'];
		$this->bloc_label = $properties['bloc_label'];
		$this->delete_label = $properties['delete_label'];
		$this->subfields = $properties['subfields'];

		if (empty($this->subfields)) throw new Exception('Extra Meta box "subfields" required');
	}

	/************************
	 *
	 * GETTERS AND SETTERS
	 *
	 ***********************/

	/**
	 * @param mixed $label
	 */
	public function setLabel( $label ) {
		$this->label = $label;
	}

	/**
	 * @return mixed
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @param mixed $add_label
	 */
	public function setAddLabel( $add_label ) {
		$this->add_label = $add_label;
	}

	/**
	 * @return mixed
	 */
	public function getAddLabel() {
		return $this->add_label;
	}

	/**
	 * @param mixed $delete_label
	 */
	public function setDeleteLabel( $delete_label ) {
		$this->delete_label = $delete_label;
	}

	/**
	 * @return mixed
	 */
	public function getDeleteLabel() {
		return $this->delete_label;
	}

	/**
	 * @param mixed $bloc_label
	 */
	public function setBlocLabel( $bloc_label ) {
		$this->bloc_label = $bloc_label;
	}

	/**
	 * @return mixed
	 */
	public function getBlocLabel() {
		return $this->bloc_label;
	}

	/**
	 * @param mixed $subfields
	 */
	public function setSubfields( $subfields ) {
		$this->subfields = $subfields;
	}

	/**
	 * @return mixed
	 */
	public function getSubfields() {
		return $this->subfields;
	}
} 