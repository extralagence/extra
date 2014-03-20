<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Date
 *
 * Define a date input metabox
 *
 * type = date
 *
 * Options :
 * - name (mandatory)
 * - label (optional)
 * - icon (optional)
 * - format (optional): php date format, ex: yy-mm-dd
 * - required (optional): true|false
 * - error_label (optional): label displayed in case of error
 */
class Date extends Field {

	protected $format;
	protected $required;
	protected $error_label;

	public static function init () {
		parent::init();
		wp_enqueue_style('extra-date-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-date.less');
		wp_enqueue_script('extra-date-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-date.js', array('jquery'), null, true);
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-date-container <?php echo ($this->required) ? 'required' : '' ?>">
			<?php $this->mb->the_field($this->get_single_field_name('date')); ?>

			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? _("Date", "extra") : $this->label; ?></label>
			<input class="extra-datepicker" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="text" value="<?php $this->mb->the_value(); ?>" />

			<span class="extra-error-message" style="display: none;">
				<?php echo ($this->error_label == null) ? _("Il manque la date", "extra") : $this->error_label; ?>
			</span>
			<?php $this->mb->the_field($this->get_prefixed_field_name('en', '-')); ?>
			<input class="extra-datepicker-en" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" />
		</div>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->format = $properties['format'];
		$this->required = $properties['required'] == true;
		$this->error_label = $properties['error_label'];
	}
} 