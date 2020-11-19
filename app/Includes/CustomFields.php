<?php
namespace WowpiGuild\Includes;
class CustomFields {

	private $plugin_name;
	private $version;

	public function __construct() {
		if ( defined( 'WOWPI_GUILD_NAME' ) ) {
			$this->plugin_name = WOWPI_GUILD_NAME.'-plugin';
		}
		if ( defined( 'WOWPI_GUILD_VERSION' ) ) {
			$this->version = WOWPI_GUILD_VERSION;
		}
	}

	public function activate() {
		if(function_exists('acf_add_local_field_group')) {
			$this->bnetIdField();
			$this->maleFemaleFields();
			$this->genderFields();
			$this->classSpecFields();
			$this->achievementFields();
			$this->memberFields();
		}
	}

	private function bnetIdField() {
			acf_add_local_field_group(array(
				'key' => 'group_bnet_id_field',
				'title' => 'Battle.net ID',
				'fields' => array(
					array(
						'key' => 'field_wowpi_guild_bnet_id',
						'label' => 'Battle.net\'s internal ID',
						'name' => 'bnet_id',
						'type' => 'number',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'min' => '',
						'max' => '',
						'step' => '',
						'readonly' => 1,
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'taxonomy',
							'operator' => '==',
							'value' => 'wowpi_guild_race',
						),
					),
					array(
						array(
							'param' => 'taxonomy',
							'operator' => '==',
							'value' => 'wowpi_guild_class_spec',
						),
					),
					array(
						array(
							'param' => 'taxonomy',
							'operator' => '==',
							'value' => 'wowpi_guild_achievement',
						),
					),
					array(
						array(
							'param' => 'taxonomy',
							'operator' => '==',
							'value' => 'wowpi_guild_realm',
						),
					),
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'wowpi_guild_member',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'side',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => true,
				'description' => '',
			));
	}

	private function maleFemaleFields() {

		acf_add_local_field_group(array(
			'key' => 'group_wowpi_male_female_custom_fields',
			'title' => 'Descriptions',
			'fields' => array(
				array(
					'key' => 'field_wowpi_guild_male',
					'label' => 'Male',
					'name' => 'wowpi_guild_male',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
				),
				array(
					'key' => 'field_wowpi_guild_female',
					'label' => 'Female',
					'name' => 'wowpi_guild_female',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'taxonomy',
						'operator' => '==',
						'value' => 'wowpi_guild_race',
					),
				),
				array(
					array(
						'param' => 'taxonomy',
						'operator' => '==',
						'value' => 'wowpi_guild_class_spec',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));
	}

	private function genderFields() {
		acf_add_local_field_group(array(
			'key' => 'group_wowpi_guild_gender_custom_fields',
			'title' => '',
			'fields' => array(
				array(
					'key' => 'field_wowpi_guild_gender_type',
					'label' => 'Gender type',
					'name' => 'wowpi_guild_gender_type',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'taxonomy',
						'operator' => '==',
						'value' => 'wowpi_guild_gender',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));
	}

	private function classSpecFields() {

			acf_add_local_field_group(array(
				'key' => 'group_wowpi_guild_class_spec_custom_fields',
				'title' => '',
				'fields' => array(
					array(
						'key' => 'field_wowpi_guild_power_type',
						'label' => 'Power type',
						'name' => 'wowpi_guild_power_type',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'min' => '',
						'max' => '',
						'step' => '',
					),
					array(
						'key' => 'field_wowpi_guild_spec_role',
						'label' => 'Role',
						'name' => 'wowpi_guild_spec_role',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'min' => '',
						'max' => '',
						'step' => '',
					),
					array(
						'key' => 'field_wowpi_guild_spec_role_type',
						'label' => 'Role type',
						'name' => 'wowpi_guild_spec_role_type',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'min' => '',
						'max' => '',
						'step' => '',
					),
					array(
						'key' => 'field_wowpi_guild_spec_description_male',
						'label' => 'Specialization description male',
						'name' => 'wowpi_guild_spec_description_male',
						'type' => 'wysiwyg',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'min' => '',
						'max' => '',
						'step' => '',
					),
					array(
						'key' => 'field_wowpi_guild_spec_description_female',
						'label' => 'Specialization description female',
						'name' => 'wowpi_guild_spec_description_female',
						'type' => 'wysiwyg',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'min' => '',
						'max' => '',
						'step' => '',
					),
					array(
						'key' => 'field_wowpi_guild_spec_talent_tiers',
						'label' => 'Specialization talent tiers data (JSON)',
						'name' => 'wowpi_guild_spec_talent_tiers',
						'type' => 'textarea',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'min' => '',
						'max' => '',
						'step' => '',
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'taxonomy',
							'operator' => '==',
							'value' => 'wowpi_guild_class_spec',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => true,
				'description' => '',
			));

	}

	private function achievementFields() {
		acf_add_local_field_group(array(
			'key' => 'group_wowpi_guild_achievement_custom_fields',
			'title' => '',
			'fields' => array(
				array(
					'key' => 'field_wowpi_guild_achievement_data',
					'label' => 'Achievement data (JSON)',
					'name' => 'achievement_data',
					'type' => 'textarea',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'taxonomy',
						'operator' => '==',
						'value' => 'wowpi_guild_achievement',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));
	}

	private function memberFields() {

		acf_add_local_field_group(array(
			'key' => 'group_wowpi_guild_character_fields',
			'title' => 'Character info',
			'fields' => array(
				array(
					'key' => 'field_wowpi_guild_character_level',
					'label' => 'Level',
					'name' => 'character_level',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
				),
				array(
					'key' => 'field_wowpi_guild_guild_rank',
					'label' => 'Guild Rank',
					'name' => 'guild_rank',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
				),
				array(
					'key' => 'field_wowpi_guild_character_achievement_points',
					'label' => 'Achievement points',
					'name' => 'achievement_points',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
				),
				array(
					'key' => 'field_wowpi_guild_character_active_title',
					'label' => 'Active title',
					'name' => 'active_title',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
				),
				array(
					'key' => 'field_wowpi_guild_character_avg_item_level',
					'label' => 'Average Item Level',
					'name' => 'avg_item_level',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
				),
				array(
					'key' => 'field_wowpi_guild_character_equipped_item_level',
					'label' => 'Equipped Item Level',
					'name' => 'equipped_item_level',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
				),
				array(
					'key' => 'field_wowpi_guild_character_media',
					'label' => 'Media',
					'name' => 'character_media',
					'type' => 'textarea',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
					'readonly' => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'wowpi_guild_member',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'side',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));

	}
}