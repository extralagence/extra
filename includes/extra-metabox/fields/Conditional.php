<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Conditional
 *
 * Define a conditional input metabox
 *
 * type = conditional
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - field_false (required)
 * - field_true (required)
 */
class Conditional extends AbstractField {

	protected $subfields_false;
	protected $subfields_true;

	public static function init() {
		parent::init();
		wp_enqueue_script('extra-conditional-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-conditional.js', array('jquery'), null, true);
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-conditional-container">
			<?php $this->mb->the_field($this->get_single_field_name('text')); ?>
			<?php $checked =$this->mb->get_the_value(); ?>
			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
			<input
				class="extra-conditional-input"
				id="<?php $this->mb->the_name(); ?>"
				name="<?php $this->mb->the_name(); ?>"
				type="checkbox"
				value="1"
				<?php if ($checked) echo ' checked="checked"'; ?>
				>
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>

			<div class="extra-conditional-field-false"<?php echo ($checked) ? ' style="display:none;"' : ''; ?>>
				<?php $this->mb->the_admin($this->subfields_false); ?>
			</div>
			<div class="extra-conditional-field-true"<?php echo ($checked) ? '' : ' style="display:none;"'; ?>>
				<?php $this->mb->the_admin($this->subfields_true); ?>
			</div>
		</div>
		<?php
	}

	public function extract_properties( $properties ) {
		parent::extract_properties( $properties );
		$this->subfields_false = $properties['subfields_false'];
		$this->subfields_true = $properties['subfields_true'];

		if (empty($this->subfields_false)) throw new Exception('Extra Meta box subfields_false properties required for'.get_class($this));
		if (empty($this->subfields_true)) throw new Exception('Extra Meta box subfields_true properties required for'.get_class($this));
	}

	public function the_admin_column_value() {
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		echo $meta;
	}
} 