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
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - format (optional): php date format, ex: yy-mm-dd
 * - required (optional): true|false
 * - error_label (optional): label displayed in case of error
 */
class Date extends AbstractField {

	protected $format;
	protected $time_format;
	protected $required;
	protected $error_label;

	public static function init () {
		parent::init();
		//wp_enqueue_style('jquery-style', EXTRA_INCLUDES_URI . '/extra-metabox/css/jquery-ui-1.9.2.custom.css');
		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('extra-date-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-date.less');

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-slider');

		wp_enqueue_script('jquery-ui-timepicker-addon', EXTRA_INCLUDES_URI . '/extra-metabox/js/jquery-ui-timepicker-addon.js', array('jquery-ui-datepicker'), null, true);
		wp_enqueue_script('jquery-ui-timepicker-fr', EXTRA_INCLUDES_URI . '/extra-metabox/js/jquery-ui-timepicker-fr.js', array('jquery-ui-datepicker', 'jquery-ui-timepicker-addon'), null, true);

		wp_enqueue_script('extra-date-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-date.js', array('jquery-ui-datepicker', 'jquery-ui-timepicker-addon', 'jquery-ui-timepicker-fr'), null, true);
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-date-container <?php echo ($this->required) ? 'required' : '' ?>">

			<?php $this->mb->the_field($this->get_single_field_name('date')); ?>

			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->required) ? ' *' : '' ?><?php echo ($this->label == null) ? _("Date", "extra") : $this->label; ?></label>
			<input
				class="extra-datepicker"
				id="<?php $this->mb->the_name(); ?>"
				name="<?php $this->mb->the_name(); ?>"
				type="text"
				value="<?php $this->mb->the_value(); ?>"
				data-format="<?php echo $this->format; ?>"
				data-time-format="<?php echo $this->time_format; ?>"
				/>

			<span class="extra-error-message" style="display: none;">
				<?php echo ($this->error_label == null) ? _("Ce champs est requis", "extra") : $this->error_label; ?>
			</span>
			<?php $this->mb->the_field($this->get_prefixed_field_name('en', '-')); ?>
			<input class="extra-datepicker-en" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" />
		</div>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);

		$this->format = (isset($properties['format'])) ? $properties['format'] : null ;
		$this->time_format = (isset($properties['time_format'])) ? $properties['time_format'] : null;
		$this->required = (isset($properties['required'])) ? $properties['required'] == true : false;
		$this->error_label = (isset($properties['error_label'])) ? $properties['error_label'] : null;

		if ($this->format == null) {
			$this->format = 'yy-mm-dd';
		}
		if ($this->time_format == null) {
			$this->time_format = '';
		}
	}
	public function the_admin_column_value() {
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		if (empty($meta)) {
			echo '-';
		} else {
			echo $meta;
		}
	}
} 