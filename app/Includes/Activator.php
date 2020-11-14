<?php
namespace WowpiGuild\Includes;
/**
 * Fired during plugin activation
 *
 * @link       https://wow-hunter.ro
 * @since      1.0.0
 *
 * @package    Wowpi_Guild
 * @subpackage Wowpi_Guild/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wowpi_Guild
 * @subpackage Wowpi_Guild/includes
 * @author     Adrian Voicu - Avenirer <adrian@avenir.ro>
 */
class Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		//self::add_default_options();
		//self::create_directories();

	}

	public static function add_default_options() {

		// login credentials for Battle.net
		$credentials = array(
			'client_id'    =>  '',
			'client_secret'    =>  '',
			'game' => 'current',
			'region' => 'eu',
			'locale' => 'en_GB',
		);
		add_option( 'wowpi_guild_credentials', $credentials );

		// guild data
		$guild = array();
		add_option( 'wowpi_guild_guild', $guild );

		add_option('wowpi_guild_races', array());
		add_option('wowpi_guild_classes', array());
	}

	public static function create_directories() {
		/*

		$plugin_dir = plugin_dir_path( dirname( __FILE__ ) );

		$imageDir = $plugin_dir.'assets/images/wow/';
		if(file_exists($imageDir) && wp_is_writable($plugin_dir.'assets/images/')) {
			removeDirectory($imageDir);
		}

		$uploadDir = wp_upload_dir();
		$wowpiUploadDir = $uploadDir['basedir'].'/wowpi/';
		if(file_exists($wowpiUploadDir) && wp_is_writable($uploadDir['basedir'].'/')) {
			removeDirectory($wowpiUploadDir);
		}
		*/
	}

}
