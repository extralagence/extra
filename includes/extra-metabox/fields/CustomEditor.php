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
 * - editor_class (optional)
 */
class CustomEditor extends AbstractField {

	public static function init () {
		parent::init();
		wp_enqueue_style('extra-custom-editor-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-custom-editor.less');
		wp_enqueue_script('extra-custom-editor-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-custom-editor.js', array('jquery', 'quicktags'), null, true);
	}

	public function the_admin() {
		?>
		<div class="extra-custom-editor-wrapper <?php echo $this->css_class; ?>">

			<?php
                // SETUP
    			$this->mb->the_field($this->get_single_field_name('editor'));
    			$editor_id = $this->mb->get_the_name();
                $editor_id = str_replace('[', '_', $editor_id);
                $editor_id = str_replace(']', '-', $editor_id);
			?>


			<?php
                // ICON
    			echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : '';
			?>


			<?php
                // LABEL ?
                if($this->label !== null): ?>
                <label for="<?php echo $editor_id; ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<?php endif; ?>



			<div id="wp-<?php echo $editor_id; ?>-wrap" class="wp-core-ui wp-editor-wrap tmce-active extra-custom-editor">

                <div id="wp-<?php echo $editor_id; ?>-editor-tools" class="wp-editor-tools hide-if-no-js">

    			    <?php
        			    if (!function_exists('media_buttons')) {
                            include(ABSPATH . 'wp-admin/includes/media.php');
                        }
                    ?>

                    <div id="wp-<?php echo $editor_id; ?>-media-buttons" class="wp-media-buttons">
                        <?php do_action( 'media_buttons', $editor_id ); ?>
                    </div>

    			    <div class="wp-editor-tabs">
                        <a id="<?php echo $editor_id; ?>-html" class="wp-switch-editor switch-html" onclick="switchEditors.switchto(this);"><?php _e("Text"); ?></a>
                        <a id="<?php echo $editor_id; ?>-tmce" class="wp-switch-editor switch-tmce" onclick="switchEditors.switchto(this);"><?php _e("Visual"); ?></a>
    				</div>
    			</div>
    			<?php
    			if(isset($this->custom_css) && !empty($this->custom_css)) {
    			     $stylesheets = $this->extract_stylesheets($this->custom_css);
    			}
    			?>

    			<div id="wp-<?php echo $editor_id; ?>-editor-container" class="wp-editor-container">
				    <textarea
				        class="wp-editor-area extra-custom-editor"
				        <?php if(isset($stylesheets)): ?>
				        data-custom-css="<?php echo $stylesheets; ?>"
				        <?php endif; ?>
				        data-extra-name="<?php
				            echo $this->name;
				            echo (isset($this->editor_class) && !empty($this->editor_class)) ? ' ' . $this->editor_class : '';
			             ?>"
				        id="<?php echo $editor_id; ?>"
				        name="<?php $this->mb->the_name(); ?>">
				            <?php echo apply_filters('the_content', html_entity_decode( $this->mb->get_the_value(), ENT_QUOTES, 'UTF-8' )); ?>
			             </textarea>
				</div>
			</div>
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

    private function extract_stylesheets($custom_css) {
        $stylesheets = array();
        $counter = 0;
        $less = wp_less::instance();
        if(is_array($custom_css)) {
            foreach($custom_css as $css) {
                $path = pathinfo($css);
                if($path['extension'] === 'less') {
                    $css = $less->parse_stylesheet($css, $this->name . '-' . $counter);
                }
                $stylesheets[] = $css;
                $counter++;
            }
        } else {
            $path = pathinfo($custom_css);
            if($path['extension'] === 'less') {
                $custom_css = $less->parse_stylesheet($custom_css, $this->name);
            }
            $stylesheets[] = $custom_css;
        }
        $stylesheets = implode(',', $stylesheets);
        return $stylesheets;
    }


    public function extract_properties($properties) {
        parent::extract_properties($properties);
        $this->editor_class = isset($properties['editor_class']) ? $properties['editor_class'] : null;
        $this->custom_css = isset($properties['custom_css']) ? $properties['custom_css'] : null;
    }
}