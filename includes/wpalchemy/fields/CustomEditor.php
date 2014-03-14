<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

class CustomEditor extends Field {

	protected $label;

	public static function init () {
		wp_enqueue_script('extra-editor', EXTRA_INCLUDES_URI . '/extra-metabox/extra-editor.js', array('jquery'), null, true);
	}

	public function the_admin($bloc_classes) {
		?>
		<div class="extra-custom-editor-wrapper <?php echo $bloc_classes; ?>">
			<?php $this->mb->the_field($this->get_single_field_name('editor')); ?>
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<div class="extra-custom-editor">
				<div class="wp-editor-tools">
					<a class="hide-if-no-js wp-switch-editor switch-html">HTML</a>
				</div>
				<textarea class="editor-slide" id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>"><?php echo apply_filters('the_content', html_entity_decode( $this->mb->get_the_value(), ENT_QUOTES, 'UTF-8' )); ?></textarea>
			</div>
		</div>
	<?php
	}

	public function get_data() {
		return $this->mb->get_the_value($this->get_single_field_name('editor'));
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