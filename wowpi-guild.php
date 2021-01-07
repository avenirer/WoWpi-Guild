<?php
require_once('vendor/autoload.php');

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wow-hunter.ro
 * @since             1.4.5
 * @package           Wowpi_Guild
 *
 * @wordpress-plugin
 * Plugin Name:       WoWpi Guild
 * Plugin URI:        wowpi-guild
 * Description:       You want a proper World of Warcraft's guild website but you don't know how? Look no further. This is the plugin for your guild's needs. It imports everything you need related to the guild members,and gives you the basis for a good guild website.
 * Version:           1.4.5
 * Author:            Adrian Voicu - Avenirer
 * Author URI:        https://wow-hunter.ro
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wowpi-guild
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * MAKE SURE WE HAVE ACF - just in case
 */
// Define path and URL to the ACF plugin.
define( 'WOWPI_GUILD_ACF_PATH', plugin_dir_path( __FILE__ ) . 'includes/acf/' );
define( 'WOWPI_GUILD_ACF_URL', plugin_dir_url( __FILE__ ) . 'includes/acf/' );

// Include the ACF plugin.
include_once( WOWPI_GUILD_ACF_PATH . 'acf.php' );

// Customize the url setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url( $url ) {
	return WOWPI_GUILD_ACF_URL;
}

add_filter('acf/settings/show_admin', 'my_acf_settings_show_admin');
function my_acf_settings_show_admin( $show_admin ) {
	return true;
	//return false;
}

/**
 * END ACF SETUP
 */

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOWPI_GUILD_VERSION', '1.4.5' );
define( 'WOWPI_GUILD_NAME', 'wowpi-guild');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wowpi-guild-activator.php
 */
function activate_wowpi_guild() {
	\WowpiGuild\Includes\Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wowpi-guild-deactivator.php
 */
function deactivate_wowpi_guild() {
	\WowpiGuild\Includes\Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wowpi_guild' );
register_deactivation_hook( __FILE__, 'deactivate_wowpi_guild' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
//require plugin_dir_path( __FILE__ ) . 'includes/class-wowpi-guild.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wowpi_guild() {

	$plugin = new \WowpiGuild\Includes\WowpiGuild();
	$plugin->run();

}
run_wowpi_guild();
