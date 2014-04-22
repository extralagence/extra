<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class taxonomy
 *
 * Define a taxonomy input metabox
 *
 * type = taxonomy
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
class Taxonomy extends AbstractField {

	protected $taxonomy;
	protected $label_default_taxomony;

	public static function init () {
		parent::init();
	}

	public function the_admin() {
		?>
		<div class="extra-taxonomy-container <?php echo $this->css_class; ?>">
			<?php $this->mb->the_field($this->get_single_field_name('text')); ?>
			<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
			<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? $this->name : $this->label; ?></label>

			<?php $this->mb->the_field($this->get_single_field_name("taxonomy")); ?>
			<?php
			?>
			<select
				name="<?php $this->mb->the_name(); ?>"
				id="<?php $this->mb->the_name(); ?>"
				>
				<option value=""><?php echo ($this->label_default_taxomony == null) ? __("Choisir une catégorie", "extra") : $this->label_default_taxomony; ?></option>
				<?php
				$categories = get_categories(array(
					'parent' => 0,
					'orderby' => 'term_order',
					'taxonomy' => $this->taxonomy,
					'hide_empty' => 0
				));

				$option = $this->taxonomy_loop($categories, $this->mb->get_the_value());
				echo $option;

				?>
			</select>
		</div>
		<?php
	}

	private function taxonomy_loop($children, $selected_value) {
		$return = '';
		if(!empty($children)) {
			foreach ($children as $child) {
				$selected = ' ';
				$value = $child->slug;
				if ($selected_value == $value) {
					$selected = ' selected';
				}

				if($child->parent != 0) {
					$space = '&nbsp;&nbsp;&nbsp;';
				} else {
					$space = '';
				}

				$return .= '
				<option'.$selected.' value="'.$value.'">'.$space.$child->name.'</option>
			';

				$next = get_categories(array(
					'parent' => $child->term_id,
					'orderby' => 'term_order',
					'taxonomy' => $this->taxonomy,
					'hide_empty' => 0
				));
				if(!empty($next)) {
					$return .= $this->taxonomy_loop($next, $selected_value);
				}
			}
		} else {
			$return = '';
		}
		return $return;
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		$this->taxonomy = isset($properties['taxonomy']) ? $properties['taxonomy'] : null;
		$this->label_default_taxomony = $properties['label_default_taxomony'];
	}

	public function the_admin_column_value() {
		//TODO
		echo '-';
	}
}