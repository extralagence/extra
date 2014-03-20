<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

class Textarea extends Field {

	public static function init () {
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?>">
			<p>
				<?php $this->mb->the_field($this->get_single_field_name('text')); ?>
				<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
				<textarea id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>"><?php $this->mb->the_value(); ?></textarea></p>
			<p/>
		</div>
	<?php
	}
} 