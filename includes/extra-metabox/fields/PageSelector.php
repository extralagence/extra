<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Text
 *
 * Define a page selector input metabox
 *
 * type = page_selector
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - placeholder (optional): label when the field is empty
 * - regex (optional): regex checked for each changes
 */
class PageSelector extends AbstractField {

	protected $post_type;

	public static function init () {
		parent::init();
	}

	public function the_admin() {
		?>
		<?php $this->mb->the_field($this->get_single_field_name('page_selector')); ?>
		<p class="<?php echo $this->css_class; ?> extra-page-selector-container">
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<?php $this->generate_post_select($this->mb->get_the_name(), $this->mb->get_the_value(), $this->post_type); ?>
		</p>
		<?php
	}

	private function generate_post_select($select_id, $selected = 0, $post_type = null) {
		if ($post_type === null) {
			wp_dropdown_pages(array(
				'name' => $this->mb->get_the_name(),
				'selected' => $this->mb->get_the_value(),
			));
		} else {
			$post_type_object = get_post_type_object($post_type);
			//$label = $post_type_object->label;
			$posts = get_posts(array('post_type'=> $post_type, 'post_status'=> 'publish', 'suppress_filters' => false, 'posts_per_page'=>-1));
			echo '<select name="'. $select_id .'" id="'.$select_id.'">';
			foreach ($posts as $post) {
				echo '<option value="', $post->ID, '"', $selected == $post->ID ? ' selected="selected"' : '', '>', $post->post_title, '</option>';
			}
			echo '</select>';
		}
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->post_type = (isset ($properties['post_type'])) ? $properties['post_type'] : null;
	}

	public function the_admin_column_value() {
		//TODO
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		echo $meta;
	}
} 