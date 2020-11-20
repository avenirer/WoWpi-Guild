<?php


namespace WowpiGuild\Api;


use WowpiGuild\Config\Settings;
use WowpiGuild\Includes\ImageManipulation;

class PlayableSpecialization extends Connector {

	public function __construct($credentials = array()) {

		parent::__construct($credentials);

	}

	public function index() {

		// https://eu.api.blizzard.com/data/wow/playable-race/index?namespace=static-eu&locale=en_GB&access_token=xxxxxxxxxxxxxxxxx
		$endpoint = $this->getDomain().'/data/wow/playable-specialization/index?namespace='.$this->getNamespace('static').'&locale='.$this->locale.'&access_token='.$this->getToken();

		$response = $this->retrieveData($endpoint);

		$specializations = array();

		if( array_key_exists( 'character_specializations', $response ) ) {
			foreach( $response['character_specializations'] as $spec) {
				$specializations[$spec['id']] = array(
					'id' => $spec['id'],
					'name' => $spec['name'],
				);
			}
		}
		update_option('wowpi_guild_specializations', $specializations);
		return $specializations;
	}

	public function getClassSpecializations($playableClassId) {

		$playableClassId = sanitize_text_field($playableClassId);

		$class = Settings::getClass($playableClassId);

		$classSpecs = array();
		if(array_key_exists('specializations', $class)) {
			foreach($class['specializations'] as $spec) {
				$endpoint = $this->getDomain().'/data/wow/playable-specialization/'.$spec['id'].'?namespace='.$this->getNamespace('static').'&locale='.$this->locale.'&access_token='.$this->getToken();
				$response = $this->retrieveData($endpoint);
				$classSpec = array(
					'id' => $response['id'],
					'name' => $response['name'],
					'role' => $response['role']['name'],
					'role_type' => $response['role']['type'],
					'male_description' => nl2br($response['gender_description']['male']),
					'female_description' => nl2br($response['gender_description']['female']),
				);
				$talentTiers = array();
				foreach($response['talent_tiers'] as $tier) {
					$talents = array();
					foreach ( $tier['talents'] as $talent ) {
						$talents[ $talent['column_index'] ] = array(
							'id'            => $talent['talent']['id'],
							'name'          => $talent['talent']['name'],
							'spell_tooltip' => $talent['spell_tooltip'],
						);
					}
					$talentTiers[ $tier['tier_index'] ] = array(
						'level'   => $tier['level'],
						'talents' => $talents,
					);
				}
				$classSpec['talent_tiers'] = $talentTiers;

				if(array_key_exists('media', $response)) {
					$specMediaId = $response['media']['id'];
					if($specMediaId > 0) {
						$specMediaConnector = new Connector();
						$specMedias = $specMediaConnector->searchMedia('playable-specialization', $specMediaId);

						$specImportedImages = array();
						foreach($specMedias as $media) {
							$specImageManipulation = new ImageManipulation();
							$specImageUrl = $specImageManipulation->setSource($media['value'])->setDir($media['key'])->setFileName('ability_'.$playableClassId.'_'.$spec['id'])->getInternalUrl();
							$specImportedImages[$media['key']] = $specImageUrl;
						}
						$classSpec['images'] = $specImportedImages;
					}
				}
				$classSpecs[] = $classSpec;
			}
		}
		update_option('wowpi_guild_class_'.$playableClassId.'_specs', $classSpecs);
		return $classSpecs;
	}

}