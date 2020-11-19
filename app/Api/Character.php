<?php
namespace WowpiGuild\Api;

use WowpiGuild\Includes\ImageManipulation;

class Character extends Connector {

	public function __construct($credentials = array()) {

		parent::__construct($credentials);

	}

	public function setRealmSlug($realmSlug) {
		$this->realmSlug = sanitize_text_field($realmSlug);
	}

	public function summary($characterName, $realmSlug = null) {

		$characterName = sanitize_text_field($characterName);

		if(isset($realmSlug)) {
			$this->realmSlug = sanitize_text_field($realmSlug);
		}

		//https://eu.api.blizzard.com/profile/wow/character/defias-brotherhood/shadoweaver?namespace=profile-eu&locale=en_US&access_token=USwOm7LCbQk562Vk2lwI8L0YqtgPJBdL4h
		$endpoint = $this->getDomain().'/profile/wow/character/'.$this->realmSlug.'/'.$characterName.'?namespace='.$this->getNamespace('profile').'&locale='.$this->locale.'&access_token='.$this->getToken();

		$response = $this->retrieveData($endpoint);

		if(! $response || ! array_key_exists('id', $response)) {
			//error_log('Weird...' . $endpoint);
			return false;
		}

		$characterData =  array(
			'id' => $response['id'],
			'name' => $response['name'],
			'gender' => $response['gender'],
			'faction' => $response['faction'],
			'race' => array(
				'id' => $response['race']['id'],
				'name' => $response['race']['name'],
			),
			'character_class' => array(
				'id' => $response['character_class']['id'],
				'name' => $response['character_class']['name'],
			),
			'active_spec' => array(
				'id' => $response['active_spec']['id'],
				'name' => $response['active_spec']['name'],
			),
			'realm' => array(
				'id' => $response['realm']['id'],
				'name' => $response['realm']['name'],
				'slug' => $response['realm']['slug'],
			),
			'guild' => array(
				'id' => $response['guild']['id'],
				'name' => $response['guild']['name'],
				'realm' => array(
					'id' => $response['guild']['realm']['id'],
					'name' => $response['guild']['realm']['name'],
					'slug' => $response['guild']['realm']['slug'],
				),
			),
			'level' => $response['level'],
			'experience' => $response['experience'],
			'achievement_points' => $response['achievement_points'],
			'last_login' => $response['last_login_timestamp'],
			'average_item_level' => $response['average_item_level'],
			'equipped_item_level' => $response['equipped_item_level'],
		);

		if(array_key_exists('active_title', $response)) {
			$characterData['active_title'] = array(
				'id' => $response['active_title']['name'],
				'name' => $response['active_title']['name'],
				'display_string' => $response['active_title']['display_string'],
			);
		}

		return $characterData;
	}

	public function media($characterName, $realmSlug = null) {

		$characterName = sanitize_text_field($characterName);

		if(isset($realmSlug)) {
			$this->realmSlug = sanitize_text_field($realmSlug);
		}

		//https://eu.api.blizzard.com/profile/wow/character/ravenholdt/toastzor/character-media?namespace=profile-eu&locale=en_US&access_token=USB2nL3kX7w0uDL8Qzs7F3pPfyRO8q2lWv
		$endpoint = $this->getDomain().'/profile/wow/character/'.$this->realmSlug.'/'.$characterName.'/character-media?namespace='.$this->getNamespace('profile').'&locale='.$this->locale.'&access_token='.$this->getToken();

		$response = $this->retrieveData($endpoint);

		if(array_key_exists('assets', $response)) {
			$medias = array();
			$newAssetStructure = array('avatar' => 'avatar', 'inset' => 'bust', 'main' => 'render', 'main-raw' => 'raw');
			foreach($response['assets'] as $asset) {
				$medias[$newAssetStructure[$asset['key']]] = $asset['value'];
			}
		}

		else {

			$medias = array(
				'avatar' => $response['avatar_url'],
				'bust'   => $response['bust_url'],
				'render' => $response['render_url'],
			);
		}

		$importedImages = array();
		foreach($medias as $key => $mediaUrl) {
			$imageManipulation = new ImageManipulation();
			$imageUrl = $imageManipulation->setSource($mediaUrl)->setDir($key)->getInternalUrl();
			$importedImages[$key] = $imageUrl;
		}

		return $importedImages;

	}
}