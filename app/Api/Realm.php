<?php
namespace WowpiGuild\Api;

class Realm extends Connector {

	protected $realmSlug;

	public function __construct($credentials = array()) {

		parent::__construct($credentials);

	}

	public function setRealmSlug($realmSlug) {
		$this->realmSlug = sanitize_text_field($realmSlug);
	}

	public function index() {

		$endpoint = $this->getDomain().'/data/wow/realm/index?namespace='.$this->getNamespace('dynamic').'&locale='.$this->locale.'&access_token='.$this->getToken();

		$response = $this->retrieveData($endpoint);

		$realms = array();
		foreach($response['realms'] as $realm) {
			$realms[$realm['id']] = array(
				'id' => $realm['id'],
				'name' => $realm['name'],
				'slug' => $realm['slug'],
			);
		}
		update_option('wowpi_guild_realms', $realms);
		return $realms;
	}

	public function activity($realmSlug, $nameSlug) {

	}

	public function achievements($realmSlug, $nameSlug) {

	}

	public function roster($realmSlug, $nameSlug) {

	}
}