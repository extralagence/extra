<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 09:23
 */

require_once 'ExtraMetaBox.php';

class ExtraPageBuilder extends ExtraMetaBox {

	public $fields;

	function __construct ($arr) {
		$arr['template'] = EXTRA_INCLUDES_PATH.'/extra-metabox/page-builder/template.php';
		parent::WPAlchemy_MetaBox($arr);

		$this->add_action('init', array($this, 'extra_init'));
		add_action('wp_ajax_extra_page_builder_block_content_form', array($this, 'extra_page_builder_block_content_form_callback'));
		//add_thickbox();
	}

	public function extra_init() {
		// ADMIN MODAL
		wp_enqueue_style('extra-admin-modal', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-admin-modal.less');
		wp_enqueue_script('extra-admin-modal', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-admin-modal.js', array('jquery'), null, true);

		// PAGE BUILDER
		wp_enqueue_style('extra-page-builder-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-page-builder.less');
		wp_enqueue_script('extra-page-builder-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-page-builder.js', array('jquery', 'extra-admin-modal'), null, true);

		// TWEENMAX
		wp_enqueue_script('tweenmax', 'http://cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/TweenMax.min.js', null, null, true);

		wp_localize_script('extra-page-builder-metabox', 'ajax_url', admin_url('admin-ajax.php'));
	}

	/**
	 * Callback for ajax method "extra_page_builder_block_content_form"
	 */
	public function extra_page_builder_block_content_form_callback() {
		$block_type = $_GET['block_type'];
		$this->extra_page_builder_the_block_content($block_type);
		die;
	}

	function extra_page_builder_the_block_content($html_block) {
		?>
		<div class="extra-page-builder-block-content">
			<?php echo $html_block; ?>
		</div>
		<div class="extra-page-builder-block-content-admin">
			<a href="#" class="edit-block">
				<span class="icon-extra-page-builder icon-extra-page-builder-edit"></span>
			</a>
			<a href="#" class="delete-block">
				<span class="icon-extra-page-builder icon-extra-page-builder-cross"></span>
			</a>
		</div>
		<?php
	}

	function extra_page_builder_the_block_blank() {
		?>
		<div class="choose-block">
			<div class="choose-block-wrapper">
				<div class="choose-block-mask">
					<div>
						<a href="#" class="choose-link"><?php _e("Choisir un bloc"); ?></a>
					</div>
					<div>
						<a href="#" class="choose-block-button choose-bloc-editor" data-value="custom_editor"><span class="icon-extra-page-builder icon-extra-page-builder-editor"></span><?php _e("Editeur", "extra-page-builder"); ?></a>
						<a href="#" class="choose-block-button choose-bloc-map" data-value="map"><span class="icon-extra-page-builder icon-extra-page-builder-map"></span><?php _e("Carte", "extra-page-builder"); ?></a>
						<a href="#" class="choose-block-button choose-bloc-image" data-value="image"><span class="icon-extra-page-builder icon-extra-page-builder-image"></span><?php _e("Image", "extra-page-builder"); ?></a>
						<a href="#" class="choose-block-button choose-bloc-slider" data-value="slider"><span class="icon-extra-page-builder icon-extra-page-builder-slider"></span><?php _e("Carrousel", "extra-page-builder"); ?></a>
					</div>
				</div>
			</div>
		</div>
	<?php
	}

	function extra_page_builder_the_block($block_number) {
		?>
		<?php
		$this->the_field('page_builder_block_choice_'.$block_number);
		$block_choice = $this->get_the_value();
		?>

		<div class="extra-page-builder-block block-<?php echo $block_number ?><?php ''; ?><?php echo (empty($block_choice)) ? ' not-selected' : ''; ?>">
			<input class="extra-page-builder-block-choice" type="hidden" name="<?php $this->the_name(); ?>" value="<?php echo (!empty($block_choice)) ? $this->get_the_value() : ''; ?>">
			<?php $this->extra_page_builder_the_block_blank(); ?>
			<?php $this->extra_page_builder_the_block_content($block_choice); ?>
		</div>
	<?php
	}
}