<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Range
 *
 * Define a slider with 2 handles and respectively 2 text inputs
 *
 * type = range
 *
 * Options :
 * - name (required)
 * - label (optional): useless
 * - icon (optional): useless
 * - max (required): max value
 * - label_min (optional): label before minimum text input
 * - suffix_min (optional): text after minimum text input
 * - label_max (optional): label before maximum text input
 * - suffix_max (optional): text after maximum text input
 */
class Range extends AbstractField {

	protected $label_min;
	protected $suffix_min;
	protected $label_max;
	protected $suffix_max;
	protected $max;

	public static function init () {
		parent::init();
		wp_enqueue_style('extra-slider-range-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-slider-range.less');
		wp_enqueue_script('jquery-ui');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('extra-range-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-range.js', array('jquery-ui-slider'), null, true);
	}

	public function the_admin() {
		?>
		<div class="extra-range-container <?php echo $this->css_class; ?>" data-max="<?php echo $this->max; ?>">
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
		parent::extract_properties($properties);
		$this->label_min = $properties['label_min'];
		$this->suffix_min = $properties['suffix_min'];
		$this->label_max = $properties['label_max'];
		$this->suffix_max = $properties['suffix_max'];
		$this->max = $properties['max'];

		if (empty($this->max)) throw new Exception('Extra Meta box "max" required for Range');
	}

	public function the_admin_column_value() {
		//TODO
		echo '-';
	}
} 