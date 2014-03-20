<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Hidden
 *
 * Define a hidden field metabox
 *
 * type = hidden
 *
 * Options :
 * - name (mandatory)
 * - label (optional)
 * - icon (optional)
 */
class Hidden extends Field {

	public function the_admin() {
		?>
		<?php $this->mb->the_field($this->get_single_field_name('hidden')); ?>
		<input <?php echo (empty($this->css_class))? '' : 'class="'.$this->css_class.'"'; ?> id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" >
	<?php
	}
} 