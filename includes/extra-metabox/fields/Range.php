<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

class Range extends Field {

	protected $label_min;
	protected $suffix_min;
	protected $label_max;
	protected $suffix_max;
	protected $max;

	public static function init () {
		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

		wp_enqueue_script('jquery-ui');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('extra-range-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-range.js', array('jquery-ui-slider'), null, true);
	}

	public function the_admin($bloc_classes) {
		?>
		<div class="extra-range-container <?php echo $bloc_classes; ?>" data-max="<?php echo $this->max; ?>">
			<p class="extra-range-min">
				<?php $this->mb->the_field($this->get_prefixed_field_name('min')); ?>
				<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label_min == null) ? $this->name : $this->label_min; ?></label>
				<input class="extra-range-input extra-range-input-min" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="text" value="<?php $this->mb->the_value(); ?>"/> <?php echo ($this->suffix_min == null) ? '' : $this->suffix_min; ?>
			</p>
			<p class="extra-range-max">
				<?php $this->mb->the_field($this->get_prefixed_field_name('max')); ?>
				<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label_max == null) ? $this->name : $this->label_max; ?></label>
				<input class="extra-range-input extra-range-input-max" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="text" value="<?php $this->mb->the_value(); ?>"/> <?php echo ($this->suffix_max == null) ? '' : $this->suffix_max; ?>
			</p>
			<div class="extra-range"></div>
		</div>
	<?php
	}

	public function extract_properties($properties) {
		$this->label_min = $properties['label_min'];
		$this->suffix_min = $properties['suffix_min'];
		$this->label_max = $properties['label_max'];
		$this->suffix_max = $properties['suffix_max'];
		$this->max = $properties['max'];

		if (empty($this->max)) throw new Exception('Extra Meta box "max" required');
	}

	/************************
	 *
	 * GETTERS AND SETTERS
	 *
	 ***********************/

	/**
	 * @param mixed $label_max
	 */
	public function setLabelMax( $label_max ) {
		$this->label_max = $label_max;
	}

	/**
	 * @return mixed
	 */
	public function getLabelMax() {
		return $this->label_max;
	}

	/**
	 * @param mixed $label_min
	 */
	public function setLabelMin( $label_min ) {
		$this->label_min = $label_min;
	}

	/**
	 * @return mixed
	 */
	public function getLabelMin() {
		return $this->label_min;
	}

	/**
	 * @param mixed $max
	 */
	public function setMax( $max ) {
		$this->max = $max;
	}

	/**
	 * @return mixed
	 */
	public function getMax() {
		return $this->max;
	}

	/**
	 * @param mixed $suffix_max
	 */
	public function setSuffixMax( $suffix_max ) {
		$this->suffix_max = $suffix_max;
	}

	/**
	 * @return mixed
	 */
	public function getSuffixMax() {
		return $this->suffix_max;
	}

	/**
	 * @param mixed $suffix_min
	 */
	public function setSuffixMin( $suffix_min ) {
		$this->suffix_min = $suffix_min;
	}

	/**
	 * @return mixed
	 */
	public function getSuffixMin() {
		return $this->suffix_min;
	}
} 