<?php

/**
 * 
 * Init dependecies
 * 
 * @link       csorbamedia.com
 * @since      1.3
 *
 * @package    private_wordpress_files
 * @subpackage private_wordpress_files/includes
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class PWPF_init {

    /**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'protect-wordpress-files';
		$this->version     = '1.2';
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->set_locale();

	}

	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/pwpf-loader.php';

		/**
		 * The class responsible to show admin messages
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/pwpf-messages.php';

		/**
		 * The class responsible for WooCommerce hooks
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/pwpf-hooks.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/pwpf-i18n.php';

		$this->loader 	= new PWPF_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function define_admin_hooks() {

		$wphooks		= new PWPF_Hooks();

		// Admin pages
		$this->loader->add_action( 'admin_menu', $wphooks, 'PWPF_load_admin_pages');

		// Ajax upload function
		$this->loader->add_action( 'wp_ajax_upload_private_media', $wphooks, 'PWPF_upload_private_media');

		// Filter to change the url by array
		$this->loader->add_filter( 'private_media_url_by_array', $wphooks, 'PWPF_url_by_array', 10, 1);

		// Filter to change the url by content
		$this->loader->add_filter( 'private_media_url_by_slug', $wphooks, 'PWPF_url_by_slug', 10, 1);

		// Check the content for private content
		$this->loader->add_filter( 'the_content', $wphooks, 'PWPF_content_filter');

		// Change the view link in the backend
		$this->loader->add_filter( 'media_row_actions', $wphooks, 'PWPF_medialib_view_link', 10, 2);

		// Add an extra column for private file urls
		$this->loader->add_filter( 'manage_media_columns', $wphooks, 'PWPF_media_columns', 99999, 1 );
		$this->loader->add_action( 'manage_media_custom_column', $wphooks, 'PWPF_media_custom_column', 10, 2 );

		// Admin CSS
		$this->loader->add_action( 'admin_footer', $wphooks, 'PWPF_backend_style');

		// Action to cp files to the private folder
		$this->loader->add_action( 'current_screen', $wphooks, 'PWPF_protect_file');

		// Action to cp files from private folder to public folder
		$this->loader->add_action( 'current_screen', $wphooks, 'PWPF_unprotect_file');

		// Ajax function to update the admin notices
		$this->loader->add_action( 'wp_ajax_private_media_notice_handler', $wphooks, 'PWPF_notice_handler');

		// Add query vars
		$this->loader->add_filter( 'query_vars', $wphooks, 'PWPF_add_query_vars' );

		// Add rewrite rule
		$this->loader->add_action( 'init', $wphooks, 'PWPF_add_rewrite_rule', 10, 0);

		// Filter to download the files
		$this->loader->add_action( 'parse_query', $wphooks, 'PWPF_handle_private_download' );

		// Attachment filter used to update the meta_data for private files
		$this->loader->add_filter('wp_generate_attachment_metadata', $wphooks, 'PWPF_acf_select_attachment', 10, 2);

		// Redirect admin to welcome page ater activating the plugin
		$this->loader->add_action( 'admin_init', $wphooks, 'PWPF_redirect_admin');

		// Hook to check current webserver
		$this->loader->add_action( 'admin_init', $wphooks, 'PWPF_check_webserver' );

		// Register the page for the wizard.
		$this->loader->add_action( 'admin_menu', $wphooks, 'PWPF_add_wizard_page' );
		$this->loader->add_action( 'admin_enqueue_scripts', $wphooks, 'PWPF_enqueue_assets' );

		// Add user capability
		$this->loader->add_action('init', $wphooks, 'PWPF_add_caps', 11);

		// This will a do some ajax request to filter in attachments grid view
		$this->loader->add_filter( 'ajax_query_attachments_args', $wphooks, 'PWPF_ajax_query_filter', 10, 1 );
		$this->loader->add_action( 'admin_enqueue_scripts', $wphooks, 'PWPF_attachment_filter');

		// This will add a dropdown in attachments list view
		$this->loader->add_action( 'restrict_manage_posts', $wphooks, 'PWPF_add_dropdown_filter_list', 10, 1 );
		$this->loader->add_action( 'pre_get_posts', $wphooks, 'PWPF_query_filter' );

		// This will add some custom links to the plugins.php page
		$this->loader->add_filter( 'plugin_action_links_' . PWPF_BASE, $wphooks, 'PWPF_plugin_links');

		// Filter to view private thumbnails
		$this->loader->add_action( 'parse_query', $wphooks, 'PWPF_handle_private_thumb' );

		// Filter to change the private thumbnail icons
		$this->loader->add_filter( 'wp_get_attachment_url', $wphooks, 'PWPF_handle_image_url', 11, 2);

		// Filter to change add some information to the thumb
		$this->loader->add_filter( 'wp_prepare_attachment_for_js', $wphooks, 'PWPF_prepare_attachment_for_js', 10, 3 );

		// Filter to change add the protected link when doing ajax in media library grid view
		$this->loader->add_filter( 'attachment_fields_to_edit', $wphooks, 'PWPF_attachment_field_to_edit', 10, 2 );


	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bucket_Auth_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new PWPF_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}


}