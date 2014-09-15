<?php
/**
 * Class ColorPicker
 *
 * Define a colorpicker metabox
 *
 * type = color_picker
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
class ColorPicker extends AbstractField {

	public static function init () {
		parent::init();
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_script('extra-color-picker-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-color-picker.js', array('jquery', 'wp-color-picker'), null, true);
	}


	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-text-container">
			<?php $this->mb->the_field($this->get_single_field_name('text')); ?>
			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<input
				class="extra-color-picker-input"
				id="<?php $this->mb->the_name(); ?>"
				name="<?php $this->mb->the_name(); ?>"
				type="text"
				value="<?php $this->mb->the_value(); ?>">
		</div>
	<?php
	}

    public function extract_properties($properties) {
        parent::extract_properties($properties);
        // TODO
    }

    public function the_admin_column_value() {
        // TODO
    }
} 