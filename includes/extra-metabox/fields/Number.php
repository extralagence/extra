<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Text
 *
 * Define a simple number input metabox
 *
 * type = number
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - placeholder (optional): label when the field is empty
 * - regex (optional): regex checked for each changes
 */
class Number extends AbstractField {

	protected $suffix;
	protected $regex;
	protected $placeholder;

	public static function init () {
		parent::init();
		wp_enqueue_script('extra-text-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-text.js', array('jquery'), null, true);
	}

	public function the_admin() {
		?>
		<p class="<?php echo $this->css_class; ?> extra-text-container">
			<?php $this->mb->the_field($this->get_single_field_name('text')); ?>
			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<input
				class="extra-text-input"
				id="<?php $this->mb->the_name(); ?>"
				name="<?php $this->mb->the_name(); ?>"
				type="number"
				value="<?php $this->mb->the_value(); ?>"
				<?php echo ($this->regex != null) ? 'data-regex="'.$this->regex.'"' : ''; ?>
				<?php echo ($this->placeholder != null) ? 'placeholder="'.$this->placeholder.'"' : ''; ?>>
            <?php echo ($this->suffix == null) ? '' : $this->suffix; ?>
		</p>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->suffix = isset($properties['suffix']) ? $properties['suffix'] : null;
		$this->regex = isset($properties['regex']) ? $properties['regex'] : null;
		$this->placeholder = isset($properties['placeholder']) ? $properties['placeholder'] : null;
	}

	public function the_admin_column_value() {
		//TODO
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		echo $meta;
	}
}