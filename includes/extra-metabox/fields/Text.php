<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

class Text extends Field {

	protected $regex;
	protected $placeholder;

	public static function init () {
		parent::init();
		wp_enqueue_script('extra-text-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-text.js', array('jquery'), null, true);
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-text-container">
			<?php $this->mb->the_field($this->get_single_field_name('text')); ?>
			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<input
				class="extra-text-input"
				id="<?php $this->mb->the_name(); ?>"
				name="<?php $this->mb->the_name(); ?>"
				type="text"
				value="<?php $this->mb->the_value(); ?>"
				<?php echo ($this->regex != null) ? 'data-regex="'.$this->regex.'"' : ''; ?>
				<?php echo ($this->placeholder != null) ? 'placeholder="'.$this->placeholder.'"' : ''; ?>>
		</div>
	<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->regex = $properties['regex'];
		$this->placeholder = $properties['placeholder'];
	}
} 