<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Bloc
 *
 * Define a bloc metabox (Only formatting)
 *
 * type = bloc
 *
 * Options :
 * - name (optional) useless for bloc
 * - subfields (required)
 * - label (optional)
 * - icon (optional)
 */
class Bloc extends Group {

	public function the_admin() {
		?>
		<div class="bloc <?php echo $this->css_class; ?>">
			<?php if ($this->label != null) : ?>
				<h2><?php
					echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
					echo $this->label; ?>
				</h2>
			<?php endif; ?>

			<?php
			$this->mb->the_admin($this->subfields);
			?>
		</div>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		if ($this->name == null || empty($this->name)) {
			$this->name = 'bloc';
		}
	}
} 