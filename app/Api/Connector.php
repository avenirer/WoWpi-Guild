<?php
namespace WowpiGuild\Api;

use WowpiGuild\Includes\ImageManipulation;

class Connector {

	protected $client_id;
	protected $client_secret;
	protected $game;
	protected $region;
	protected $locale;
	protected $realmSlug;
	protected $guild;

	private $apis = array(
		'media' => array(
			'search' => array(
				'type' => 'static',
			),
		),
		'realm' => array(
			'index' => array(
				'type' => 'dynamic',
			),
			'realm' => array(
				'type' => 'dynamic',
				'args' => array('realmSlug', 'realmId'),
			)
		),
		'guild' => array(
			'index' => array(
				'type' => 'profile',
			),
			'activity' => array(
				'type' => 'profile',
			),
			'achievements' => array(
				'type' => 'profile',
			),
			'roster' => array(
				'type' => 'profile',
			)
		),
		'playable-race' => array(
			'index' => array(
				'type' => 'static',
			),
			'search' => array(
				'type' => 'static',
			),
		),
		'playable-class' => array(
			'index' => array(
				'type' => 'static',
			),
			'search' => array(
				'type' => 'static',
			),
		),
		'playable-specialization' => array(
			'index' => array(
				'type' => 'static',
			),
			'search' => array(
				'type' => 'static',
			),
			'media' => array(
				'type' => 'static',
			),
		),
		'achievement-category' => array(
			'index' => array(
				'type' => 'static',
			),
			'search' => array(
				'type' => 'static',
			),
		),
		'achievement' => array(
			'index' => array(
				'type' => 'static',
			),
			'search' => array(
				'type' => 'static',
			),
		),
	);

	public function __construct($credentials = array()) {
		$credentials = empty($credentials) ? get_option( 'wowpi_guild_credentials' ) : $credentials;
		if($credentials) {
			$this->client_id = $credentials['client_id'];
			$this->client_secret = $credentials['client_secret'];
			$this->game = $credentials['game'];
			$this->region = $credentials['region'];
			$this->locale = $credentials['locale'];

			$guild = get_option('wowpi_guild_guild');
			if($guild) {
				$this->guild = $guild;
				$this->realmSlug = $guild['realm_slug'];
			}

			if(! $this->client_id || ! $this->client_secret || ! $this->game || ! $this->region || ! $this->locale) {
				error_log('You must have all the needed credentials in order to retrieve data from Battle.net: client ID, client secret, game, region, locale');
			}
		}
	}

	public function getToken() {

		$authorization  =  base64_encode ( $this->client_id.":".$this->client_secret );
		$tokenUrl = $this->buildTokenUrl();

		$args = array(
			'headers' => array(
				'Authorization' => 'Basic ' . $authorization,
				'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
			),
			'body' => 'grant_type=client_credentials'
		);
		$response = wp_remote_post( $tokenUrl, $args );

		try {
			if ( is_wp_error( $response ) ) {
				$error_message = 'Something went wrong while trying to get token: ' . $response->get_error_message();
				error_log($error_message);
				throw new \Exception($error_message);
			}

			$response_body = wp_remote_retrieve_body( $response );
			$response_body = json_decode( $response_body, true );

			if(!array_key_exists('access_token', $response_body)) {
				throw new \Exception('No access code was retrieved: ' . $response_body['error_description']);
			}

			return $response_body['access_token'];

		}
		catch(\Exception $exception) {
			echo $exception->getMessage();
			error_log($exception->getMessage());
			return false;
		}
	}

	function buildTokenUrl() {
		$tokenUrl = 'https://'.$this->region.'.battle.net/oauth/token';
		return $tokenUrl;
	}

	public function getAchievementCategories() {

		// https://eu.api.blizzard.com/data/wow/playable-class/index?namespace=static-eu&locale=en_GB&access_token=xxxxxxxxxxxxxxxxx
		$endpoint = $this->buildEndpoint('achievement-category', 'index');

		$response = $this->retrieveData($endpoint);

		$achievementCategories = array();

		if( array_key_exists( 'categories', $response ) ) {
			foreach( $response['categories'] as $category) {
				$achievementCategories[$category['id']] = array(
					'id' => $category['id'],
					'name' => $category['name'],
				);
			}
		}

		return $achievementCategories;
	}

	public function getAchievements($categoryId = null) {

		if(is_null($categoryId)) {
			$endpoint = $this->buildEndpoint('achievement', 'index');
		}
		else {
			$endpoint = $this->buildEndpoint('achievement-category', 'search', array('id' => $categoryId));
		}

		$response = $this->retrieveData($endpoint);

		$achievements = array();

		if( array_key_exists( 'achievements', $response ) ) {
			foreach( $response['achievements'] as $achievement) {
				$achievements[$achievement['id']] = array(
					'id' => $achievement['id'],
					'name' => $achievement['name'],
				);
			}
		}

		if(!is_null($categoryId)) {
			return array(
				'name' => $response['name'],
				'id' => $response['id'],
				'achievements' => $achievements,
			);
		}

		return $achievements;
	}

