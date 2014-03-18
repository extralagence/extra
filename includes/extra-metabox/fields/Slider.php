<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

class Slider extends Field {

	protected $label;
	protected $suffix;
	protected $max;

	public static function init () {
		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

		wp_enqueue_script('jquery-ui');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('extra-slider-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-slider.js', array('jquery-ui-slider'), null, true);
	}

	public function the_admin($bloc_classes) {
		?>
		<?php $this->mb->the_field($this->get_single_field_name('slider')); ?>
		<div class="extra-slider-container <?php echo $bloc_classes; ?>" data-max="<?php echo $this->max; ?>">
			<p>
				<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
				<input class="extra-slider-input" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="text" value="<?php $this->mb->the_value(); ?>"/> <?php echo ($this->suffix == null) ? '' : $this->suffix; ?>
			</p>
			<div class="extra-slider"></div>
		</div>
	<?php
	}

	public function extract_properties($properties) {
		$this->label = $properties['label'];
		$this->suffix = $properties['suffix'];
		$this->max = $properties['max'];

		if (empty($this->max)) throw new Exception('Extra Meta box "max" required');
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
	 * @param mixed $suffix
	 */
	public function setSuffix( $suffix ) {
		$this->suffix = $suffix;
	}

	/**
	 * @return mixed
	 */
	public function getSuffix() {
		return $this->suffix;
	}
} 