<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Group
 *
 * Define a group metabox (Only formatting)
 *
 * type = group
 *
 * Options :
 * - name (optional) useless for bloc
 * - subfields (required)
 * - label (optional)
 * - label_level
 * - icon (optional)
 */
class Group extends AbstractGroup {

	public function the_admin() {
		?>
		<div class="group <?php echo $this->css_class; ?>">
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
		if ($this->name == null || empty($this->name)) {
			$this->name = 'group';
		}
	}

	public function the_admin_column_value() {
		//TODO
		echo '-';
	}
}