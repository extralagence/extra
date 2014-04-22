<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Editor
 *
 * Define an wordpress editor metabox
 *
 * type = editor
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - title (optional): bigger than label
 */
class Editor extends AbstractField {

	protected $title;

	public static function init() {
		parent::init();
        wp_enqueue_script('extra-editor-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-editor.js', array('jquery'), null, true);
	}


	public function the_admin() {
	    
        $editor_id = $this->mb->get_the_name();
        $editor_id = str_replace('[', '_', $editor_id);
        $editor_id = str_replace(']', '-', $editor_id);
        $this->css_class = 'extra-editor-wrapper ' . $this->css_class;
        
		?>
		<div <?php echo (!empty($this->css_class)) ? ' class="'.$this->css_class.'"' : ''; ?>>
			<?php if ($this->title != null) : ?>
				<h2><?php
					echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
					echo $this->title; ?>
				</h2>
			<?php endif; ?>

			<?php if ($this->label != null) : ?>
				<p class="label"><?php echo $this->label; ?></p>
			<?php endif; ?>

			<?php $this->mb->the_field($this->get_single_field_name('editor'));
			$value = apply_filters('the_content', html_entity_decode( $this->mb->get_the_value(), ENT_QUOTES, 'UTF-8' ));
			wp_editor($value, $editor_id, array(
				'textarea_name' => $this->mb->get_the_name(),
				'editor_height' => 500,
				'tinymce' => array(
					'body_class' => $this->name
				)
			)); ?>
            
            <table class="post-status-info">
                <tbody>
                    <tr>
                        <td data-id="<?php echo $editor_id; ?>" id="<?php echo $editor_id; ?>-resize-handle" class="content-resize-handle hide-if-no-js"><br /></td>
                    </tr>
                </tbody>
            </table>
		</div>
		<?php
	}

	public function extract_properties( $properties ) {
		parent::extract_properties( $properties );
		$this->title = isset($properties['title']) ? $properties['title'] : null;
	}

	public function the_admin_column_value() {
		//TODO
		echo '-';
	}
} 