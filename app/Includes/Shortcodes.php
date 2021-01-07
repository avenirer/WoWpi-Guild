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
			'order_by' => 'level desc', // can be name, level or rank with asc or desc
			'linkto' => 'simple',
			'show_search' => '1', // can be 0 or 1
			'show_select_page_length' => '1' // can be 0 or 1
		), $atts );

		$tableId = sanitize_html_class($pull_guild_atts['id']);
		$tableClass = sanitize_html_class($pull_guild_atts['class']);
		$rows = sanitize_text_field($pull_guild_atts['rows']);
		$ranks = sanitize_text_field($pull_guild_atts['ranks']);
		$orderBy = sanitize_text_field($pull_guild_atts['order_by']);
		$showSearch = intval($pull_guild_atts['show_search']);
		$showSelectPageLength = intval($pull_guild_atts['show_select_page_length']);
		$ajaxUrl = admin_url('admin-ajax.php?action=getRoster');

		/*
		if(strlen($ranks) > 0) {
			$ajaxUrl .= '&ranks='.$ranks;
		}
		*/

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
			'ajaxurl' => $ajaxUrl,
			'columns' => json_encode($columns)
		) );

		return '<table id="' . $tableId . '" class="wowpi-roster ' . $tableClass .'" data-length="'.$rows.'" data-orderby="'.$orderBy.'" data-showsearch="' . $showSearch . '" data-showselectlength="' . $showSelectPageLength . '" data-ranks="'. rawurlencode($ranks) .'">
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
	}

}