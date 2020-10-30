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

	public function getRoster($atts) {


		$pull_guild_atts = shortcode_atts( array(
			'ranks' => '', // 1|2|3 or 1:Rankname|2:Blaubu
			'id' => 'wowpi_guild_roster',
			'class' => '',
			'rows' => '25', // can be a number or 'all'
			'linkto' => 'simple',
		), $atts );

		$tableId = sanitize_html_class($pull_guild_atts['id']);
		$tableClass = sanitize_html_class($pull_guild_atts['class']);
		$rows = sanitize_text_field($pull_guild_atts['rows']);
		$ranks = sanitize_text_field($pull_guild_atts['ranks']);

		$ajaxUrl = admin_url('admin-ajax.php?action=getRoster');

		if($ranks) {
			$ajaxUrl .= '&ranks='.$ranks;
		}

		$rows = is_numeric($rows) ? intval($rows) : 1000;

        $columns = array(
            array(
                'data' => 'name',
                'sortable' => true,
            ),
            array(
                'data' => 'race',
                'sortable' => false,
            ),
            array(
                'data' => 'class',
                'sortable' => false,
            ),
            array(
                'data' => 'role',
                'sortable' => false,
            ),
            array(
                'data' => 'level',
                'sortable' => true,
            ),
            array(
                'data' => 'rank',
                'sortable' => false,
            ),
        );

        wp_enqueue_style('wowpi-guild-roster', Settings::pluginUrl() . 'dist/public/css/wowpi-guild-roster.css', array('main'), WOWPI_GUILD_VERSION);
		wp_enqueue_script( 'wowpi-guild-roster', Settings::pluginUrl() . 'dist/public/js/wowpi-guild-roster.js', array( 'jquery' ), WOWPI_GUILD_VERSION, true );
		wp_localize_script( 'wowpi-guild-roster', 'wowpiRosterAjax', array(
			'datatable_id' => $tableId,
			'datatable_class' => $tableClass,
			'datatable_length' => $rows,
			'ajaxurl' => $ajaxUrl,
			'columns' => json_encode($columns)
		) );

		$output = '<table id="wowpi_guild_roster">
    <thead>
        <tr>
            <th>'.__('Name', 'wowpi-guild').'</th>
            <th>'.__('Race', 'wowpi-guild').'</th>
            <th>'.__('Class', 'wowpi-guild').'</th>
            <th>'.__('Role', 'wowpi-guild').'</th>
            <th>'.__('Level', 'wowpi-guild').'</th>
            <th>'.__('Guild Rank', 'wowpi-guild').'</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>'.__('Name', 'wowpi-guild').'</th>
            <th>'.__('Race', 'wowpi-guild').'</th>
            <th>'.__('Class', 'wowpi-guild').'</th>
            <th>'.__('Role', 'wowpi-guild').'</th>
            <th>'.__('Level', 'wowpi-guild').'</th>
            <th>'.__('Guild Rank', 'wowpi-guild').'</th>
        </tr>
    </tfoot></table>';
		//$output .= '<script>jQuery(document).ready(function($){$("#'.$tableId.'").DataTable({'.$datatable_settings.'});});</script>';
		return $output;
	}

}