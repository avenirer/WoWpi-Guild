<?php


namespace WowpiGuild\Includes;


use WowpiGuild\Config\Settings;

class Shortcodes {

	public function init() {

		//add_shortcode('wowpi_character','wowpi_shortcode_character');
		add_shortcode('wowpi_guild_roster', array($this, 'getRoster'));
		//add_shortcode('wowpi_guild_progression','wowpi_shortcode_guild_progression');
		//add_shortcode('wowpi_guild_achievements','wowpi_shortcode_guild_achievements');
		//add_shortcode('wowpi_tabard','wowpi_shortcode_guild_tabard');
		//add_shortcode('wowpi_realms','wowpi_shortcode_realms');
	}

	public function getRoster() {
		wp_enqueue_script( 'wowpi-guild-roster', Settings::pluginUrl() . 'dist/public/js/wowpi-guild-roster.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( 'wowpi-guild-roster', 'wowpiRosterAjax', array( 'ajaxurl' => admin_url('admin-ajax.php?action=getRoster')) );

		$output = '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">';
		$output .= '<table id="wowpi_guild_roster">
    <thead>
        <tr>
            <th>Name</th>
            <th>Race</th>
            <th>Class</th>
            <th>Role</th>
            <th>Level</th>
            <th>Guild Rank</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Name</th>
            <th>Race</th>
            <th>Class</th>
            <th>Role</th>
            <th>Level</th>
            <th>Guild Rank</th>
        </tr>
    </tfoot></table>';
		return $output;
	}

}