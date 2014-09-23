<?php

/**
ReduxFramework Sample Config File
For full documentation, please visit: https://github.com/ReduxFramework/ReduxFramework/wiki
 **/

if (!class_exists("ReduxFramework")) {
	return;
}

if (!class_exists("Extra_Redux_Framework")) {

	class Extra_Redux_Framework {

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
			$this->sections[] = array(
				'icon' => 'el-icon-picture',
				'title' => __('Images par défaut', 'extra-admin'),
				'desc' => null,
				'fields' => array(
					array(
						'id' => 'default-thumbnail',
						'type' => 'media',
						'title' => __('Image générique par défaut', 'extra-admin'),
					),
					array(
						'id' => 'default-thumbnail-small',
						'type' => 'media',
						'title' => __('Image générique par défaut (petite taille)', 'extra-admin'),
					)
				)
			);

            $this->sections = apply_filters('extra_default_global_options_section', $this->sections);
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
				'display_version' => '', // Version that appears at the top of your panel
				'menu_type' => 'menu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
				'allow_sub_menu' => true, // Show the sections below the admin menu item or not
				'menu_title' => __('Paramètres', 'extra-admin'), 'page' => __('Paramètres', 'extra-admin'), 'google_api_key' => '', // Must be defined to add google fonts to the typography module
				'global_variable' => '', // Set a different name for your global variable other than the opt_name
				'dev_mode' => false, // Show the time the page took to load, etc
				'customizer' => true, // Enable basic customizer support
				'admin_bar' => false, // Show the panel pages on the admin bar

				// OPTIONAL -> Give you extra features
				'page_priority' => 58, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
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
        new Extra_Redux_Framework();
    }
    add_action('init', 'init_global_options');

}