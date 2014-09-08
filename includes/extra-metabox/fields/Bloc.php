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
 * - title (optional)
 * - icon (optional)
 */
class Bloc extends AbstractGroup {

	public function the_admin() {
		?>
		<div class="bloc <?php echo $this->css_class; ?>">
			<?php if ($this->title != null) : ?>
				<h2><?php
					echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
					echo $this->title; ?>
				</h2>
			<?php endif; ?>

			<?php
			$this->mb->the_admin_from_field($this->subfields, $this->name_suffix);
			?>
		</div>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
        $this->name = isset($properties['name']) ? $properties['name'] : 'bloc';
	}

	public function the_admin_column_value() {
		echo '-';
	}
}