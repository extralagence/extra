<?php
/**
 * Class Separator
 *
 * Define a separator with a title
 *
 * type = bloc
 *
 * Options :
 * - name (optional) useless for bloc
 * - title (optional)
 * - icon (optional)
 */
class Separator extends AbstractField {

	public function the_admin() {
		?>
            <hr class="separator" />
			<?php if ($this->title != null) : ?>
				<h2><?php
					echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
					echo $this->title; ?>
				</h2>
			<?php endif; ?>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
        $this->title = isset($properties['title']) ? $properties['title'] : null;
        $this->name = isset($properties['name']) ? $properties['name'] : 'separator';
	}

	public function the_admin_column_value() {
	    // nothing here
	}
}