<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Label
 *
 * Display a simple label
 *
 * type = label
 *
 * Options :
 * - name (optional)
 * - label (optional)
 * - icon (required)
 */
class Label extends Field {

	public function the_admin() {
		?>
		<p class="<?php echo $this->css_class; ?> extra-label">
			<?php echo $this->label; ?>
		</p>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		if ($this->name == null || empty($this->name)) {
			$this->name = 'label';
		}
		if (empty($this->label)) {
			throw new Exception('Extra Meta box "label" required for Label (ahah)');
		}
	}
} 