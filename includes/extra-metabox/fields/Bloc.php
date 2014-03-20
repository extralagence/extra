<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Bloc
 *
 * Define a bloc metabox (Only formatting)
 *
 * Options for Bloc :
 * - name (optional): useless for bloc,
 * - subfields (mandatory): child fields for bloc,
 * - label (optional): bloc's title,
 * - icon (optional): bloc's icon
 */
class Bloc extends Field {

	protected $subfields;
	protected $label;
	protected $icon;

	public static function init () {
	}

	function __construct(ExtraMetaBox $mb, $name) {
		parent::__construct($mb, $name);

		if ($this->name == null || empty($this->name)) {
			$this->name = 'bloc';
		}
	}
	public function the_admin($bloc_classes) {
		?>
		<div class="bloc <?php echo $bloc_classes; ?>">
			<?php if ($this->label != null) : ?>
				<h2><?php
					echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
					echo $this->label; ?></h2>
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
		$this->icon = $properties['icon'];

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