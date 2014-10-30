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
 * - subfields_false (required)
 * - subfields_true (required)
 */
class Conditional extends AbstractField {

	protected $subfields_false;
	protected $subfields_true;

	public static function init() {
		parent::init();
		add_action( 'admin_enqueue_scripts', function () {
			wp_enqueue_script( 'extra-conditional-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-conditional.js', array( 'jquery' ), null, true );
		});
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-conditional-container">
			<?php if ($this->title != null) : ?>
				<h2><?php
					echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
					echo $this->title; ?>
				</h2>
			<?php endif; ?>

			<?php $this->mb->the_field($this->get_single_field_name('conditional')); ?>
			<?php $checked =$this->mb->get_the_value(); ?>
			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
            <p>
	            <label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
				<input
					class="extra-conditional-input"
					id="<?php $this->mb->the_name(); ?>"
					name="<?php $this->mb->the_name(); ?>"
					type="checkbox"
					value="1"
					<?php if ($checked) echo ' checked="checked"'; ?>>
            </p>

			<div class="extra-conditional-field-false"<?php echo ($checked) ? ' style="display:none;"' : ''; ?>>
				<?php $this->mb->the_admin_from_field($this->subfields_false, $this->name_suffix); ?>
			</div>
			<div class="extra-conditional-field-true"<?php echo ($checked) ? '' : ' style="display:none;"'; ?>>
				<?php $this->mb->the_admin_from_field($this->subfields_true, $this->name_suffix); ?>
			</div>
		</div>
		<?php
	}

	public function extract_properties( $properties ) {
		parent::extract_properties( $properties );
		$this->subfields_false = $properties['subfields_false'];
		$this->subfields_true = $properties['subfields_true'];

		if (!isset($this->subfields_false)) throw new Exception('Extra Meta box subfields_false properties required for'.get_class($this));
		if (!isset($this->subfields_true)) throw new Exception('Extra Meta box subfields_true properties required for'.get_class($this));
	}

	public function the_admin_column_value() {
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		if ($meta) {
			_e("Oui", "extra-admin");
		} else {
			_e("Non", "extra-admin");
		}
	}
}