	public function getAchievement($achievementId) {
		$endpoint = $this->buildEndpoint('achievement', 'search', array('id' => $achievementId) );

		$response = $this->retrieveData($endpoint);

		$achievement = array(
			'id' => $response['id'],
			'category' => array(
				'id' => $response['category']['id'],
				'name' => $response['category']['name'],
			),
			'name' => $response['name'],
			'description' => $response['description'],
			'points' => $response['points'],
			'is_account_wide' => $response['is_account_wide'],
			'criteria' => array_key_exists('criteria', $response) ? $response['criteria'] : array(),
		);

		if(array_key_exists('media', $response)) {
			$mediaId = $response['media']['id'];
			if($mediaId > 0) {
				$mediaConnector = new Connector();
				$medias = $mediaConnector->searchMedia('achievement', $mediaId);

				$importedImages = array();
				foreach($medias as $media) {
					$imageManipulation = new ImageManipulation();
					$imageUrl = $imageManipulation->setSource($media['value'])->setDir($media['key'])->getInternalUrl();
					$importedImages[$media['key']] = $imageUrl;
				}
				$achievement['images'] = $importedImages;
			}
		}

		return $achievement;
	}

	public function searchMedia($tags, $id = 0) {

		$endpoint = $this->buildEndpoint('media', 'search', array('tags' => $tags, 'id' => $id));
		$response = $this->retrieveData($endpoint);

		if(array_key_exists('results', $response) && !empty($response['results'])) {
			$medias = $response['results'][0]['data']['assets'];
			if(!empty($medias)) {
				return $medias;
			}
			return array();
		}
	}

	private function buildEndpoint($api, $query, $args = array()) {

		if(! array_key_exists($api, $this->apis) || ! array_key_exists($query, $this->apis[$api])) {
			error_log('You didn\'t define an api for api: ' . $api . ', query: ' .$query );
			exit;
		}

		$domain = $this->getDomain();
		$namespace = $this->getNamespace($this->apis[$api][$query]['type']);

		$endpoint = $domain . '/data/wow/'.$api;

		if( $api == 'realm' ) {
			$realmSlug = array_key_exists('realmSlug', $args) ? $args['realmSlug']
				: (array_key_exists('realm_slug', $this->guild) ? $this->guild['realm_slug'] : false);
			if($query == 'realm' && $realmSlug) {
				$query = $realmSlug;
			}
		}

		if( $api == 'guild' ) {

			// get realm slug
			$realmSlug = array_key_exists('realmSlug', $args) ? $args['realmSlug']
				: (array_key_exists('realm_slug', $this->guild) ? $this->guild['realm_slug'] : false);

			if(! $realmSlug) {
				error_log('You do not have a realm slug defined in option, nor as parameter');
				exit;
			}

			// get guild slug
			$guildSlug = array_key_exists('guildSlug', $args) ? $args['guildSlug']
				: (array_key_exists('slug', $this->guild) ? $this->guild['slug'] : false);

			if(! $guildSlug) {
				error_log('You do not have a guild slug defined in option, nor as parameter');
				exit;
			}

			$endpoint .= '/'.$realmSlug.'/'.$guildSlug;
			if($query == 'index') $query = '';
		}

		if( ($api == 'playable-race' || $api == 'playable-class' || $api == 'playable-specialization' || $api == 'achievement-category' || $api == 'achievement') && $query == 'search' ) {
			$query = $args['id'];
		}

		if($api == 'media' && $query == 'search') {
			$endpoint = str_replace('media', 'search/media', $endpoint);
			$query = '';
		}



		$endpoint .= '/'.$query;
		//address here the args for api
		$endpoint = rtrim($endpoint, " /") . '?';

		if($api == 'media' && $query == '' && !empty($args)) {
			$params = http_build_query($args);
			$endpoint .= $params.'&';
		}

		$endpoint .= 'namespace='.$namespace.'&locale='.$this->locale.'&access_token='.$this->getToken();

		return $endpoint;

	}

	protected function getDomain() {
		$domain = 'https://';
		if($this->region == 'cn') {
			$domain .= 'gateway.battlenet.com.cn';
		}
		else {
			$domain .= $this->region.'.api.blizzard.com';
		}
		return $domain;
	}


	protected function getNamespace($type) {
		$types = array('dynamic', 'static', 'profile');
		if(!in_array($type, $types)) {
			error_log('There can only be dynamic static, or profile namespaces');
			exit;
		}
		if($this->game == 'classic' && $type == 'profile') {
			error_log('The profile namespace is incompatible with the World of Warcraft Classic');
			exit;
		}
		return $type.'-'.($this->game == 'classic' ? 'classic-' : '').$this->region;
	}

	protected function retrieveData($endpoint)
	{
		$args = array(
			'timeout'     => 10,
			'sslverify' => false,
		);

		try {
			$response = wp_remote_get($endpoint, $args);
			if ( is_wp_error( $response ) ) {
				$error_message = 'Something went wrong while trying to get data: ' . $response->get_error_message();
				throw new \Exception($error_message);
			}

			if (!is_array($response)) {
				$error_message = 'Error occured during query. Maybe your website doesn\'t allow outgoing connections? <!--'.$endpoint.'--> Response code: '. wp_remote_retrieve_response_code( $response );
				throw new \Exception(($error_message));
			}
			$response_body = wp_remote_retrieve_body( $response );
			$response_body = json_decode( $response_body, true );

			if(array_key_exists('code', $response_body) && $response_body['code'] == '404') {
				error_log('404 returned from retrieveData method for endpoint '.$endpoint);
				return false;
			}

			if(array_key_exists('code', $response) && $response['code'] == '403') {
				error_log('403 returned from retrieveData method for endpoint '.$endpoint);
				return false;
			}

			return $response_body;
		}
		catch(\Exception $exception) {
			echo $exception->getMessage();
			error_log($exception->getMessage());
			return false;
		}
	}
}