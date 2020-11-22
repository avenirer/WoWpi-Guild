<?php
namespace WowpiGuild\Includes;


use WowpiGuild\Api\Character;
use WowpiGuild\Config\Settings;

class WowpiCron {

	public function init() {
		add_action('init', function() {
			add_action('wowpi_cron', array($this, 'updateMembers'));
			register_deactivation_hook(__FILE__, 'wowpiDeactivate');

			if(! wp_next_scheduled('wowpi_cron')) {
				wp_schedule_event(time(), 'hourly', 'wowpi_cron');
			}
		});
	}

	public function wowpiDeactivate() {
		wp_clear_scheduled_hook('wowpi_cron');
	}

	public function updateMembers() {

		if(!get_option('wowpi_guild_guild')) {
			return true;
		}

		$args = array(
			'post_type' => 'wowpi_guild_member',
			'numberposts' => 10,
			'orderby' => 'modified',
			'order' => 'ASC',
		);

		$posts = get_posts($args);

		$changedCharacters = array();
		foreach($posts as $characterPost) {
			$characterName = $characterPost->post_title;
			$status = $this->updateCharacter($characterPost);
			$changedCharacters[$status][] = $characterName;
		}

		$messages = array();
		foreach($changedCharacters as $status => $characters) {
			$statusMessage = strtoupper($status).': '.implode(',', $characters);
			$messages[] = $statusMessage;
		}

		$message = implode('. ', $messages);

		return $message;

	}

	/**
	 * @param \WP_Post $characterPost
	 *
	 * @return string 'deleted'|'updated'|'not updated'
	 */
	public function updateCharacter(\WP_Post $characterPost) {

		$guildRoster = Settings::getRoster(true);
		$roster = $guildRoster['roster'];

		$characterId = get_field( 'bnet_id', $characterPost->ID );
		if ( $characterId && ! array_key_exists( $characterId, $roster ) ) {
			wp_delete_post( $characterPost->ID );
			return 'deleted';
		}

		$remoteCharacter = $roster[ $characterId ];;
		$characterName    = strtolower( $remoteCharacter['name'] );
		$realmSlug        = $remoteCharacter['realm']['slug'];
		$connector        = new Character();
		$connector->setRealmSlug($realmSlug);
		$characterSummary = $connector->summary( $characterName );

		if ( ! $characterSummary ) {
			// SET STATUS INACTIVE

			global $wpdb;
			$time              = time();
			$mysql_time_format = "Y-m-d H:i:s";
			$post_modified     = gmdate( $mysql_time_format, $time );
			$post_modified_gmt = gmdate( $mysql_time_format, ( $time + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) );
			$wpdb->query( "UPDATE $wpdb->posts SET post_modified = '{$post_modified}', post_modified_gmt = '{$post_modified_gmt}', post_status = 'draft'  WHERE ID = {$characterPost->ID}" );

			return 'not updated - maybe not playing';
		}

		// MAKE SURE STATUS IS ACTIVE
		$this->addTaxonomyTerm($characterPost->ID, 'wowpi_guild_character_status', 'Active', 'active');

		$characterPost->post_title = $characterSummary['name'];
		$characters[] = $characterSummary['name'];

		// CHARACTER GENDER
		$this->addGenderTerm($characterPost->ID, $characterSummary['gender']);

		// CHARACTER REALM
		$this->addTaxonomyTerm($characterPost->ID, 'wowpi_guild_realm', $characterSummary['realm']['name'], $characterSummary['realm']['slug']);

		// CHARACTER RACE
		$this->addTaxonomyTerm($characterPost->ID, 'wowpi_guild_race', $characterSummary['race']['name'], $characterSummary['race']['name'].'-'.$characterSummary['race']['id']);

		// CHARACTER CLASS
		$this->addTaxonomyTerm($characterPost->ID, 'wowpi_guild_class_spec', $characterSummary['character_class']['name'], $characterSummary['character_class']['name'].'-'.$characterSummary['character_class']['id']);


		// CHARACTER SPECIALIZATION
		$this->addTaxonomyTerm($characterPost->ID, 'wowpi_guild_class_spec', $characterSummary['active_spec']['name'], $characterSummary['active_spec']['name'].'-'.$characterSummary['active_spec']['id'], true);


		// CHARACTER LEVEL
		update_field('character_level', $characterSummary['level'], $characterPost->ID);

		// CHARACTER ACHIEVEMENT POINTS
		update_field('achievement_points', $characterSummary['achievement_points'], $characterPost->ID);

		// CHARACTER ACTIVE TITLE
		if(array_key_exists('active_title', $characterSummary) && array_key_exists('display_string', $characterSummary['active_title'])) {
			update_field( 'active_title', $characterSummary['active_title']['display_string'], $characterPost->ID );
		}

		// AVERAGE ITEM LEVEL
		update_field('avg_item_level', $characterSummary['average_item_level'], $characterPost->ID);

		// EQUIPPED ITEM LEVEL
		update_field('equipped_item_level', $characterSummary['equipped_item_level'], $characterPost->ID);

		// CHARACTER IMAGES
		$characterMedias = $connector->media($characterName);
		update_field('character_media', json_encode($characterMedias), $characterPost->ID);

		return 'updated';
	}

	/**
	 * @param $postID
	 * @param $taxonomy
	 * @param $name
	 * @param $slug
	 * @param false $append
	 *
	 * @return array|false|\WP_Error
	 */
	private function addTaxonomyTerm($postID, $taxonomy, $name, $slug, $append = false) {

		$slug = sanitize_title($slug);
		$term = get_term_by('slug', $slug, $taxonomy, ARRAY_A);
		if(!$term) {
			$term = wp_insert_term($name, $taxonomy, array('slug' => $slug));
		}
		return wp_set_post_terms($postID, array($term['term_id']), $taxonomy, $append);
	}

	private function addGenderTerm($postID, $gender) {

		$slug = sanitize_title($gender['name']);
		$term = get_term_by('slug', $slug, 'wowpi_guild_gender', ARRAY_A);
		if(!$term) {
			$term = wp_insert_term($gender['name'], 'wowpi_guild_gender', array('slug' => $slug));
		}
		if(!get_field('wowpi_guild_gender_type', 'wowpi_guild_gender_'. $term['term_id'])) {
			update_field( 'wowpi_guild_gender_type', $gender['type'], 'wowpi_guild_gender_' . $term['term_id'] );
		}
		return wp_set_post_terms($postID, array($term['term_id']), 'wowpi_guild_gender'. false);
	}

}