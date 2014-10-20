<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class File
 *
 * Define a file metabox
 *
 * type = file
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
class File extends AbstractField {

	public static function init () {
		parent::init();

		add_action( 'admin_enqueue_scripts', function () {
			wp_enqueue_media();
			wp_enqueue_script('extra-file-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-file.js', array('jquery'), null, true);
		});
	}

	public function the_admin() {
		?>
		<div class="<?php echo $this->css_class; ?>">
			<?php if (!empty($this->title)) : ?>
				<h2>
					<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
					<?php echo ($this->title == null) ? __('Image', 'extra-admin') : $this->title; ?>
				</h2>
			<?php endif; ?>
			<?php $this->mb->the_field($this->get_single_field_name("file")); ?>
			<div class="extra-custom-file">
				<label for="<?php $this->mb->the_name(); ?>"><?php echo ($this->label == null) ? __('Ajouter un fichier', 'extra-admin') : $this->label; ?></label>
				<input class="choose-button button" type="button" value="<?php _e("Ouvrir le gestionnaire de fichiers", "extra-admin"); ?>" />
                <input class="file-input" name="<?php $this->mb->the_name(); ?>" type="text" value="<?php $this->mb->the_value(); ?>" />
                <br />
                <span class="file-name"><?php echo basename($this->mb->get_the_value()); ?></span>
			</div>
		</div>
		<?php
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
	}

	public function the_admin_column_value() {
		$meta = $this->mb->get_meta($this->name, $this->mb->meta);
		echo $meta;
	}
}