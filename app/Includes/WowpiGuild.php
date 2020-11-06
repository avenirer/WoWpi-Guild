<?php
namespace WowpiGuild\Includes;

use WowpiGuild\Admin\Admin;
use WowpiGuild\Admin\AdminSettings;
use WowpiGuild\Frontend\FrontEnd;
use WowpiGuild\Widgets\Widgets;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wow-hunter.ro
 * @since      1.0.0
 *
 * @package    Wowpi_Guild
 * @subpackage Wowpi_Guild/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wowpi_Guild
 * @subpackage Wowpi_Guild/includes
 * @author     Adrian Voicu - Avenirer <adrian@avenir.ro>
 */
class WowpiGuild {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

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
		if ( defined( 'WOWPI_GUILD_NAME' ) ) {
			$this->plugin_name = WOWPI_GUILD_NAME;
		}
		if ( defined( 'WOWPI_GUILD_VERSION' ) ) {
			$this->version = WOWPI_GUILD_VERSION;
		}

		$this->loadDependencies();
		$this->setLocale();
		$this->defineContentTypes();
		$this->defineTaxonomies();
		$this->defineCustomFields();
		$this->defineAdminHooks();
		$this->definePublicHooks();
		$this->defineCronJobs();
		$this->defineShortcodes();
		$this->defineWidgets();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Loader. Orchestrates the hooks of the plugin.
	 * - I18n. Defines internationalization functionality.
	 * - Admin. Defines all hooks for the admin area.
	 * - FrontEnd. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function loadDependencies() {

		$this->loader = new Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wowpi_Guild_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function setLocale() {

		$plugin_i18n = new I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function defineContentTypes() {
		$contentTypes = new ContentTypes();
		$contentTypes->activate();
	}

	private function defineTaxonomies() {
		$taxonomies = new Taxonomies();
		$taxonomies->activate();
	}

	private function defineCustomFields() {
		$customFields = new CustomFields();
		$customFields->activate();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function defineAdminHooks() {

		$pluginAdmin = new Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $pluginAdmin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $pluginAdmin, 'enqueue_scripts' );
		$this->loader->add_action('wp_ajax_getRemoteDataRegistered', $pluginAdmin, 'getRemoteDataRegistered');

		$pluginPublic = new FrontEnd( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action('wp_ajax_nopriv_getRemoteData', $pluginPublic, 'getRemoteData');

		$plugin_settings = new AdminSettings( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_menu', $plugin_settings, 'setup_plugin_options_menu' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_credentials_settings' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_realm_guild_settings' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_synchronizing' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function definePublicHooks() {

		$frontEnd = new FrontEnd( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $frontEnd, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $frontEnd, 'enqueue_scripts' );


		$this->loader->add_action('wp_ajax_getRemoteData', $frontEnd, 'getRemoteData');
		$this->loader->add_action('wp_ajax_nopriv_getRemoteData', $frontEnd, 'getRemoteData');

		$dataTables = new DataTables();
		$this->loader->add_action('wp_ajax_getRoster', $dataTables, 'getRoster');
		$this->loader->add_action('wp_ajax_nopriv_getRoster', $dataTables, 'getRoster');

	}

	private function defineCronJobs() {
		$cron = new WowpiCron();
		$cron->init();
	}

	private function defineShortcodes() {

		$shortcodes = new Shortcodes();
		$this->loader->add_action('init', $shortcodes, 'init' );

	}

	private function defineWidgets() {
		$widgets = new Widgets();
		$this->loader->add_action('widgets_init', $widgets, 'init');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
