<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Map
 *
 * Define a slider and his text input
 *
 * type = slider
 *
 * Options :
 * - name (required)
 * - label (optional): label before text input
 * - icon (optional)
 * - max (required): max value
 */
class Slider extends AbstractField {

	protected $suffix;
	protected $max;

	public static function init () {
		parent::init();
		wp_enqueue_style('extra-slider-range-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-slider-range.less');
		wp_enqueue_script('jquery-ui');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('extra-slider-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-slider.js', array('jquery-ui-slider'), null, true);
	}

	public function the_admin() {
		?>
		<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
		<?php $this->mb->the_field($this->get_single_field_name('slider')); ?>
		<div class="extra-slider-container <?php echo $this->css_class; ?>" data-max="<?php echo $this->max; ?>">
			<p>
				<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
				<input class="extra-slider-input" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="text" value="<?php $this->mb->the_value(); ?>"/> <?php echo ($this->suffix == null) ? '' : $this->suffix; ?>
			</p>
			<div class="extra-slider"></div>
		</div>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->suffix = (isset($properties['suffix'])) ? $properties['suffix'] : null;
		$this->max = (isset($properties['max'])) ? $properties['max'] : null;

		if (empty($this->max)) throw new Exception('Extra Meta box "max" required for Slider');
	}

	public function the_admin_column_value() {
		//TODO
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		echo $meta;
	}
} 