<?php
namespace WowpiGuild\Frontend;
use WowpiGuild\Api\Guild;
use WowpiGuild\Config\Settings;
use WowpiGuild\Includes\WowpiCron;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wow-hunter.ro
 * @since      1.0.0
 *
 * @package    Wowpi_Guild
 * @subpackage Wowpi_Guild/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wowpi_Guild
 * @subpackage Wowpi_Guild/public
 * @author     Adrian Voicu - Avenirer <adrian@avenir.ro>
 */
class FrontEnd {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wowpi_Guild_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wowpi_Guild_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, Settings::pluginUrl() . 'dist/public/css/wowpi-guild-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wowpi_Guild_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wowpi_Guild_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wowpi-public-script
		wp_enqueue_script( 'wowpi-guild-public-script', Settings::pluginUrl() . 'dist/public/js/wowpi-guild-public.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( 'wowpi-guild-public-script', 'wowpiGuildPublicAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php'), 'ajaxnoncepublic' => wp_create_nonce('ajax_public_validation') ));
		wp_localize_script( 'wowpi-guild-public-script', 'wowpiRosterAjax', array( 'ajaxurl' => admin_url('admin-ajax.php?action=getRoster')) );

	}

	public function getRemoteData() {

		if( ! check_ajax_referer('ajax_public_validation', 'security', false)) {
			echo 'Zug-zug';
			die();
		}

		// only these operations are available
		$available_operations = array(
			'roster',
			'character'
		);

		$retrieve = sanitize_text_field($_REQUEST['retrieve']);
		$forced = boolval(sanitize_text_field($_REQUEST['forced']));

		if( ! in_array($retrieve, $available_operations)) {
			echo 'Zug-zug';
			die();
		}

		if($retrieve == 'roster') {
			$result = $this->importRemoteGuild($forced);
		}
		elseif($retrieve == 'character') {
			$characterId = intval(sanitize_text_field($_REQUEST['characterId']));
			$synchAll = boolval(sanitize_text_field($_REQUEST['synchAll']));
			if(!$characterId) {
				return false;
			}
			$result = $this->importRemoteCharacter($characterId, $forced, $synchAll);
		}

		// Check if action was fired via Ajax call. If yes, JS code will be triggered, else the user is redirected to the post page
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$result = json_encode($result);
			echo $result;
		}
		else {
			header("Location: ".$_SERVER["HTTP_REFERER"]);
		}

		die();
	}

	private function importRemoteGuild($forced) {

		$minimumRefreshRate = 60*60*24;
		if($forced && ! is_user_logged_in()) {
			var_dump('User not logged in');
			exit;
		}

		$guildOption = get_option('wowpi_guild_guild');
		$lastImport = array_key_exists('last_import', $guildOption) ? $guildOption['last_import'] : 0;
		//$lastImport = 0;
		$importDate = time();

		if( $forced || ( ( $importDate - $lastImport ) > $minimumRefreshRate ) ) {

			$connector = new Guild();
			$connector->guild();
			$rosterRemoteData = $connector->roster();
			$roster = $rosterRemoteData['roster'];
		}

		else {
			$rosterRemoteData = Settings::getRoster();
			$roster = $rosterRemoteData['roster'];
		}


		return array(
			'type' => 'success',
			'data' => array(
				'roster' => $roster,
			),
		);

	}

	private function importRemoteCharacter($characterId, $forced, $synchAll = false) {

		$minimumRefreshRate = 60*60*24;
		$characterId = intval($characterId);
		$guildRoster = Settings::getRoster();
		$roster = $guildRoster['roster'];

		if(! array_key_exists($characterId, $roster)) {
			return false;
		}

		$remoteCharacter = $roster[$characterId];

		$characterSlug = sanitize_title($remoteCharacter['name']).'-'.$remoteCharacter['id'];

		$characterData = array(
			'post_title' => $remoteCharacter['name'],
			'post_name' => $characterSlug,
			'post_status' => 'publish',
			'post_type' => 'wowpi_guild_member',
		);

		$args     = array(
			'name'        => $characterSlug,
			'post_type'   => 'wowpi_guild_member',
			'post_status' => 'publish',
			'numberposts' => 1
		);

		$characterPosts = get_posts( $args );
		if ( $characterPosts ) {
			$characterPostId = $characterPosts[0]->ID;
		}
		else {
			$characterPostId = wp_insert_post( $characterData );
		}

		$realmTerm = get_term_by('slug', $remoteCharacter['realm']['slug'], 'wowpi_guild_realm', ARRAY_A);
		if(!$realmTerm) {
			$realms = Settings::getRealms();
			if(array_key_exists($remoteCharacter['realm']['id'], $realms)) {
				$remoteRealm = $realms[$remoteCharacter['realm']['id']];
				$realmTerm = wp_insert_term($remoteRealm['name'], 'wowpi_guild_realm', array('slug' => $remoteRealm['slug']));
			}
		}
		wp_set_post_terms($characterPostId, array($realmTerm['term_id']), 'wowpi_guild_realm');

		$races = Settings::getRaces();
		$characterRace = array_key_exists($remoteCharacter['race_id'], $races) ? $races[$remoteCharacter['race_id']] : false;

		if($characterRace) {
			$raceSlug = sanitize_title($characterRace['name']).'-'.$characterRace['id'];
			$raceTerm = get_term_by('slug', $raceSlug, 'wowpi_guild_race', ARRAY_A);
			if(!$raceTerm) {
				$raceTerm = wp_insert_term($characterRace['name'], 'wowpi_guild_race', array('slug' => $raceSlug));
			}
			wp_set_post_terms($characterPostId, array($raceTerm['term_id']), 'wowpi_guild_race');
		}

		$classes = Settings::getClasses();

		$characterClass = array_key_exists($remoteCharacter['class_id'], $classes) ? $classes[$remoteCharacter['class_id']] : false;

		if($characterClass) {
			$classSlug = sanitize_title($characterClass['name']).'-'.$characterClass['id'];
			$classTerm = get_term_by('slug', $classSlug, 'wowpi_guild_class_spec', ARRAY_A);
			if(!$classTerm) {
				$classTerm = wp_insert_term($characterClass['name'], 'wowpi_guild_class_spec', array('slug' => $classSlug));
			}
			wp_set_post_terms($characterPostId, array($classTerm['term_id']), 'wowpi_guild_class_spec');
		}

		update_field( 'bnet_id', $characterId, $characterPostId );
		update_field('character_level', $remoteCharacter['level'], $characterPostId);
		update_field('guild_rank', $remoteCharacter['rank'], $characterPostId);

		if($synchAll) {
			$cronJob       = new WowpiCron();
			$characterPost = get_post( $characterPostId );
			$status        = $cronJob->updateCharacter( $characterPost );
		}

		return array(
			'type' => 'success',
			'data' => array(
				'message' => 'The Guild member <strong>'.$characterData['post_title']. '</strong> was '.(isset($status) ? $status : 'updated').'.',
			),
		);
	}

}
