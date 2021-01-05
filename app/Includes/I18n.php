<?php
namespace WowpiGuild\Includes;

use WowpiGuild\Config\Settings;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wow-hunter.ro
 * @since      1.0.0
 *
 * @package    Wowpi_Guild
 * @subpackage Wowpi_Guild/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wowpi_Guild
 * @subpackage Wowpi_Guild/includes
 * @author     Adrian Voicu - Avenirer <adrian@avenir.ro>
 */
class I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wowpi-guild',
			false,
			'wowpi-guild/languages'
		);

	}



}
