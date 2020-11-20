<?php


namespace WowpiGuild\Api;


use WowpiGuild\Includes\ImageManipulation;

class PlayableClass extends Connector {

	public function __construct($credentials = array()) {

		parent::__construct($credentials);

	}

	public function index($updateOption = true) {

		// https://eu.api.blizzard.com/data/wow/playable-race/index?namespace=static-eu&locale=en_GB&access_token=xxxxxxxxxxxxxxxxx
		$endpoint = $this->getDomain().'/data/wow/playable-class/index?namespace='.$this->getNamespace('static').'&locale='.$this->locale.'&access_token='.$this->getToken();

		$response = $this->retrieveData($endpoint);

		$classes = array();

		if( array_key_exists( 'classes', $response ) ) {
			foreach( $response['classes'] as $class) {
				$classes[$class['id']] = array(
					'id' => $class['id'],
					'name' => $class['name'],
				);
			}
		}

		update_option('wowpi_guild_classes', $classes);
		return $classes;
	}

	public function getClass($playableClassId) {

		$playableClassId = sanitize_text_field($playableClassId);

		$endpoint = $this->getDomain().'/data/wow/playable-class/'.$playableClassId.'?namespace='.$this->getNamespace('static').'&locale='.$this->locale.'&access_token='.$this->getToken();

		$response = $this->retrieveData($endpoint);

		$class = array();
		$class['id'] = $response['id'];
		$class['name'] = $response['name'];
		$class['male'] = $response['gender_name']['male'];
		$class['female'] = $response['gender_name']['female'];
		$class['power_type'] = $response['power_type']['name'];

		if(array_key_exists('media', $response)) {
			$mediaId = $response['media']['id'];
			if($mediaId > 0) {
				$mediaConnector = new Connector();
				$medias = $mediaConnector->searchMedia('playable-class', $mediaId);

				$importedImages = array();
				foreach($medias as $media) {
					$imageManipulation = new ImageManipulation();
					$imageUrl = $imageManipulation->setSource($media['value'])->setDir($media['key'])->setFileName('classicon_'.$response['id'])->getInternalUrl();
					$importedImages[$media['key']] = $imageUrl;
				}
				$class['images'] = $importedImages;
			}
		}

		$class['specializations'] = array();
		if(array_key_exists('specializations', $response)) {
			foreach($response['specializations'] as $spec) {
				$class['specializations'][$spec['id']] = array(
					'id' => $spec['id'],
					'name' => $spec['name'],
				);
			}
		}
		update_option('wowpi_guild_class_'.$playableClassId, $class);
		return $class;
	}

}