<?php
/**
 * Class ConditionalMultiple
 *
 * Define multiple conditional inputs metabox
 *
 * type = conditional_multiple
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - type (checkbox or radio) (optional, default radio)
 * - values (array slug => value required)
 * - multiple_subfields (required)
 */
class ConditionalMultiple extends AbstractField {

    protected $input_type;
    protected $values;
    protected $multiple_subfields;

	public static function init() {
		parent::init();
		wp_enqueue_script('extra-conditional-multiple-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-conditional-multiple.js', array('jquery'), null, true);
        wp_enqueue_style('extra-conditional-multiple-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-conditional-multiple.less');
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-conditional-multiple-container">


			<?php $this->mb->the_field($this->get_single_field_name('conditional_multiple')); ?>

			<?php $field_name = $this->mb->get_the_name(); ?>


            <?php if ($this->title != null) : ?>
                <h2><?php
                    echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
                    echo $this->title; ?>
                </h2>
            <?php endif; ?>


            <?php foreach($this->values as $name => $label): ?>

                <?php
	            $this->mb->the_field($this->get_single_field_name('conditional_multiple'));
                if($this->input_type == 'checkbox') {
                    $checked = $this->mb->get_the_value();
                } else {
                    $checked = $this->mb->get_the_value() == $name;
                }
                ?>
                <div class="extra-conditional-multiple-input-container">
                        <label class="extra-conditional-multiple-label" for="<?php echo $field_name; echo '-' . $name; ?>"><?php echo $label; ?></label>
                        <input
                        class="extra-conditional-multiple-input"
                        id="<?php echo $field_name; echo '-' . $name; ?>"
                        name="<?php echo $field_name; ?><?php echo ($this->input_type == 'checkbox') ? '-' . $name : ''; ?>"
                        type="<?php echo $this->input_type; ?>"
                        value="<?php echo ($this->input_type == 'checkbox') ? '1' : $name; ?>"
                        <?php if ($checked) echo ' checked="checked"'; ?>>

                    <div class="extra-conditional-multiple-field"<?php echo ($checked) ? ' style="display:none;"' : ''; ?>>
                        <?php $this->mb->the_admin_from_field($this->multiple_subfields[$name], $this->name_suffix); ?>
                    </div>
                </div>

            <?php endforeach; ?>
		</div>
		<?php
	}

	public function extract_properties( $properties ) {
		parent::extract_properties( $properties );
        $this->input_type = isset($properties['input_type']) ? $properties['input_type'] : 'radio';
        $this->values = $properties['values'];
        $this->multiple_subfields = $properties['multiple_subfields'];

        if ($this->input_type != 'radio' && $this->input_type != 'checkbox') throw new Exception('Extra Meta box can only be of input_type radio or checkbox for'.get_class($this));
        if (!isset($this->values)) throw new Exception('Extra Meta box values properties required for'.get_class($this));
        if (!isset($this->multiple_subfields)) throw new Exception('Extra Meta box multiple_subfields properties required for'.get_class($this));
	}

	public function the_admin_column_value() {
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		echo $meta;
	}
}