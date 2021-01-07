<?php


namespace WowpiGuild\Includes;


class DataTables {

	public function getRoster() {

		$fieldNames = filter_var_array($_REQUEST['columns']);
		$fields = array();
		$searchBy = array();
		foreach($fieldNames as $key => $field) {
			$fields[$key] = $field['data'];
			if(strlen($field['search']['value']) > 0) {
				$searchBy[$field['data']] = $field['search']['value'];
			}
		}

		$offset = intval(sanitize_text_field($_REQUEST['start']));
		$limit = intval(sanitize_text_field($_REQUEST['length']));

		$args = array(
			'post_type' => 'wowpi_guild_member',
			'post_status' => 'publish',
			'posts_per_page' => $limit,
			'offset' => $offset,

		);

		if($_REQUEST['search']['value']) {
			$args['s'] = sanitize_text_field($_REQUEST['search']['value']);
		}



		if(array_key_exists('rank', $searchBy)) {
			$ranks = array();
			$rankValues = array();
			$guildRanks = filter_var(rawurldecode($searchBy['rank']), FILTER_SANITIZE_STRING);
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
						'compare' => 'IN',
						'value'	  	=> $rankValues,
					)
				);
			}
		}

		$order = $_REQUEST['order'];

		$orderBy = array();
		if(!empty($order)) {
			foreach($order as $fieldDirection) {
				$orderBy[$fields[sanitize_text_field($fieldDirection['column'])]] = sanitize_text_field($fieldDirection['dir']);
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
					case 'rank':
						$args['meta_key'] = 'guild_rank';
						$args['orderby'] = 'meta_value';
						$args['order'] = $direction;
						break;
					case 'level':
						$args['meta_key'] = 'character_level';
						$args['orderby'] = 'meta_value';
						$args['order'] = $direction;
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
				$race = 'none';
				if($characterRaces) {
					$raceTerm = $characterRaces[0];
					$race = $raceTerm->name;
					$race_id = get_field('bnet_id', 'wowpi_guild_race_'. $raceTerm->term_id);
				}

				$characterGenders = get_the_terms(get_the_ID(), 'wowpi_guild_gender');
				$gender = 'male';
				if($characterGenders) {
					$genderTerm = $characterGenders[0];
					$gender = $genderTerm->name;
					$gender_type = strtolower(get_field('wowpi_guild_gender_type', 'wowpi_guild_gender_'.$genderTerm->term_id));
				}

				$characterClasses = get_the_terms(get_the_ID(), 'wowpi_guild_class_spec');
				$class = '';
				$role = 'none';
				if($characterClasses) {
					foreach($characterClasses as $classTerm) {
						if($classTerm->parent == 0) {
							$class = $classTerm->name;
							$class_id = get_field('bnet_id', 'wowpi_guild_class_spec_'.$classTerm->term_id);
						}
						else {
							$role = get_field('wowpi_guild_spec_role', 'wowpi_guild_class_spec_'.$classTerm->term_id);
							$role_type = get_field('wowpi_guild_spec_role_type', 'wowpi_guild_class_spec_'.$classTerm->term_id);
							$spec_id = get_field('bnet_id', 'wowpi_guild_class_spec_'.$classTerm->term_id);
						}
					}
				}

				$character         = array();
				$character['name'] = get_the_title();
				$character['race'] = array('name' => $race, 'icon' =>'');
				if(isset($race_id) && isset($gender_type)) {
					$character['race']['icon'] = 'race_'.$race_id . '_' . $gender_type.'.jpg';
					unset($race_id);
					unset($gender_type);
				}

				$character['class'] = array('name' => $class, 'icon' => '');
				if(isset($class_id)) {
					$icon = 'classicon_' . $class_id . '.jpg';
					/*
					if(isset($spec_id)) {
						$icon = 'ability_'.$class_id.'_'.$spec_id.'.jpg';
						unset($spec_id);
					}*/
					$character['class']['icon'] = $icon;
					unset($class_id);
				}
				$character['role'] = array('name' => $role, 'id' => '');
				if(isset($role_type)) {
					$character['role']['type'] = strtolower($role_type);
					unset($role_type);
				}
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
	}


}