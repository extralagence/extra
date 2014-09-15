<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 27/02/2014
 * Time: 11:02
 */

/**
 * Class Redirection
 *
 * Define a redirection metabox
 *
 * type = redirection
 *
 * Options :
 * - name (required)
 * - label (optional)
 * - icon (optional)
 */
class Redirection extends AbstractField {

	public static function init () {
		parent::init();
		wp_enqueue_style('extra-redirection-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/css/extra-redirection.less');
		wp_enqueue_script('jquery-ui-autocomplete');
		wp_enqueue_script('extra-accent-fold-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-accent-fold.js', array('jquery'));
		wp_enqueue_script('extra-redirection-metabox', EXTRA_INCLUDES_URI . '/extra-metabox/js/extra-redirection.js', array('jquery', 'jquery-ui-autocomplete', 'extra-accent-fold-metabox'));

		wp_localize_script('extra-redirection-metabox', 'ajax', array( 'url' => admin_url( 'admin-ajax.php' )));
	}

	public function the_admin() {
		?>
		<style>
			#<?php echo $this->mb->id; ?>_metabox {
				background-color: transparent;
				border: none;
				-webkit-box-shadow: none;
				box-shadow: none;
			}

			#<?php echo $this->mb->id; ?>_metabox .hndle {
				display: none;
			}

			#<?php echo $this->mb->id; ?>_metabox .inside {
				font-size: 14px;
				padding: 8px 0;
				margin: 0;
				line-height: 1.4;
			}
		</style>

		<div class="extra-redirection <?php echo $this->css_class; ?>">
			<h2>
				<?php echo ($this->icon != null) ? '<div class="dashicons '.$this->icon.'"></div>' : ''; ?>
				<?php echo ($this->label == null) ? __('Attention cette page est redirigée !', 'extra-admin') : $this->label; ?>
			</h2>

			<div class="extra-checkbox">
				<?php $this->mb->the_field($this->get_prefixed_field_name("type")); ?>
				<input type="radio" name="<?php $this->mb->the_name(); ?>" id="redirection-type-auto" value="auto" <?php echo ($this->mb->is_value('auto') || $this->mb->get_the_value() == null )?' checked="checked"':''; ?>> <label for="redirection-type-auto">Redirection automatique</label>
			</div>

			<div class="extra-checkbox">
				<?php $this->mb->the_field($this->get_prefixed_field_name("type")); ?>
				<input type="radio" name="<?php $this->mb->the_name(); ?>" id="redirection-type-manual" value="manual" <?php echo $this->mb->is_value('manual')?' checked="checked"':''; ?>> <label for="redirection-type-manual">Redirection manuelle</label>

				<div class="extra-conditional bloc">
					<?php $this->mb->the_field($this->get_prefixed_field_name("manual")); ?>
					<label for="<?php $this->mb->the_name(); ?>"><?php _e("Url de la redirection :"); ?></label>
					<input id="<?php $this->mb->the_name(); ?>" name="<?php $this->mb->the_name(); ?>" type="text" value="<?php $this->mb->the_value(); ?>" />
				</div>

			</div>

			<div class="extra-checkbox">
				<?php $this->mb->the_field($this->get_prefixed_field_name("type")); ?>
				<input type="radio" name="<?php $this->mb->the_name(); ?>" id="redirection-type-content" value="content" <?php echo $this->mb->is_value('content')?' checked="checked"':''; ?>> <label for="redirection-type-content">Redirection vers un contenu</label>

				<div class="extra-conditional bloc">
					<?php $this->mb->the_field($this->get_prefixed_field_name("content")); ?>
					<?php $post_id = $this->mb->get_the_value(); ?>

					<div class="extra-search-choice" <?php echo (empty($post_id)) ? 'style="display: none;"' : ''; ?>>
						<span><?php _e("Votre sélection :"); ?></span>
						<div id="extra-search-choice-title"><?php echo (!empty($post_id)) ? get_the_title($this->mb->get_the_value()) : ''; ?></div>
						<div id="extra-search-choice-url"><?php echo (!empty($post_id)) ? get_permalink($this->mb->get_the_value()) : ''; ?></div>
					</div>

					<label for="content-autocomplete"><?php _e("Recherchez un contenu :"); ?></label>
					<input id="content-autocomplete" type="text" value=""/>
					<input id="hidden-content-autocomplete" name="<?php $this->mb->the_name(); ?>" type="hidden" value="<?php $this->mb->the_value(); ?>"/>
				</div>
			</div>

			<div class="extra-checkbox">
				<?php $this->mb->the_field($this->get_prefixed_field_name("type")); ?>
				<input type="radio" name="<?php $this->mb->the_name(); ?>" id="redirection-type-post-type" value="post-type" <?php echo $this->mb->is_value('post-type')?' checked="checked"':''; ?>> <label for="redirection-type-post-type">Redirection vers un type de contenu</label>

				<div class="extra-conditional bloc">
					<?php $this->mb->the_field($this->get_prefixed_field_name("post-type")); ?>
					<label for="<?php $this->mb->the_name(); ?>"><?php _e("Choisissez un type de contenu :", "extra-admin"); ?></label>
					<select name="<?php $this->mb->the_name(); ?>" id="<?php $this->mb->the_name(); ?>">
						<?php
						$post_types = get_post_types('', 'objects');
						foreach ($post_types as $post_type): ?>
							<option value="<?php echo esc_attr($post_type->name); ?>"<?php echo $this->mb->is_value($post_type->name)?' selected="selected"':''; ?>><?php echo esc_html($post_type->labels->name); ?></option>
						<?php endforeach; ?>
					</select>


				</div>
			</div>
		</div>
		<?php
	}

	static function extra_redirection_wp_ajax() {
		global $wpdb;

		$types = get_post_types(array(
			'public' => true
		));
		if (array_key_exists('attachment', $types)) {
			unset($types['attachment']);
		}
		$post_types = "'".implode("', '", $types)."'";

		$keyword = '%'.$_GET['term'].'%';

		$query = $wpdb->prepare("
		SELECT DISTINCT ID, post_title, post_type FROM {$wpdb->posts}
		WHERE post_title LIKE '%s'
		AND post_status = 'publish'
		AND post_type IN (".$post_types.")
		ORDER BY ID
		LIMIT 10
	", $keyword);

		$results = $wpdb->get_results($query);

		$data = array();
		foreach ($results as $result) {
			$type = get_post_type_object($result->post_type);

			$data[] = array(
				'ID' => $result->ID,
				'post_title' => html_entity_decode($result->post_title),
				'post_type' => $type->labels->singular_name,
				'url' => get_permalink($result->ID)
			);
		}

		echo json_encode($data);
		die();
	}

	public function the_admin_column_value() {
		//TODO
		echo '-';
	}
}

add_action('wp_ajax_extra-redirection', array('Redirection', 'extra_redirection_wp_ajax'));