<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

class Text extends Field {

	protected $label;
	protected $regex;

	public static function init () {
		wp_enqueue_script('extra-text-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-text.js', array('jquery'), null, true);
	}

	public function the_admin($bloc_classes) {
		?>
		<div class="<?php echo $bloc_classes; ?> extra-text-container">
			<?php $this->mb->the_field($this->get_single_field_name('text')); ?>
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<input
				class="extra-text-input"
				id="<?php $this->mb->the_name(); ?>"
				name="<?php $this->mb->the_name(); ?>"
				type="text"
				value="<?php $this->mb->the_value(); ?>"
				<?php echo ($this->regex != null) ? 'data-regex="'.$this->regex.'"' : ''; ?> >
		</div>
	<?php
	}

	public function extract_properties($properties) {
		$this->label = $properties['label'];
		$this->regex = $properties['regex'];
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
	 * @param mixed $regex
	 */
	public function setRegex( $regex ) {
		$this->regex = $regex;
	}

	/**
	 * @return mixed
	 */
	public function getRegex() {
		return $this->regex;
	}
} 