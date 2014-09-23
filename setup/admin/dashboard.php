<?php
/**********************
 *
 *
 *
 * INIT WIDGET
 *
 *
 *
 *********************/
function extra_dashboard_setup() {
	wp_add_dashboard_widget('extra_welcome_dashboard_widget', __('Bienvenue', 'extra-admin'), 'extra_welcome_dashboard_widget_output');
	add_meta_box('extra_welcome_dashboard_widget', __('Bienvenue', 'extra-admin'), 'extra_welcome_dashboard_widget_output', 'dashboard', 'normal', 'core');
}
remove_action("welcome_panel", "wp_welcome_panel");
add_action('welcome_panel', 'extra_welcome_dashboard_widget_output' );
/**********************
 *
 *
 *
 * WIDGET HTML
 *
 *
 *
 *********************/
function extra_welcome_dashboard_widget_output() { ?>
	<h3><?php _e("Bienvenue", "extra-admin"); ?></h3>
	<p class="about-description"><?php _e("Voici quelques raccourcis pour utiliser le site", "extra-admin"); ?></p>
	<div class="extra-welcome-wrapper">
		<div class="welcome-panel-column">
			<ul>
			<?php if ('page' == get_option('show_on_front') && !get_option('page_for_posts')) : ?>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __("Éditer la page d'accueil", 'extra-admin').'</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __('Ajouter une nouvelle page', 'extra-admin').'</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
			<?php elseif ('page' == get_option('show_on_front')) : ?>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __("Éditer la page d'accueil", 'extra-admin').'</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __('Ajouter une nouvelle page', 'extra-admin').'</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __('Rédiger une actualité', 'extra-admin').'</a>', admin_url( 'post-new.php' ) ); ?></li>
			<?php else : ?>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __('Rédiger votre première actualité', 'extra-admin').'</a>', admin_url( 'post-new.php' ) ); ?></li>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __('Ajouter une nouvelle page', 'extra-admin').'</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>
			<?php endif; ?>
				<?php do_action('extra_dashboard_welcome_panel_links'); ?>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-view-site">' . __('Afficher le site', 'extra-admin').'</a>', home_url( '/' ) ); ?></li>
			</ul>
		</div>
	</div>
<?php }
/**********************
 *
 *
 *
 * HIDE DASHBOARD WIDGETS
 *
 *
 *
 *********************/
function extra_dashboard_widgets() {
	global $wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['icl_dashboard_widget']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}
add_action('wp_dashboard_setup', 'extra_dashboard_widgets');
remove_action( 'welcome_panel', 'wp_welcome_panel' );
/**********************
 *
 *
 *
 * ALWAYS SHOW WELCOME PANEL :)
 *
 *
 *
 *********************/
function extra_hook_welcome($value, $object_id, $meta_key, $single ) {
    if($meta_key == 'show_welcome_panel') {
        return 1;
    }
}
add_filter( "get_user_metadata", "extra_hook_welcome", 10, 4);
?>