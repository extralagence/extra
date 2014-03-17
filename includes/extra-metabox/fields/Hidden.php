<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

class Hidden extends Field {

	public static function init () {
	}

	public function the_admin($bloc_classes) {
		?>
		<?php $this->mb->the_field($this->get_single_field_name('hidden')); ?>
		<input <?php echo (empty($bloc_classes))? '' : 'class="'.$bloc_classes.'"'; ?> id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" >
	<?php
	}

	public function extract_properties($properties) {
	}
} 