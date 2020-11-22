<?php
namespace WowpiGuild\Config;

use WowpiGuild\Api\Guild;
use WowpiGuild\Api\PlayableClass;
use WowpiGuild\Api\PlayableRace;
use WowpiGuild\Api\PlayableSpecialization;
use WowpiGuild\Api\Realm;
use WowpiGuild\Includes\Connector;

class Settings {

	public static function pluginPath() {
		$pluginPath = plugin_dir_path( __FILE__ );
		$pluginPath = substr($pluginPath, 0, strpos($pluginPath, 'app'.DIRECTORY_SEPARATOR));
		return $pluginPath;

	}

	public static function pluginUrl() {

		$pluginDirectoryUrl = plugin_dir_url( __FILE__ );
		$pluginDirectoryUrl = substr($pluginDirectoryUrl, 0, strpos($pluginDirectoryUrl, 'app/'));
		return $pluginDirectoryUrl;
	}

	public static function getRealms() {
		$realms = get_option('wowpi_guild_realms');
		if(!$realms) {
			$connector = new Realm();
			$realms = $connector->index();
		}
		return $realms;
	}

	public static function getRaces() {
		$races = get_option('wowpi_guild_races');
		if(!$races) {
			$connector = new PlayableRace();
			$races = $connector->index();
		}
		return $races;
	}

	public static function getClasses() {
		$classes = get_option('wowpi_guild_classes');
		if(empty($classes)) {
			$connector = new PlayableClass();
			$classes = $connector->index();
		}
		return $classes;
	}

	public static function getClass($playableClassId) {
		$class = get_option('wowpi_guild_class_'.$playableClassId);
		if(!$class) {
			$connector = new PlayableClass();
			$class = $connector->getClass($playableClassId);
		}
		return $class;
	}

	public static function getClassSpecs($playableClassId) {
		$classSpecs = get_option('wowpi_guild_class_'.$playableClassId.'_specs');
		if(!$classSpecs) {
			$connector = new PlayableSpecialization();
			$classSpecs = $connector->getClassSpecializations($playableClassId);
		}
		return $classSpecs;
	}

	public static function getGuild() {
		$guild = get_option('wowpi_guild_guild');
		if(!$guild) {
			$connector = new Guild();
			$guild = $connector->guild();
		}
		return $guild;
	}

	/**
	 * @param false $refreshRoster - if we want to make sure we have the newest roster
	 *
	 * @return array|false|mixed|void
	 */
	public static function getRoster($refreshRoster = false) {
		$members = false;
		if(! $refreshRoster) {
			$members = get_option( 'wowpi_guild_guild_roster' );
		}
		if( ! $members) {
			$connector = new Guild();
			$members = $connector->roster();
		}
		return $members;
	}



}