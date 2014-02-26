<?php

/**
ReduxFramework Sample Config File
For full documentation, please visit: https://github.com/ReduxFramework/ReduxFramework/wiki
 **/

if (!class_exists("ReduxFramework")) {
	return;
}

if (!class_exists("Redux_Framework_sample_config")) {

	class Redux_Framework_sample_config {

		public $args = array();
		public $sections = array();
		public $theme;
		public $ReduxFramework;

		public function __construct() {

			// Set the default arguments
			$this -> setArguments();

			// Set a few help tabs so you can see how it's done
			//$this->setHelpTabs();

			// Create the sections and fields
			$this -> setSections();

			$this -> ReduxFramework = new ReduxFramework($this -> sections, $this -> args);

		}

		public function setSections() {

			// ACTUAL DECLARATION OF SECTIONS
			$this -> sections[] = array(
				'icon' => 'el-icon-list-alt',
				'title' => __('Pages', 'extra-admin'),
				'desc' => null,
				'fields' => array(
					array(
						'id' => 'posts_on_front_page',
						'type' => 'slider',
						'min' => '0',
						'max' => '5',
						'default' => '3',
						'title' => __('Nombre maximum d\'actualité affichée sur la page d\'accueil', 'extra-admin'),
					),
					array(
						'id' => 'procurement_consultation_page',
						'type' => 'select',
						'data' => 'pages',
						'title' => __('Page "Marchés publics > Consultations en cours"', 'extra-admin'),
					)
				)
			);

			$this -> sections[] = array(
				'icon' => 'el-icon-link',
				'title' => __('Liens du pied de page', 'extra-admin'),
				'desc' => null,
				'fields' => array(
					array(
						'id' => 'footer_link_title_1',
						'type' => 'text',
						'title' => __('Titre du lien 1', 'extra-admin'),
					),
					array(
						'id' => 'footer_link_icon_1',
						'type' => 'text',
						'title' => __('Icône du lien 1', 'extra-admin'),
					),
					array(
						'id' => 'footer_link_subtitle_1',
						'type' => 'text',
						'title' => __('Sous titre du lien 1', 'extra-admin'),
					),
					array(
						'id' => 'footer_link_url_1',
						'type' => 'text',
						'title' => __('Url du lien 1', 'extra-admin'),
					),
					array(
						'id' => 'footer_link_separator_1',
						'type' => 'section',
					),



					array(
						'id' => 'footer_link_title_2',
						'type' => 'text',
						'title' => __('Titre du lien 2', 'extra-admin'),
					),
					array(
						'id' => 'footer_link_icon_2',
						'type' => 'text',
						'title' => __('Icône du lien 2', 'extra-admin'),
					),
					array(
						'id' => 'footer_link_subtitle_2',
						'type' => 'text',
						'title' => __('Sous titre du lien 2', 'extra-admin'),
					),
					array(
						'id' => 'footer_link_url_2',
						'type' => 'text',
						'title' => __('Url du lien 2', 'extra-admin'),
					),
					array(
						'id' => 'footer_link_separator_2',
						'type' => 'section',
					),



					array(
						'id' => 'footer_link_title_3',
						'type' => 'text',
						'title' => __('Titre du lien 3', 'extra-admin'),
					),
					array(
						'id' => 'footer_link_icon_3',
						'type' => 'text',
						'title' => __('Icône du lien 3', 'extra-admin'),
					),
					array(
						'id' => 'footer_link_subtitle_3',
						'type' => 'text',
						'title' => __('Sous titre du lien 3', 'extra-admin'),
					),
					array(
						'id' => 'footer_link_url_3',
						'type' => 'text',
						'title' => __('Url du lien 3', 'extra-admin'),
					),
					array(
						'id' => 'footer_link_separator_3',
						'type' => 'section',
					),
				)
			);

			$sections = apply_filters('extra_add_global_options_section', array());
			if (!empty($sections)) {
				foreach ($sections as $section) {
					$this -> sections[] = $section;
				}
			}
		}


		// PANNEAUX D'AIDES
		public function setHelpTabs() {

			// Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
			$this -> args['help_tabs'][] = array('id' => 'redux-opts-1', 'title' => __('Theme Information 1', 'extra-admin'), 'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'extra-admin'));

			// Set the help sidebar
			$this -> args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'extra-admin');

		}


		// Create the sections and fields
		public function setArguments() {

			$this -> args = array(

				// TYPICAL -> Change these values as you need/desire
				'opt_name' => 'extra_options', // This is where your data is stored in the database and also becomes your global variable name.
				'display_name' => __('Paramètres du thème Extra', 'extra-admin'), // Name that appears at the top of your panel
				'display_version' => '1.0', // Version that appears at the top of your panel
				'menu_type' => 'menu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
				'allow_sub_menu' => false, // Show the sections below the admin menu item or not
				'menu_title' => __('Paramètres', 'extra-admin'), 'page' => __('Paramètres', 'extra-admin'), 'google_api_key' => '', // Must be defined to add google fonts to the typography module
				'global_variable' => '', // Set a different name for your global variable other than the opt_name
				'dev_mode' => false, // Show the time the page took to load, etc
				'customizer' => true, // Enable basic customizer support

				// OPTIONAL -> Give you extra features
				'page_priority' => 30, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
				'page_parent' => 'themes.php', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
				'page_permissions' => 'manage_options', // Permissions needed to access the options panel.
				'menu_icon' => 'dashicons-admin-tools', // Specify a custom URL to an icon
				'last_tab' => '', // Force your panel to always open to a specific tab (by id)
				'page_icon' => 'icon-themes', // Icon displayed in the admin panel next to your menu_title
				'page_slug' => '_options', // Page slug used to denote the panel
				'save_defaults' => true, // On load save the defaults to DB before user clicks save or not
				'default_show' => false, // If true, shows the default value next to each field that is not the default value.
				'default_mark' => '', // What to print by the field's title if the value shown is default. Suggested: *

				// CAREFUL -> These options are for advanced use only
				'transient_time' => 60 * MINUTE_IN_SECONDS, 'output' => true, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
				'output_tab' => true, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
				//'domain'             	=> 'redux-framework', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
				//'footer_credit'      	=> '', // Disable the footer credit of Redux. Please leave if you can help it.

				// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
				'database' => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!

				'show_import_export' => false, // REMOVE
				'system_info' => false, // REMOVE

				'help_tabs' => array(), 'help_sidebar' => '', // __( '', $this->args['domain'] );
			);

			// header
			//$this->args['intro_text'] = '';

			// Add content after the form.
			//$this->args['footer_text'] = '';

		}

	}

	function init_global_options () {
		new Redux_Framework_sample_config();
	}
	add_action('init', 'init_global_options');

}