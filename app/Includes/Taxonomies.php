<?php
namespace WowpiGuild\Includes;
class Taxonomies {

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

		add_action('init', array($this, 'createRealmTaxonomy'));
		add_action('init', array($this, 'createRaceTaxonomy'));
		add_action('init', array($this, 'createClassSpecTaxonomy'));
		add_action('init', array($this, 'createAchievementTaxonomy'));
		add_action('init', array($this, 'createGenderTaxonomy'));
		add_action('init', array($this, 'createStatusTaxonomy'));
	}

	public function createRaceTaxonomy() {
		$labels = array(
			'name' => _x( 'Races', $this->plugin_name ),
			'singular_name' => _x( 'Race', $this->plugin_name ),
			'search_items' =>  __( 'Search Races', $this->plugin_name ),
			'all_items' => __( 'All Races', $this->plugin_name ),
			//'parent_item' => __( 'Parent Race', $this->plugin_name ),
			//'parent_item_colon' => __( 'Parent Subject:', $this->plugin_name ),
			'edit_item' => __( 'Edit Race', $this->plugin_name ),
			'update_item' => __( 'Update Race', $this->plugin_name ),
			'add_new_item' => __( 'Add New Race', $this->plugin_name ),
			'new_item_name' => __( 'New Race title', $this->plugin_name ),
			'menu_name' => __( 'Races', $this->plugin_name ),
		);

		// Now register the taxonomy
		register_taxonomy(
			'wowpi_guild_race',
			array('wowpi_guild_member'),
			array(
				'hierarchical' => false,
				'labels' => $labels,
				'show_ui' => true,
				'show_in_rest' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'race' ),
			)
		);
	}

	public function createClassSpecTaxonomy() {
		$labels = array(
			'name' => _x( 'Classes and Specs', $this->plugin_name ),
			'singular_name' => _x( 'Class/Spec', $this->plugin_name ),
			'search_items' =>  __( 'Search Classes/Specs', $this->plugin_name ),
			'all_items' => __( 'All Classes/Specs', $this->plugin_name ),
			'edit_item' => __( 'Edit Class/Spec', $this->plugin_name ),
			'update_item' => __( 'Update Class/Spec', $this->plugin_name ),
			'add_new_item' => __( 'Add New Class/Spec', $this->plugin_name ),
			'new_item_name' => __( 'New Class/Spec', $this->plugin_name ),
			'menu_name' => __( 'Classes/Specs', $this->plugin_name ),
		);

		// Now register the taxonomy
		register_taxonomy(
			'wowpi_guild_class_spec',
			array('wowpi_guild_member'),
			array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'show_in_rest' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'class-spec' ),
			)
		);
	}

	public function createAchievementTaxonomy() {
		$labels = array(
			'name' => _x( 'Achievements', $this->plugin_name ),
			'singular_name' => _x( 'Achievement', $this->plugin_name ),
			'search_items' =>  __( 'Search Achievements', $this->plugin_name ),
			'all_items' => __( 'All Achievement', $this->plugin_name ),
			//'parent_item' => __( 'Parent Race', $this->plugin_name ),
			//'parent_item_colon' => __( 'Parent Subject:', $this->plugin_name ),
			'edit_item' => __( 'Edit Achievement', $this->plugin_name ),
			'update_item' => __( 'Update Achievement', $this->plugin_name ),
			'add_new_item' => __( 'Add New Achievement', $this->plugin_name ),
			'new_item_name' => __( 'New Achievement title', $this->plugin_name ),
			'menu_name' => __( 'Achievements', $this->plugin_name ),
		);

		// Now register the taxonomy
		register_taxonomy(
			'wowpi_guild_achievement',
			array('wowpi_guild_member'),
			array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'show_in_rest' => true,
				'show_admin_column' => false,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'achievement' ),
			)
		);
	}

	public function createGenderTaxonomy() {
		$labels = array(
			'name' => _x( 'Gender', $this->plugin_name ),
			'singular_name' => _x( 'Gender', $this->plugin_name ),
			'search_items' =>  __( 'Search Genders', $this->plugin_name ),
			'all_items' => __( 'All Genders', $this->plugin_name ),
			//'parent_item' => __( 'Parent Race', $this->plugin_name ),
			//'parent_item_colon' => __( 'Parent Subject:', $this->plugin_name ),
			'edit_item' => __( 'Edit Gender', $this->plugin_name ),
			'update_item' => __( 'Update Gender', $this->plugin_name ),
			'add_new_item' => __( 'Add New Gender', $this->plugin_name ),
			'new_item_name' => __( 'New Gender name', $this->plugin_name ),
			'menu_name' => __( 'Genders', $this->plugin_name ),
		);

		// Now register the taxonomy
		register_taxonomy(
			'wowpi_guild_gender',
			array('wowpi_guild_member'),
			array(
				'hierarchical' => false,
				'labels' => $labels,
				'show_ui' => true,
				'show_in_rest' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'gender' ),
			)
		);
	}

	public function createStatusTaxonomy() {
		$labels = array(
			'name' => _x( 'Character status', $this->plugin_name ),
			'singular_name' => _x( 'Status', $this->plugin_name ),
			'search_items' =>  __( 'Search Statuses', $this->plugin_name ),
			'all_items' => __( 'All Statuses', $this->plugin_name ),
			//'parent_item' => __( 'Parent Race', $this->plugin_name ),
			//'parent_item_colon' => __( 'Parent Subject:', $this->plugin_name ),
			'edit_item' => __( 'Edit Status', $this->plugin_name ),
			'update_item' => __( 'Update Status', $this->plugin_name ),
			'add_new_item' => __( 'Add New Status', $this->plugin_name ),
			'new_item_name' => __( 'New Status title', $this->plugin_name ),
			'menu_name' => __( 'Statuses', $this->plugin_name ),
		);

		// Now register the taxonomy
		register_taxonomy(
			'wowpi_guild_character_status',
			array('wowpi_guild_member'),
			array(
				'hierarchical' => false,
				'labels' => $labels,
				'show_ui' => true,
				'show_in_rest' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'status' ),
			)
		);
	}

	public function createRealmTaxonomy() {
		$labels = array(
			'name' => _x( 'Realm', $this->plugin_name ),
			'singular_name' => _x( 'Realm', $this->plugin_name ),
			'search_items' =>  __( 'Search Realms', $this->plugin_name ),
			'all_items' => __( 'All Realms', $this->plugin_name ),
			//'parent_item' => __( 'Parent Race', $this->plugin_name ),
			//'parent_item_colon' => __( 'Parent Subject:', $this->plugin_name ),
			'edit_item' => __( 'Edit Realm', $this->plugin_name ),
			'update_item' => __( 'Update Realm', $this->plugin_name ),
			'add_new_item' => __( 'Add New Realm', $this->plugin_name ),
			'new_item_name' => __( 'New Realm', $this->plugin_name ),
			'menu_name' => __( 'Realms', $this->plugin_name ),
		);

		// Now register the taxonomy
		register_taxonomy(
			'wowpi_guild_realm',
			array('wowpi_guild_member'),
			array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'show_in_rest' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'realm' ),
			)
		);
	}

}