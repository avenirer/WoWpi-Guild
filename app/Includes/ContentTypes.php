<?php
namespace WowpiGuild\Includes;
class ContentTypes {

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
		add_action('init', array($this, 'createMemberContentType'));
	}

	public function createMemberContentType() {

		register_post_type('wowpi_guild_member', array(
			'label' => __('Members', $this->plugin_name),
			'description' => __('This post type contains the guild members'),
			'public' => true,
			'hierarchical' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_admin_bar' => false,
			'show_in_rest' => true,
			'menu_position' => 10,
			'menu_icon' => 'data:image/svg+xml;base64,PHN2ZyBpZD0iQ2FwYV8xIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA1MTEuOTg2IDUxMS45ODYiIGhlaWdodD0iNTEyIiB2aWV3Qm94PSIwIDAgNTExLjk4NiA1MTEuOTg2IiB3aWR0aD0iNTEyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxnPjxwYXRoIGQ9Im00ODQuODE4IDQ3Ljg1N2MzLjY0Ny01LjkxNyAyLjc1Mi0xMy41NjMtMi4xNjMtMTguNDc4cy0xMi41Ni01LjgxMS0xOC40NzgtMi4xNjJsLTg1LjE1NSA1Mi40OSA1My4zMDUgNTMuMzA1eiIgZmlsbD0iI2ZmZmZmZiIvPjxwYXRoIGQ9Im0xMDEuODQ2IDM0Mi4yNzMgOTYuNzExIDM4Ljk0NyAzNS44NTEtMzYuMDY3LTY3LjUyNi02Ny41MjZ6IiBmaWxsPSIjZmZmZmZmIi8+PHBhdGggZD0ibTQuNDc0IDQzOS4wNmMtMi44MjcgMi44MTEtNC40MTkgNi42My00LjQyNSAxMC42MTZzMS41NzUgNy44MTEgNC4zOTQgMTAuNjI5bDQ3LjI4NyA0Ny4yODdjMi44MTMgMi44MTMgNi42MjggNC4zOTQgMTAuNjA2IDQuMzk0aC4wMjJjMy45ODYtLjAwNiA3LjgwNi0xLjU5OSAxMC42MTYtNC40MjZsNTIuODM5LTUzLjE1Ny05Ny4zMzgtMzkuMnoiIGZpbGw9IiNmZmZmZmYiLz48cGF0aCBkPSJtNTEuNjMxIDM5Mi4xODcgOTcuMTQgMzkuMTIgMjYuODI4LTI2Ljk5MS05Ni45MDgtMzkuMDI3eiIgZmlsbD0iI2ZmZmZmZiIvPjxwYXRoIGQ9Im0yMzQuMzQ1IDMwMi42NjMgMTYyLjExMy0xNjMuMDk0LTIzLjk5Mi0yMy45OTItMTYzLjA5NCAxNjIuMTE0eiIgZmlsbD0iI2ZmZmZmZiIvPjxwYXRoIGQ9Im0zNzUuNTIzIDI3Ni42OTMtMjAuMzg1LTUzLTM2LjUyIDM2Ljc0MnoiIGZpbGw9IiNmZmZmZmYiLz48cGF0aCBkPSJtNTA2LjQ4OCAyMTAuODU3Yy0yLjM4Ny02LjMzMi04LjcyMy0xMC4yODQtMTUuNDU0LTkuNjQyLTE5LjM5NiAxLjg0Ni0zNS43MTYtMi43ODUtNDUuOTYzLTEzLjAzM2wtMjcuNC0yNy40LTM5LjM1OCAzOS41OTYgMjUuMjEgNjUuNTQ1YzQuMTEgMTAuNjg2IDEuODYyIDIyLjU3MS01Ljg2NiAzMS4wMi01Ljc5MiA2LjMzMS0xMy43ODIgOS43NjgtMjIuMDU2IDkuNzY4LTIuNzY2IDAtNS41NjItLjM4NC04LjMyLTEuMTcybC03Mi44MTctMjAuODA1LTM4LjkwNyAzOS4xNDIgMjYuOTEgMjYuOTFjMTMuMDYyIDEzLjA2MiAxNC4yOTcgMzEuNjYxIDEzLjAzNCA0NC45NjQtLjY0IDYuNzMyIDMuMzA2IDEzLjA2MSA5LjYzMyAxNS40NSA5Ljc4MSAzLjY5NCAyMC42NzggNS41NTEgMzIuMjY2IDUuNTUxIDguMzQ4IDAgMTcuMDU2LS45NjQgMjUuOTYtMi44OTggMzIuMDIxLTYuOTQ5IDY0LjYzMy0yNS43NTMgOTEuODI5LTUyLjk0OSA0Ni44NTMtNDYuODU1IDY3LjQ2OS0xMDcuMTUzIDUxLjI5OS0xNTAuMDQ3eiIgZmlsbD0iI2ZmZmZmZiIvPjxwYXRoIGQ9Im0yMzQuMzcgMTM1LjIzNCAxNi43NTggNTguNjUyIDM3Ljg2OC0zNy42NDF6IiBmaWxsPSIjZmZmZmZmIi8+PHBhdGggZD0ibTExNi4yODYgMjE2LjUzNWMxMy4zMDUtMS4yNiAzMS45MDItLjAyOCA0NC45NjMgMTMuMDMzbDI2LjkwOSAyNi45MDkgMzguNjctMzguNDM4LTIxLjMwNC03NC41NjNjLTMuMTQ2LTExLjAwOS4xNDgtMjIuNjQ3IDguNTk2LTMwLjM3NnMyMC4zMzMtOS45NzUgMzEuMDItNS44NjVsNjcuMTcyIDI1LjgzNCAzOC45NC0zOC43MDctMjcuMzk5LTI3LjM5OGMtMTAuMjQ3LTEwLjI0Ny0xNC44NzUtMjYuNTctMTMuMDMzLTQ1Ljk2My42NC02LjczMy0zLjMwNi0xMy4wNjItOS42MzMtMTUuNDUxLTE2LjgyOS02LjM1NS0zNi45NjUtNy4yNzEtNTguMjI1LTIuNjUyLTMxLjE5NyA2Ljc3LTYzLjgxIDI1LjkzLTkxLjgzIDUzLjk0OS00Ni40MTggNDYuNDE5LTY2LjYzMiAxMDYuNzE4LTUwLjMgMTUwLjA0NiAyLjM4NyA2LjMzMiA4LjcxOCAxMC4yODcgMTUuNDU0IDkuNjQyeiIgZmlsbD0iI2ZmZmZmZiIvPjwvZz48L3N2Zz4=',
			'capability_type' => 'post',
			'supports' => array('title', 'editor', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields'),
			//'register_meta_box cb' => callback for meta boxes
			'taxonomies' => array('wowpi_guild_race', 'wowpi_guild_class_spec'),
		));
	}

}