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
 * Define a taxonomy selector input metabox
 *
 * type = taxonomy_selector
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 * - placeholder (optional): label when the field is empty
 * - regex (optional): regex checked for each changes
 */
class TaxonomySelector extends AbstractField {

	protected $taxonomy;

	public static function init () {
		parent::init();
	}

	public function the_admin() {
		?>
		<?php $this->mb->the_field($this->get_single_field_name('taxonomy_selector')); ?>
		<p class="<?php echo $this->css_class; ?> extra-taxonomy-selector-container">
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>
			<?php $this->generate_category_select(); ?>
		</p>
		<?php
	}

	private function generate_category_select() {
		wp_dropdown_categories(array(
			'name' => $this->mb->get_the_name(),
			'selected' => $this->mb->get_the_value(),
			'taxonomy' => $this->taxonomy,
		));
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->taxonomy = (isset ($properties['taxonomy'])) ? $properties['taxonomy'] : 'category';
	}

	public function the_admin_column_value() {
		//TODO
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		echo $meta;
	}
} 