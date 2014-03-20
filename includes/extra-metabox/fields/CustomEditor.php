<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class CustomEditor
 *
 * Define an editor metabox (addable to tabs and other group)
 *
 * type = custom_editor
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
class CustomEditor extends Field {

	public static function init () {
		parent::init();
		wp_enqueue_style('extra-editor-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-editor.less');
		wp_enqueue_script('extra-editor-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-editor.js', array('jquery'), null, true);
	}

	public function the_admin() {
		?>
		<div class="extra-custom-editor-wrapper <?php echo $this->css_class; ?>">
			<?php $this->mb->the_field($this->get_single_field_name('editor')); ?>
			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
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
} 