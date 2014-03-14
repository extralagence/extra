<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

class Textarea extends Field {

	protected $label;

	public static function init () {
	}

	public function the_admin($bloc_classes) {
		?>
		<div class="<?php echo $bloc_classes; ?>">
			<p>
				<?php $this->mb->the_field($this->get_single_field_name('text')); ?>
				<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
				<textarea id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>"><?php $this->mb->the_value(); ?></textarea></p>
			<p/>
		</div>
	<?php
	}

	public function get_data() {
		return $this->mb->get_the_value($this->get_single_field_name('text'));
	}

	public function extract_properties($properties) {
		$this->label = $properties['label'];
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
} 