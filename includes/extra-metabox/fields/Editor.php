<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

class Editor extends Field {

	protected $label;

	public static function init () {
	}

	public function the_admin($bloc_classes) {
		?>
		<div <?php echo (!empty($bloc_classes)) ? ' class="'.$bloc_classes.'"' : ''; ?>>
			<?php if ($this->title != null) : ?>
				<h2><?php echo $this->title; ?></h2>
			<?php endif; ?>

			<?php if ($this->label != null) : ?>
				<p class="label"><?php echo $this->label; ?></p>
			<?php endif; ?>

			<?php $this->mb->the_field($this->get_single_field_name('editor'));
			$value = apply_filters('the_content', html_entity_decode( $this->mb->get_the_value(), ENT_QUOTES, 'UTF-8' ));
			wp_editor($value, $this->mb->get_the_name(), array(
				'textarea_name' => $this->mb->get_the_name(),
				'editor_height' => 800,
				'tinymce' => array(
					'body_class' => $this->name
				)
			)); ?>
		</div>
	<?php
	}

	public function extract_properties($properties) {
		$this->label = $properties['label'];
	}

	/************************
	 *
	 * GETTERS AND SETTERS
	 *
	 ***********************/

	/**
	 * @param mixed $label
	 */
	public function setLabel( $label ) {
		$this->label = $label;
	}

	/**
	 * @return mixed
	 */
	public function getLabel() {
		return $this->label;
	}
} 