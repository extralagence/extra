<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

namespace ExtraPageBuilder\Blocks;

use ExtraPageBuilder\AbstractBlock;

/**
 * Class Image
 *
 * Define a image block
 *
 * type = image
 */
class Image extends AbstractBlock {

	public static function init () {
		parent::init();

		wp_enqueue_style('extra-page-builder-block-image', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Image/css/image.less');
		wp_enqueue_script('extra-page-builder-block-image', EXTRA_INCLUDES_URI . '/extra-metabox/page-builder/blocks/Image/js/image.js', array('jquery'), null, true);
	}

	function __construct($mb, $type) {
		parent::__construct($mb, $type);
		$this->resizable = true;
	}

	public function extract_properties($properties) {
		parent::extract_properties($properties);
		if (empty($this->add_icon)) {
			$this->add_icon = 'icon-extra-page-builder-image';
		}
		if (empty($this->add_label)) {
			$this->add_label = __("Image", "extra-admin");
		}
	}

	public function the_admin($name_suffix) {
		$name = $name_suffix;
		$this->mb->the_field($name); ?>
		<input class="extra-page-builder-image-input" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" />
		<?php $this->mb->the_field('size_'.$name); ?>
		<input class="extra-page-builder-image-input-size" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>" />
		<?php
	}

	public function the_preview($name_suffix, $block_width) {
		$name = $name_suffix;

		$img_size = $this->mb->get_the_value('size_'.$name_suffix);
		if ($img_size == null) {
			$img_size = 'original';
		}
		$img_id = $this->mb->get_the_value($name);

		if(!empty($img_id)){
			$src =  wp_get_attachment_image_src( $img_id, 'full' );

			//echo '<div class="extra-page-builder-image"><img src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" /></div>';
			//echo '<div class="extra-page-builder-image'.$css.'" style="background-image: url('.$src[0].');"></div>';

			//echo'<div class="extra-page-builder-image size-'.$img_size.'" style="background-image: url('.$src[0].');"></div>';

			$original_width = $src[1];
			$original_height = $src[2];

			switch ($img_size) {
				case 'auto' :
					if ($original_width < $block_width) {
						$height = $original_height;
					} else {
						$height = intval(($block_width * $original_height) / $original_width);
					}
					echo'<div data-original-width="'.$original_width.'" data-original-height="'.$original_height.'" class="extra-page-builder-image size-'.$img_size.'" style="background-image: url('.$src[0].'); height: '.$height.'px;"></div>';
					break;
				case 'custom' :
					echo'<div data-original-width="'.$original_width.'" data-original-height="'.$original_height.'" class="extra-page-builder-image size-'.$img_size.'" style="background-image: url('.$src[0].');"></div>';
					break;
			}

		} else {
			echo '<div class="extra-page-builder-image empty"></div>';
		}
	}

	public static function get_front($block_data, $name_suffix, $block_height, $block_width) {
		$img_size = isset($block_data['size_'.$name_suffix]) ? $block_data['size_'.$name_suffix] : null;
		if ($img_size == null) {
			$img_size = 'auto';
		}
		$img_id = $block_data[$name_suffix];
		if(!empty($img_id)){
            if(empty($alt)) {
                $alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);
                if(empty($alt)) {
                    $alt = get_the_title($img_id);
                }
            }
			$src =  wp_get_attachment_image_src( $img_id, 'full' );

			$original_width = $src[1];
			$original_height = $src[2];
			//$html = '<div class="extra-page-builder-image" style="background-image: url('.$src[0].');"></div>';
            //$html = '<div class="extra-page-builder-image size-'.$img_size.'"><img src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" /></div>';

			if ($img_size == 'auto') {
				if ($original_width < $block_width) {
					$width = $original_width;
					$height = $original_height;
				} else {
					$width = $block_width;
					$height = intval(($block_width * $original_height) / $original_width);
				}

				$params = array('width' => $width, 'height' => $height, 'crop' => 'false');
				$html = '<div class="extra-page-builder-image size-'.$img_size.'"><img src="'.bfi_thumb($src[0], $params).'" width="'.$width.'" height="'.$height.'" /></div>';
			} else {
				$params = array('width' => $block_width, 'height' => $block_height, 'crop' => 'true');
				$html = '<div class="extra-page-builder-image size-'.$img_size.'"><img src="'.bfi_thumb($src[0], $params).'" width="'.$block_width.'" height="'.$block_height.'" /></div>';
			}
		} else {
			$html = '<div class="extra-page-builder-image empty"></div>';
		}

		return $html;
	}

	/**
	 * @return boolean
	 */
	public function is_resizable($name_suffix = null, $block_data = null) {
		if ($block_data == null) {
			$img_size = $this->mb->get_the_value('size_'.$name_suffix);
		} else {
			$img_size = isset($block_data['size_'.$name_suffix]) ? $block_data['size_'.$name_suffix] : null;
		}

		if ($img_size == null) {
			$img_size = 'auto';
		}
		if ($img_size == 'auto') {
			return false;
		}

		return true;
	}
}

//add_filter( 'attachment_fields_to_save', function ($post, $attachment) {
//	var_dump($attachment);
//
//	return $post;
//}, 10, 2 );

add_filter( 'attachment_fields_to_edit', function ($form_fields, $post) {
	//$value = get_post_meta( $post->ID, 'extra_image_size', true );
	$value = 'auto';

	$form_fields['extra_image_size'] = array(
		'label' => __( "Taille de l'image", "extra-admin" ),
		'input' => 'html',
		'value' => $value,
		'html' => "<input checked='checked' type='radio' id='extra_image_size_{$post->ID}_auto' name='attachments[{$post->ID}][extra_image_size]' value='auto'> <label for='extra_image_size_{$post->ID}_auto'>".__("Taille automatique", "extra-admin")."</label><br>
				   <input type='radio' id='extra_image_size_{$post->ID}_custom' name='attachments[{$post->ID}][extra_image_size]' value='custom'> <label for='extra_image_size_{$post->ID}_custom'>".__("Taille personnalisée", "extra-admin")."</label>",
	);

	return $form_fields;
}, 10, 2 );
