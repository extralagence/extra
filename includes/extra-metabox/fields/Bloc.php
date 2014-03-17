<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

class Bloc extends Field {

	protected $subfields;
	protected $label;

	public static function init () {
	}

	public function the_admin($bloc_classes) {
		?>
		<div class="bloc <?php echo $bloc_classes; ?>">
			<?php if ($this->label != null) : ?>
				<h2><?php echo $this->label; ?></h2>
			<?php endif; ?>

			<?php
			$this->mb->the_admin($this->subfields);
			?>
		</div>
		<?php
	}

	public function extract_properties($properties) {
		$this->label = $properties['label'];
		$this->subfields = $properties['subfields'];

		if (empty($this->subfields)) throw new Exception('Extra Meta box subfields properties required');
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