<?php
namespace WowpiGuild\Api;

use WowpiGuild\Config\Settings;

class Guild extends Connector {

	protected $realmSlug;
	protected $nameSlug;

	public function __construct($realmSlug = null, $nameSlug = null, $credentials = array()) {

		parent::__construct($credentials);

		$guildData = array();
		if(!isset($realmSlug) && !isset($nameSlug)) {
			$guildData = Settings::getGuild();
		}

		if(isset($realmSlug)) {
			$this->realmSlug = sanitize_text_field($realmSlug);
		}
		elseif(array_key_exists('realm_slug', $guildData)) {
			$this->realmSlug = $guildData['realm_slug'];
		}
		if(isset($nameSlug)) {
			$this->nameSlug = sanitize_text_field($nameSlug);
		}
		elseif (array_key_exists('slug', $guildData)) {
			$this->nameSlug = $guildData['slug'];
		}

		if(!isset($this->realmSlug) || !isset($this->nameSlug)) {
			error_log('I do not have realm slug, nor guild name slug');
			exit;
		}

	}
	public function guild($updateOption = true) {

		//https://eu.api.blizzard.com/data/wow/guild/ravenholdt/direwolves?namespace=profile-eu&locale=en_US&access_token=USwOm7LCbQk562Vk2lwI8L0YqtgPJBdL4h
		$endpoint = $this->getDomain().'/data/wow/guild/'.$this->realmSlug.'/'.$this->nameSlug.'?namespace='.$this->getNamespace('profile').'&locale='.$this->locale.'&access_token='.$this->getToken();

		$response = $this->retrieveData($endpoint);

		if(array_key_exists('code', $response) && $response['code'] == '404') {
			error_log('No guild found for realm '.$this->realmSlug . ' and guild slug '. $this->nameSlug);
			return false;
		}

		if($response) {
			$guild = array(
				'name'               => $response['name'],
				'slug'               => $this->nameSlug,
				'faction'            => $response['faction'],
				'realm_slug'         => $response['realm']['slug'],
				'realm_name'         => $response['realm']['name'],
				'realm_id'           => $response['realm']['id'],
				'achievement_points' => $response['achievement_points'],
				'member_count'       => $response['member_count'],
				'crest'              => $response['crest'],
				'last_update'       => time(),
			);
			if($updateOption) {
				update_option('wowpi_guild_guild', $guild);
			}
			return $guild;
		}
		return false;
	}

	public function activity($realmSlug, $nameSlug) {

	}

	public function achievements($realmSlug, $nameSlug) {

	}

	public function roster($updateOption = true) {

		//https://eu.api.blizzard.com/data/wow/guild/ravenholdt/direwolves/roster?namespace=profile-eu&locale=en_US&access_token=USwOm7LCbQk562Vk2lwI8L0YqtgPJBdL4h
		$endpoint = $this->getDomain().'/data/wow/guild/'.$this->realmSlug.'/'.$this->nameSlug.'/roster?namespace='.$this->getNamespace('profile').'&locale='.$this->locale.'&access_token='.$this->getToken();

		$response = $this->retrieveData($endpoint);

		if(array_key_exists('code', $response) && $response['code'] == '404') {
			error_log('No guild found for realm '.$this->realmSlug . ' and guild slug '. $this->nameSlug);
			return false;
		}

		$members = array();
		if(array_key_exists('members', $response)) {
			foreach ( $response['members'] as $member ) {
				$rank                        = $member['rank'];
				$character                   = $member['character'];
				$members[ $character['id'] ] = array(
					'id'       => $character['id'],
					'name'     => $character['name'],
					'rank'     => $rank,
					'realm'    => array(
						'id'   => $character['realm']['id'],
						'slug' => $character['realm']['slug'],
					),
					'level'    => $character['level'],
					'class_id' => $character['playable_class']['id'],
					'race_id'  => $character['playable_class']['id'],
				);
			}
		}

		$guild = array(
			'last_update' => time(),
			'roster' => $members,
		);

		if(!empty($members) && $updateOption) {
			update_option('wowpi_guild_guild_roster', $guild);
		}

		return $guild;

	}
}