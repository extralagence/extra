<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Checkbox
 *
 * Define a radio button group input metabox
 *
 * type = radio
 *
 * Options :
 *  - radis (required) array of arrays with label and value :
 *
'radios' => array(
	array(
		'value' => 'header',
		'label' => 'En-tête'
	),
	array(
		'value' => 'footer',
		'label' => 'Pied de page'
	)
),
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
class Radio extends AbstractField {

	protected $radios = array();

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?> extra-radio-group-container">
			<?php $this->mb->the_field($this->get_single_field_name('radio')); ?>
			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>

			<?php foreach ($this->radios as $radio) : ?>
			<label for="<?php $this->mb->the_name(); ?>-<?php echo $radio['value'] ?>"><?php echo $radio['label']; ?></label>
			<input
				class="extra-radio-group-input"
				id="<?php $this->mb->the_name(); ?>-<?php echo $radio['value'] ?>"
				type="radio"
				name="<?php $this->mb->the_name(); ?>"
				value="<?php echo $radio['value'] ?>"<?php echo $this->mb->is_value($radio['value'])?' checked="checked"':''; ?>
				>
			<br>
			<?php endforeach; ?>
		</div>
	<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->radios = isset($properties['radios']) ? $properties['radios'] : array();
	}

	public function the_admin_column_value() {
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		if (!empty($meta)) {
			echo $meta;
		} else {
			echo '-';
		}
	}
}