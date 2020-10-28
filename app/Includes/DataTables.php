<?php


namespace WowpiGuild\Includes;


class DataTables {

	public function getRoster() {

		$fieldNames = filter_var_array($_REQUEST['columns']);
		$fields = array();
		foreach($fieldNames as $key => $field) {
			$fields[$key] = $field['data'];
		}
		$offset = filter_var($_REQUEST['start'], FILTER_SANITIZE_NUMBER_INT);
		$limit = filter_var($_REQUEST['length'], FILTER_SANITIZE_NUMBER_INT);

		$args = array(
			'post_type' => 'wowpi_guild_member',
			'post_status' => 'publish',
			'posts_per_page' => $limit,
			'offset' => $offset,

		);

		if($_REQUEST['search']['value']) {
			$args['s'] = filter_var($_REQUEST['search']['value'], FILTER_SANITIZE_STRING);
		}

		if(array_key_exists('ranks', $_REQUEST)) {
			$ranks = array();
			$rankValues = array();
			$guildRanks = filter_var( $_REQUEST['ranks'], FILTER_SANITIZE_STRING );
			$ranksArr = explode('|', $guildRanks);
			foreach($ranksArr as $rank) {
				$rankDef = explode(':', $rank);
				$ranks[$rankDef[0]] = sizeof($rankDef)==2 ? $rankDef[1] : $rankDef[0];
				$rankValues[] = $rankDef[0];
			}
			if(!empty($rankValues)) {
				$args['meta_query']	= array(
					'relation'		=> 'AND',
					array(
						'key'	 	=> 'guild_rank',
						'value'	  	=> $rankValues,
					)
				);
			}
		}

		$order = filter_var_array($_REQUEST['order'], FILTER_SANITIZE_STRING);

		$orderBy = array();
		if(!empty($order)) {
			foreach($order as $fieldDirection) {
				$orderBy[$fields[$fieldDirection['column']]] = $fieldDirection['dir'];
			}
		}

		if(!empty($orderBy)) {
			foreach($orderBy as $field => $direction) {
				switch ($field) {
					case 'name':
						if(!array_key_exists('order_by', $args)) {
							$args['orderby'] = array();
						}
						$args['orderby']['title'] = $direction;
						break;

				}

			}
		}

		$rosterQuery = new \WP_Query($args);
		$totalData = $rosterQuery->found_posts;

		$data = array();
		if ( $rosterQuery->have_posts() ) {

			while ( $rosterQuery->have_posts() ) {

				$rosterQuery->the_post();

				$characterRaces = get_the_terms(get_the_ID(), 'wowpi_guild_race');
				$race = '';
				if($characterRaces) {
					$race = $characterRaces[0];
				}

				$characterGenders = get_the_terms(get_the_ID(), 'wowpi_guild_gender');
				$gender = 'male';
				if($characterGenders) {
					$genderTerm = $characterGenders[0];
					$gender = $genderTerm->slug;
				}

				$characterClasses = get_the_terms(get_the_ID(), 'wowpi_guild_class_spec');
				$class = '';
				$role = 'none';
				if($characterClasses) {
					foreach($characterClasses as $classTerm) {
						if($classTerm->parent == 0) {
							$class = $classTerm->name;
						}
						else {
							$role = get_field('wowpi_guild_spec_role', 'wowpi_guild_class_spec_'.$classTerm->term_id);
						}
					}
				}

				$character         = array();
				$character['name'] = get_the_title();
				$character['race'] = array('race' => strtolower(str_replace(array(' ', '\''), '', $race->name)), 'gender' => $gender);
				$character['class'] = str_replace(' ', '_', strtolower($class));
				$character['role'] = strtolower($role);
				$character['level'] = get_field('character_level', get_the_ID());
				$rank = intval(get_field('guild_rank', get_the_ID()));
				$character['rank'] = (isset($ranks) && array_key_exists($rank, $ranks)) ? $ranks[$rank] : $rank;

				$data[] = $character;

			}
		}

			wp_reset_query();

			$json_data = array(
				'draw' => intval($_REQUEST['draw']),
				'recordsTotal' => intval($totalData),
				'recordsFiltered' => intval($totalData),
				"data" => $data,
			);

		echo json_encode($json_data);

		die();



		echo 'bau';
		exit;

		echo 'bau';
		exit;
	}


}