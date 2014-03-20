<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Textarea
 *
 * Define a simple textarea metabox
 *
 * type = textarea
 *
 * Options :
 * - name (mandatory)
 * - label (optional)
 * - icon (optional)
 */
class Textarea extends Field {

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?>">
			<?php $this->mb->the_field($this->get_single_field_name('text')); ?>
			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<textarea id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>"><?php $this->mb->the_value(); ?></textarea>
		</div>
		<?php
	}
} 