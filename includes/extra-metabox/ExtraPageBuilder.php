<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 09:23
 */

require_once 'MetaBox.php';
require_once 'page-builder/AbstractBlock.php';

//Require once each fields
foreach (scandir(dirname(__FILE__).'/page-builder/blocks') as $field_name) {
	$path = dirname(__FILE__).'/page-builder/blocks/'.$field_name.'/'.$field_name.'.php';
	if (is_file($path)) {
		require_once $path;
	}
}


class ExtraPageBuilder extends WPAlchemy_MetaBox {

	public $blocks;
	public $block_instances;

	function __construct ($arr) {
		$arr['template'] = EXTRA_INCLUDES_PATH.'/extra-metabox/page-builder/template.php';
		parent::WPAlchemy_MetaBox($arr);

		$this->add_action('init', array($this, 'extra_init'));
		add_action('wp_ajax_extra_page_builder_block_content_form', array($this, 'extra_page_builder_block_content_form_callback'));
	}

	public function extra_init() {
		// TWEENMAX
		wp_enqueue_script('tweenmax', 'http://cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/TweenMax.min.js', null, null, true);

		// ADMIN MODAL
		wp_enqueue_style('extra-admin-modal', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/css/extra-admin-modal.less');
		wp_enqueue_script('extra-admin-modal', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/js/extra-admin-modal.js', array('jquery'), null, true);


		// PAGE BUILDER
		wp_enqueue_style('extra-page-builder-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/css/extra-page-builder.less');
		wp_enqueue_script('extra-page-builder-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/js/extra-page-builder.js', array('jquery', 'extra-admin-modal'), null, true);

		$this->block_instances = array();
		// BLOCKS
		if (isset($this->blocks) && !empty($this->blocks)) {
			foreach ($this->blocks as $type => $properties) {
				$block = $this->construct_block_from_properties($type, $properties);
				$block->init();
				$this->block_instances[$type] = $block;
			}
		} else {
			throw new Exception('Extra Page Builder "blocks" required');
		}

		wp_localize_script('extra-page-builder-metabox', 'ajax_url', admin_url('admin-ajax.php'));
	}


	private function construct_class_name($type) {
		if (empty($type)) throw new Exception ('Extra Page Builder Block "type" required');
		$array = explode('_', $type);
		$class = '';
		foreach ($array as $item) {
			$class .= ucfirst($item);
		}

		return 'ExtraPageBuilder\\Blocks\\'.$class;
	}

	protected function construct_block_from_properties($type, $properties) {
		$class = $this->construct_class_name($type);
		/**
		 * @var $block \ExtraPageBuilder\AbstractBlock
		 */
		$block = new $class($this);
		$block->extract_properties($properties);

		return $block;
	}

	/**
	 * Callback for ajax method "extra_page_builder_block_content_form"
	 */
	public function extra_page_builder_block_content_form_callback() {
		$block_type = $_GET['block_type'];
		$block_id = $_GET['block_id'];

		$this->the_block_admin($block_type, $block_id);

		die;
	}

	public function the_block_admin($block_type, $block_id) {

		if (array_key_exists($block_type, $this->block_instances)) {
			/**
			 * @var $block \ExtraPageBuilder\AbstractBlock
			 */
			$block = $this->block_instances[$block_type];
			$block->the_admin($block_id);
		}
	}

	function the_block_front($block_type, $block_id) {
		?>
		<div class="extra-page-builder-block-content">
			<?php
			if (array_key_exists($block_type, $this->block_instances)) {
				/**
				 * @var $block \ExtraPageBuilder\AbstractBlock
				 */
				$block = $this->block_instances[$block_type];
				$block->the_front($block_id);
			}
			?>
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

	function the_block_blank() {
		/**
		 * @var $block \ExtraPageBuilder\AbstractBlock
		 */
		?>
		<div class="choose-block">
			<a href="#" class="choose-link"><?php _e("Choisir un bloc"); ?></a>
			<div class="choose-block-choices">
				<?php foreach ($this->block_instances as $type => $block) : ?>
					<a href="#" class="choose-block-button choose-bloc-<?php echo $type; ?>" data-value="<?php echo $type; ?>"><span class="icon-extra-page-builder <?php echo $block->get_add_icon(); ?>"></span><?php echo $block->get_add_label(); ?></a>
				<?php endforeach; ?>
<!--						<a href="#" class="choose-block-button choose-bloc-editor" data-value="custom_editor"><span class="icon-extra-page-builder icon-extra-page-builder-editor"></span>--><?php //_e("Editeur", "extra-page-builder"); ?><!--</a>-->
<!--						<a href="#" class="choose-block-button choose-bloc-map" data-value="map"><span class="icon-extra-page-builder icon-extra-page-builder-map"></span>--><?php //_e("Carte", "extra-page-builder"); ?><!--</a>-->
<!--						<a href="#" class="choose-block-button choose-bloc-image" data-value="image"><span class="icon-extra-page-builder icon-extra-page-builder-image"></span>--><?php //_e("Image", "extra-page-builder"); ?><!--</a>-->
<!--						<a href="#" class="choose-block-button choose-bloc-slider" data-value="slider"><span class="icon-extra-page-builder icon-extra-page-builder-slider"></span>--><?php //_e("Carrousel", "extra-page-builder"); ?><!--</a>-->
			</div>
		</div>
	<?php
	}

	function the_block($block_number) {
		?>
		<?php
		$this->the_field('page_builder_block_choice_'.$block_number);
		$block_choice = $this->get_the_value();
		?>

		<div class="extra-page-builder-block extra-page-builder-block-<?php echo $block_number ?><?php ''; ?><?php echo (empty($block_choice)) ? ' not-selected' : ''; ?>" data-block-number="<?php echo $block_number; ?>">
			<input class="extra-page-builder-block-choice" type="hidden" name="<?php $this->the_name(); ?>" value="<?php echo (!empty($block_choice)) ? $this->get_the_value() : ''; ?>">
			<?php $this->the_block_blank(); ?>
			<?php $this->the_block_front($block_choice, $block_number); ?>
			<div class="extra-page-builder-block-form">
				<?php $this->the_block_admin($block_choice, $block_number); ?>
			</div>
		</div>
	<?php
	}
}