<?php
namespace WowpiGuild\Admin;

use WowpiGuild\Api\PlayableClass;
use WowpiGuild\Api\PlayableRace;
use WowpiGuild\Api\PlayableSpecialization;
use WowpiGuild\Config\Settings;
//use WowpiGuild\Includes\Connector;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wow-hunter.ro
 * @since      1.0.0
 *
 * @package    Wowpi_Guild
 * @subpackage Wowpi_Guild/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wowpi_Guild
 * @subpackage Wowpi_Guild/admin
 * @author     Adrian Voicu - Avenirer <adrian@avenir.ro>
 */
class Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, Settings::pluginUrl() . 'dist/admin/css/wowpi-guild-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( 'wowpi-guild-admin-script', Settings::pluginUrl() . 'dist/admin/js/wowpi-guild-admin.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'wowpi-guild-public-script', Settings::pluginUrl() . 'dist/public/js/wowpi-guild-public.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( 'wowpi-guild-admin-script', 'wowpiGuildAdminAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php'), 'ajaxnonce' => wp_create_nonce('ajax_validation') ));
		wp_localize_script( 'wowpi-guild-public-script', 'wowpiGuildPublicAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php'), 'ajaxnoncepublic' => wp_create_nonce('ajax_public_validation') ));

	}

	public function getRemoteDataRegistered() {

		if( ! check_ajax_referer('ajax_validation', 'security', false)) {
			echo 'Zug-zug';
			die();
		}

		// only these operations are available
		$available_operations = array(
			'races',
			'classes',
			'specializations',
			'achievementCategories',
			'achievements'
		);

		$retrieve = sanitize_text_field($_REQUEST['retrieve']);

		if( ! in_array($retrieve, $available_operations)) {
			echo 'Zug-zug';
			die();
		}

		if($retrieve == 'specializations') {
			$classId = intval(sanitize_text_field($_REQUEST['classId']));
			$result = $this->importRemoteSpecs($classId);
		}
		elseif($retrieve == 'classes') {
			$result = $this->getRemoteClasses();
		}
		elseif($retrieve == 'achievementCategories') {
			$result = $this->getRemoteAchievementCategories();
		}
		elseif($retrieve == 'achievements') {
			$categoryId = intval( sanitize_text_field($_REQUEST['categoryId']));
			$result = $this->importRemoteAchievements($categoryId);
		}
		else {
			$result = $this->{'importRemote' . ucfirst( $retrieve )}();
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

	private function importRemoteRaces() {

		$connector = new PlayableRace();
		$races = $connector->index();

		$inserted = 0;
		$updated = 0;
		if(!empty($races)) {
			foreach($races as $race) {
				$term_name = $race['name'];
				$term_slug = sanitize_title($term_name) . '-' . $race['id'];
				$term = get_term_by('slug', $term_slug, 'wowpi_guild_race');
				if( ! $term ) {
					$term = wp_insert_term( $race['name'], 'wowpi_guild_race', array('slug' => $term_slug) );
					$term_id = $term['term_id'];
					if(is_array($term)) {
						$aboutRace        = $connector->getRace( $race['id'] );
						update_field( 'wowpi_guild_male', $aboutRace['male'], 'wowpi_guild_race_' . $term_id );
						update_field( 'wowpi_guild_female', $aboutRace['female'], 'wowpi_guild_race_' . $term_id );
					}
					$inserted += 1;
				}
				else {
					$term_id = $term->term_id;
					$updated += 1;
				}
				$bnet_id = get_field('bnet_id', 'wowpi_guild_race_'.$term_id);
				if( $bnet_id != $race['id'] ) {
					update_field( 'bnet_id', $race['id'], 'wowpi_guild_race_' . $term_id );
				}
			}
		}

		return array(
			'type' => 'success',
			'data' => array(
				'inserted' => $inserted,
				'updated' => $updated,
			),
		);
	}

	/*
	private function getRemoteAchievementCategories() {

		$connector = new Connector();

		$achievementCategories = $connector->getAchievementCategories();

		return array(
			'type' => 'success',
			'data' => array(
				'categories' => $achievementCategories,
			),
		);
	}
	*/

	private function importRemoteClass($classId) {

		$classConnector = new PlayableClass();
		$aboutClass        = $classConnector->getClass( $classId );
		$term_name = $aboutClass['name'];
		$term_slug = sanitize_title($term_name) . '-' . $aboutClass['id'];

		$term = get_term_by('slug', $term_slug, 'wowpi_guild_class_spec');
		if( ! $term ) {
			$term = wp_insert_term( $term_name, 'wowpi_guild_class_spec', array('slug' => $term_slug) );
			$term_id = $term['term_id'];
			if(is_array($term)) {
				update_field( 'wowpi_guild_male', $aboutClass['male'], 'wowpi_guild_class_spec_' . $term_id );
				update_field( 'wowpi_guild_female', $aboutClass['female'], 'wowpi_guild_class_spec_' . $term_id );
				update_field('wowpi_guild_power_type', $aboutClass['power_type'], 'wowpi_guild_class_spec_'.$term_id);
			}
		}
		else {
			$term_id = $term->term_id;
		}

		// Battle net ID
		$bnet_id = get_field('bnet_id', 'wowpi_guild_class_spec_'.$term_id);
		if( $bnet_id != $aboutClass['id'] ) {
			update_field( 'bnet_id', $aboutClass['id'], 'wowpi_guild_class_spec_' . $term_id );
		}

		return get_term_by('slug', $term_slug, 'wowpi_guild_class_spec');
	}



	private function getRemoteClasses() {

		$connector = new PlayableClass();

		$classes = $connector->index();

		return array(
			'type' => 'success',
			'data' => array(
				'classes' => $classes,
			),
		);
	}

	private function importRemoteSpecs($classId) {

		$classId = intval(sanitize_text_field($classId));
		$classTerm = $this->importRemoteClass($classId);
		if( ! $classTerm) {
			error_log('A remote class could not be imported!');
			return false;
		}
		$inserted = 0;
		$updated = 0;

		$aboutClass = Settings::getClass($classId);
		$connector = new PlayableSpecialization();
		$classSpecializations = $connector->getClassSpecializations( $classId );

		// Add specializations
		if(!empty($classSpecializations)) {
			foreach($classSpecializations as $spec) {
				$spec_term_name = $spec['name'];
				$spec_term_slug = sanitize_title($spec_term_name) . '-' . $spec['id'];
				$spec_term = get_term_by('slug', $spec_term_slug, 'wowpi_guild_class_spec');
				if (! $spec_term) {
					$spec_term = wp_insert_term($spec_term_name, 'wowpi_guild_class_spec', array('slug' => $spec_term_slug, 'parent' => $classTerm->term_id));
					$spec_term_id = $spec_term['term_id'];
					$inserted += 1;
				}
				else {
					$spec_term_id = $spec_term->term_id;
				}
				$updated += 1;
				update_field( 'bnet_id', $spec['id'], 'wowpi_guild_class_spec_' . $spec_term_id );
				update_field('wowpi_guild_power_type', $aboutClass['power_type'], 'wowpi_guild_class_spec_'.$spec_term_id);
				update_field('wowpi_guild_spec_role', $spec['role'],'wowpi_guild_class_spec_'.$spec_term_id);
				update_field('wowpi_guild_spec_role_type', $spec['role_type'],'wowpi_guild_class_spec_'.$spec_term_id);
				update_field('wowpi_guild_spec_description_male', $spec['male_description'], 'wowpi_guild_class_spec_'.$spec_term_id);
				update_field('wowpi_guild_spec_description_female', $spec['female_description'], 'wowpi_guild_class_spec_'.$spec_term_id);
				update_field('wowpi_guild_spec_talent_tiers', json_encode($spec['talent_tiers']), 'wowpi_guild_class_spec_'.$spec_term_id);
			}
		}

		return array(
			'type' => 'success',
			'data' => array(
				'message' => 'The class ' . $aboutClass['name'] . ' and specializations were checked and updated. ' . $inserted . ' specializations were inserted and ' . $updated . ' specializations were updated.',
			),
		);

	}

	/*
	private function importRemoteAchievements($categoryId) {

		$categoryConnector = new Connector();
		$category        = $categoryConnector->getAchievements( $categoryId );

		$term_name = $category['name'];
		$term_slug = sanitize_title($term_name) . '-' . $category['id'];

		$message = '';
		$term = get_term_by('slug', $term_slug, 'wowpi_guild_achievement');
		if( ! $term ) {
			$term = wp_insert_term( $term_name, 'wowpi_guild_achievement', array('slug' => $term_slug) );
			$term_id = $term['term_id'];
			$message .= 'The achievement category "'.$term_name.'" was created. ';
		}
		else {
			$term_id = $term->term_id;
		}

		// Battle net ID
		$bnet_id = get_field('bnet_id', 'wowpi_guild_achievement_'.$term_id);
		if( $bnet_id != $category['id'] ) {
			update_field( 'bnet_id', $category['id'], 'wowpi_guild_achievement_' . $term_id );
		}
		$achievements = 0;
		foreach($category['achievements'] as $achievement) {
			$this->importRemoteAchievement($achievement['id']);
			$achievements+=1;
		}
		$message .= $achievements. ' achievements were inserted/updated. ';

		return array(
			'type' => 'success',
			'data' => array(
				'message' => $message,
			),
		);
	}

	private function importRemoteAchievement($achievementId) {

		$connector = new Connector();
		$achievement = $connector->getAchievement($achievementId);
		$category_slug = sanitize_title($achievement['category']['name']).'-'.$achievement['category']['id'];
		$category = get_term_by('slug', $category_slug, 'wowpi_guild_achievement');

		$achievement_term_name = $achievement['name'];
		$achievement_term_slug = sanitize_title($achievement_term_name) . '-' . $achievement['id'];

		$achievement_term = get_term_by('slug', $achievement_term_slug, 'wowpi_guild_achievement');
		if (! $achievement_term) {
			$achievement_term = wp_insert_term($achievement_term_name, 'wowpi_guild_achievement', array('slug' => $achievement_term_slug, 'parent' => $category->term_id));
			$achievement_term_id = $achievement_term['term_id'];
		}
		else {
			$achievement_term_id = $achievement_term->term_id;
		}
		update_field( 'bnet_id', $achievement['id'], 'wowpi_guild_achievement_' . $achievement_term_id );
		update_field( 'achievement_data', json_encode($achievement), 'wowpi_guild_achievement_' . $achievement_term_id );

	}

	*/

}
