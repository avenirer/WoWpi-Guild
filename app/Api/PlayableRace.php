<?php


namespace WowpiGuild\Api;


class PlayableRace extends Connector {

	public function __construct($credentials = array()) {

		parent::__construct($credentials);

	}

	public function index() {

		// https://eu.api.blizzard.com/data/wow/playable-race/index?namespace=static-eu&locale=en_GB&access_token=xxxxxxxxxxxxxxxxx
		$endpoint = $this->getDomain().'/data/wow/playable-race/index?namespace='.$this->getNamespace('static').'&locale='.$this->locale.'&access_token='.$this->getToken();

		$response = $this->retrieveData($endpoint);

		$races = array();

		if( array_key_exists( 'races', $response ) ) {
			foreach( $response['races'] as $race) {
				$races[$race['id']] = array(
					'id' => $race['id'],
					'name' => $race['name'],
				);
			}
		}

		update_option('wowpi_guild_races', $races);
		return $races;
	}

	public function getRace($playableRaceId) {

		$playableRaceId = sanitize_text_field($playableRaceId);

		$endpoint = $this->getDomain().'/data/wow/playable-race/'.$playableRaceId.'?namespace='.$this->getNamespace('static').'&locale='.$this->locale.'&access_token='.$this->getToken();

		$response = $this->retrieveData($endpoint);

		return array(
			'id' => $response['id'],
			'name' => $response['name'],
			'male' => $response['gender_name']['male'],
			'female' => $response['gender_name']['female'],
			'faction' => $response['faction']['name'],
		);
	}

}