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
	public $row_layouts;
	public $row_default_layout;
	public $block_instances;

	function __construct ($arr) {
		$arr['template'] = EXTRA_INCLUDES_PATH.'/extra-metabox/page-builder/template.php';
		parent::WPAlchemy_MetaBox($arr);

		$this->add_action('init', array($this, 'extra_init'));

		//AJAX
		add_action('wp_ajax_extra_page_builder_block', array($this, 'builder_block_callback'));

		if ($this->row_layouts == null || empty($this->row_layouts)) {
			$this->row_layouts = array('1', '12', '21', '11', '111');
		}
		if ($this->row_default_layout == null) {
			$this->row_default_layout = $this->row_layouts[0];
		}
	}

	private function is_associative ($arr) {
		return (is_array($arr) && count(array_filter(array_keys($arr),'is_string')) == count($arr));
	}

	public function extra_init() {
		// TWEENMAX
		wp_enqueue_script('tweenmax', 'http://cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/TweenMax.min.js', null, null, true);

		// ADMIN MODAL
		wp_enqueue_style('extra-admin-modal', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/css/extra-admin-modal.less');
		wp_enqueue_script('extra-admin-modal', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/js/extra-admin-modal.js', array('jquery'), null, true);

		// PAGE BUILDER
		wp_enqueue_style('extra-page-builder-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/css/extra-page-builder.less');
		wp_enqueue_script(
			'extra-page-builder-metabox',
			EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/js/extra-page-builder.js',
			array('jquery', 'extra-admin-modal', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-resizable'),
			null,
			true
		);

		$this->block_instances = array();
		// BLOCKS
		if (isset($this->blocks) && !empty($this->blocks)) {

			if ($this->is_associative($this->blocks)) {
				foreach ($this->blocks as $type => $properties) {
					$block = $this->construct_block_from_properties($type, $properties);
					$block->init();
					$this->block_instances[$type] = $block;
				}
			} else {
				foreach ($this->blocks as $type) {
					$block = $this->construct_block_from_properties($type, array());
					$block->init();
					$this->block_instances[$type] = $block;
				}
			}


		} else {
			throw new Exception('Extra Page Builder "blocks" required');
		}

		wp_localize_script('extra-page-builder-metabox', 'ajax_url', admin_url('admin-ajax.php'));
	}

	/**
	 * Callback for ajax method "extra_page_builder_block"
	 */
	public function builder_block_callback() {
		$block_type = $_GET['block_type'];
		$block_id = $_GET['block_id'];
		$row_id =  $_GET['row_id'];

		$class = $this->construct_class_name($block_type);
		/**
		 * @var $block \ExtraPageBuilder\AbstractBlock
		 */
		$block = new $class($this, $block_type);

		// This is a hack to imitate wpalchemy group behavior
		$this->id = $row_id;
		$this->in_template = TRUE;
		$this->the_block_wrapper($block, $block_id);

		die;
	}

	protected function construct_class_name($type) {
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
		$block = new $class($this, $type);
		$block->extract_properties($properties);

		return $block;
	}

	/**
	 * @param $block_id
	 */
	public function the_block($block_id, $row_layout) {
		?>
		<?php
		$this->the_field('page_builder_block_choice_'.$block_id);
		$block_type = $this->get_the_value();

		/**
		 * @var $block \ExtraPageBuilder\AbstractBlock
		 */
		$block = $this->block_instances[$block_type];
		$resizable = ($block == null) ? false : $block->is_resizable();
		$editable = ($block == null) ? false : $block->is_editable();

		$block_height = null;
		if ($resizable) {
			$block_height = $this->get_the_value('page_builder_block_height_'.$block_id);
		}

		$block_css = '';
		if (empty($block_type)) {
			$block_css .= ' not-selected';
		}
		if ($resizable) {
			$block_css .= ' resizable';
		}
		if ($editable) {
			$block_css .= ' editable';
		}
		if ($block_id == 1) {
			$block_css .= ' first';
		}

		if (
			($row_layout == '1' && $block_id == 1) ||
			($row_layout == '12' && $block_id == 2) ||
			($row_layout == '21' && $block_id == 2) ||
			($row_layout == '11' && $block_id == 2) ||
			($row_layout == '111' && $block_id == 3)
		) {
			$block_css .= ' last';
		}
		?>

		<div
			class="extra-page-builder-block extra-page-builder-block-<?php echo $block_id ?><?php echo (!empty($block_css)) ? ' '.$block_css : '';  ?>"
			data-block-number="<?php echo $block_id; ?>"
			<?php echo ($resizable && !empty($block_height)) ? ' style="height: '.$block_height.';"' : ''; ?>>
			<div class="extra-page-builder-block-droppable-wrapper">
				<input class="extra-page-builder-block-choice" type="hidden" name="<?php $this->the_name(); ?>" value="<?php echo (!empty($block_type)) ? $this->get_the_value() : ''; ?>">

				<input class="extra-page-builder-block-height" type="hidden" name="<?php $this->the_name('page_builder_block_height_'.$block_id); ?>" value="<?php echo (!empty($block_height)) ? $block_height : ''; ?>">

				<div class="choose-block">
					<a href="#" class="choose-link"><?php _e("Choisir un bloc"); ?></a>
					<div class="choose-block-choices">
						<?php
						/**
						 * @var $current \ExtraPageBuilder\AbstractBlock
						 */
						$current = null;
						foreach ($this->block_instances as $type => $current) : ?>
							<a
								href="#" class="choose-block-button choose-bloc-<?php echo $type; ?>"
								data-value="<?php echo $type; ?>"
								data-resizable="<?php echo $current->is_resizable() ? 'yes' : 'no'; ?>"
								data-editable="<?php echo $current->is_editable() ? 'yes' : 'no'; ?>"
								>
								<span class="icon-extra-page-builder <?php echo $current->get_add_icon(); ?>"></span><?php echo $current->get_add_label(); ?>
							</a>
						<?php endforeach; ?>
						<!--						<a href="#" class="choose-block-button choose-bloc-editor" data-value="custom_editor"><span class="icon-extra-page-builder icon-extra-page-builder-editor"></span>--><?php //_e("Editeur", "extra-page-builder"); ?><!--</a>-->
						<!--						<a href="#" class="choose-block-button choose-bloc-map" data-value="map"><span class="icon-extra-page-builder icon-extra-page-builder-map"></span>--><?php //_e("Carte", "extra-page-builder"); ?><!--</a>-->
						<!--						<a href="#" class="choose-block-button choose-bloc-image" data-value="image"><span class="icon-extra-page-builder icon-extra-page-builder-image"></span>--><?php //_e("Image", "extra-page-builder"); ?><!--</a>-->
						<!--						<a href="#" class="choose-block-button choose-bloc-slider" data-value="slider"><span class="icon-extra-page-builder icon-extra-page-builder-slider"></span>--><?php //_e("Carrousel", "extra-page-builder"); ?><!--</a>-->
					</div>
				</div>
				<div class="extra-page-builder-block-wrapper">
					<?php $this->the_block_wrapper($block, $block_id); ?>
				</div>
			</div>
		</div>
	<?php
	}

	/**
	 * @param $block \ExtraPageBuilder\AbstractBlock
	 * @param $block_id int
	 */
	protected function the_block_wrapper($block, $block_id) {
		if ($block != null) : $name_suffix = $block->get_type().'_'.$block_id;
			?>
			<div class="extra-page-builder-block-content">
				<?php
				$block->the_preview($name_suffix);
				?>
			</div>
			<div class="extra-page-builder-block-content-admin">
				<div class="extra-page-builder-block-content-admin-wrapper">
					<a href="#" class="edit-block">
						<span class="icon-extra-page-builder icon-extra-page-builder-edit"></span>
					</a>
					<a href="#" class="delete-block">
						<span class="icon-extra-page-builder icon-extra-page-builder-cross"></span>
					</a>
				</div>
				<div class="extra-page-builder-block-content-admin-size">
				</div>
			</div>
			<div class="extra-page-builder-block-form">
				<div class="extra-field-form">
					<?php $block->the_admin($name_suffix) ?>
				</div>
			</div>

		<?php
		endif;
	}

	public function get_front($id = null) {
		$meta = $this->the_meta($id);
        if(!isset($meta['page_builder'])) {
            return;
        }
		$rows = $meta['page_builder'];
		$html = '<div class="extra-page-builder-wrapper">';
		$row_number = 1;
		foreach ($rows as $row) {
			$row_css = 'row';
			if ($row_number == 1) {
				$row_css .= ' first';
			}
			if ($row_number = count($row)) {
				$row_css .= ' last';
			}
			$html .= $this->get_front_row($row, $row_number, $row_css);
			$row_number++;
		}
		$html .= '</div>';

		return $html;
	}

	protected function get_front_row($row, $row_number, $row_css) {
		$row_type = isset($row['page_builder_row_type']) ? $row['page_builder_row_type'] : '1';
		$html = '<div class="'.$row_css.'">';
		switch ($row_type) {
			case '1':
				$html .= $this->get_front_block($row, $row_number, '1', array('col', 'col-12', 'first', 'last'));
				break;

			case '11':
				$html .= $this->get_front_block($row, $row_number, '1', array('col', 'col-6', 'first'));
				$html .= $this->get_front_block($row, $row_number, '2', array('col', 'col-6', 'last'));
				break;

			case '111':
				$html .= $this->get_front_block($row, $row_number, '1', array('col', 'col-4', 'first'));
				$html .= $this->get_front_block($row, $row_number, '2', array('col', 'col-4'));
				$html .= $this->get_front_block($row, $row_number, '3', array('col', 'col-4', 'last'));
				break;

			case '12':
				$html .= $this->get_front_block($row, $row_number, '1', array('col', 'col-4', 'first'));
				$html .= $this->get_front_block($row, $row_number, '2', array('col', 'col-8', 'last'));
				break;

			case '21':
				$html .= $this->get_front_block($row, $row_number, '1', array('col', 'col-8', 'first'));
				$html .= $this->get_front_block($row, $row_number, '2', array('col', 'col-4', 'last'));
				break;

			default :
				break;
		}
		$html .= '</div>';

		return $html;
	}

	protected function endsWith($haystack, $needle)
	{
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}

	protected function get_front_block($row_data, $row_number, $block_number, $block_css) {
		$block_type = $row_data['page_builder_block_choice_'.$block_number];
		// _page_builder[page_builder][0][page_builder_block_height_1]
		$block_height = $row_data['page_builder_block_height_'.$block_number];

		$block_suffix = $block_type.'_'.$block_number;

		if (is_array($block_css)) {
			$css = implode(' ', $block_css);
		} else {
			$css = $block_css;
		}

		$block_data = array();
		foreach ($row_data as $key => $data) {
			if($this->endsWith($key, $block_suffix)) {
				$block_data[$key] = $row_data[$key];
			}
		}
		$block_html = '';
		if (!empty($block_type)) {
			$class = $this->construct_class_name($block_type);
			/* @var $instance \ExtraPageBuilder\AbstractBlock */
			$instance = new $class($this, $block_type);
			if (!$instance->is_resizable()) {
				$block_height = null;
			}

			$block_html = $class::get_front($block_data, $block_suffix, $block_height);
			$block_html = apply_filters('extra_page_builder_'.$block_type, $block_html, $block_data, $block_suffix, $block_css, $block_number, $row_number, $block_height);
		}

		$block_height_html = ($block_height != null) ? ' style="height: '.$block_height.';"' : '';

		$html = '<div class="'.$css.'"'.$block_height_html.'>';
		$html .= $block_html;
		$html .= '</div>';

		return $html;
	}
}