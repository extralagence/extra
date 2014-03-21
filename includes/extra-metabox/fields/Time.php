<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * TODO FINISHED
 *
 * Class Time
 *
 * Define a time input metabox
 *
 * type = time
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - format (optional): php time format, ex: H:i
 * - required (optional): true|false
 * - error_label (optional): label displayed in case of error
 */
class Time extends AbstractField {

	protected $format;
	protected $required;
	protected $error_label;

	public static function init () {
		parent::init();

		//wp_enqueue_style('jquery-style', EXTRA_INCLUDES_URI . '/extra-metabox/css/jquery-ui-1.9.2.custom.css');
		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('extra-date-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-date.less');

		wp_enqueue_script('jquery-ui-timepicker');
		wp_enqueue_script('jquery-ui-slider');

		wp_enqueue_script('extra-time-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-time.js', array('jquery-ui-timepicker', 'jquery-ui-slider', 'jquery'), null, true);
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-time-container <?php echo ($this->required) ? 'required' : '' ?>">
			<?php $this->mb->the_field($this->get_single_field_name('time')); ?>

			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? _("Heure", "extra") : $this->label; ?><?php echo ($this->required) ? ' *' : '' ?></label>
			<input
				class="extra-timepicker"
				id="<?php $this->mb->the_name(); ?>"
				name="<?php $this->mb->the_name(); ?>"
				type="text"
				value="<?php $this->mb->the_value(); ?>"
				data-format="<?php echo $this->format; ?>"
				/>

			<span class="extra-error-message" style="display: none;">
				<?php echo ($this->error_label == null) ? _("Ce champs est requis", "extra") : $this->error_label; ?>
			</span>
			<?php $this->mb->the_field($this->get_prefixed_field_name('en', '-')); ?>
			<input class="extra-timepicker-en" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" />
		</div>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->format = $properties['format'];
		$this->required = $properties['required'] == true;
		$this->error_label = $properties['error_label'];

		if ($this->format == null) {
			$this->format = 'h:i';
		}
	}

	public function the_admin_column_value() {
		//TODO
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		echo $meta;
	}
} 