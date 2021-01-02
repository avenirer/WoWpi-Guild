<?php
namespace WowpiGuild\Admin;
use WowpiGuild\Api\Connector;
use WowpiGuild\Api\Guild;
use WowpiGuild\Api\Realm;
use WowpiGuild\Includes\WowpiCron;

/**
 * The settings of the plugin.
 *
 * @link       https://avenir.ro
 * @since      1.0.0
 *
 * @package    Wowpi Guild
 * @subpackage wowpi-guild/admin
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class AdminSettings {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $credentials;
	private $guild;

	private $regions = array(
		'us' => 'North America',
		'eu' => 'Europe',
		'kr' => 'Korea',
		'tw' => 'Taiwan',
		'cn' => 'China',
	);

	private $locales = array(
		'zh_CN' => 'Chinese (Simplified)',
		'zh_TW' => 'Chinese (Traditional)',
		'en_GB' => 'English (Great Britain)',
		'en_US' => 'English (United States)',
		'fr_FR' => 'French',
		'de_DE' => 'German',
		'it_IT' => 'Italian',
		'ko_KR' => 'Korean',
		'pt_BR' => 'Portuguese',
		'ru_RU' => 'Russian',
		'es_ES' => 'Spanish',
		'es_MX' => 'Spanish (Mexico)',
	);

	private $games = array(
	        'current' => 'World of Warcraft',
	        //'classic' => 'World of Warcraft Classic',
    );

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->credentials = get_option( 'wowpi_guild_credentials' );
		$this->guild = get_option( 'wowpi_guild_guild' );

	}

	/**
	 * This function introduces the theme options into the 'Appearance' menu and into a top-level
	 * 'WoWpi Guild' menu.
	 */
	public function setup_plugin_options_menu() {

		//Add the menu to the Plugins set of menu items
        /*
		add_plugins_page(
			'WoWpi Guild options',           // The title to be displayed in the browser window for this page.
			'WoWpi Guild options',          // The text to be displayed for this menu item
			'manage_options',          // Which type of users can see this menu item
			'wowpi_guild_options',      // The unique ID - that is, the slug - for this menu item
			array( $this, 'render_settings_page_content')        // The name of the function to call when rendering this menu's page
		);*/

		add_menu_page(
			'WoWpi Guild',
			'WoWpi Guild',
			'manage_options',
			'wowpi_guild_settings',
			array( $this, 'render_settings_page_content'),
			'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMzJwdCIgaGVpZ2h0PSIzMnB0IiB2aWV3Qm94PSIwIDAgMzIgMzIiIHZlcnNpb249IjEuMSI+CjxnIGlkPSJzdXJmYWNlMTIxMDkwNDgiPgo8cGF0aCBzdHlsZT0iIHN0cm9rZTpub25lO2ZpbGwtcnVsZTpub256ZXJvO2ZpbGw6cmdiKDEwMCUsMTAwJSwxMDAlKTtmaWxsLW9wYWNpdHk6MTsiIGQ9Ik0gMTYgNCBDIDEzLjYzMjgxMiA0IDExLjM1MTU2MiA0LjY5MTQwNiA5LjM3ODkwNiA2IEwgNiA2IEwgNiA5LjM3ODkwNiBDIDQuNjkxNDA2IDExLjM1MTU2MiA0IDEzLjYzMjgxMiA0IDE2IEMgNCAxOC4zNjcxODggNC42OTE0MDYgMjAuNjQ4NDM4IDYgMjIuNjIxMDk0IEwgNiAyNiBMIDkuMzc4OTA2IDI2IEMgMTEuMzUxNTYyIDI3LjMwODU5NCAxMy42MzI4MTIgMjggMTYgMjggQyAxOC4zNjcxODggMjggMjAuNjQ4NDM4IDI3LjMwODU5NCAyMi42MjEwOTQgMjYgTCAyNiAyNiBMIDI2IDIyLjYyMTA5NCBDIDI3LjMwODU5NCAyMC42NDg0MzggMjggMTguMzY3MTg4IDI4IDE2IEMgMjggMTMuNjMyODEyIDI3LjMwODU5NCAxMS4zNTE1NjIgMjYgOS4zNzg5MDYgTCAyNiA2IEwgMjIuNjIxMDk0IDYgQyAyMC42NDg0MzggNC42OTE0MDYgMTguMzY3MTg4IDQgMTYgNCBaIE0gMTYgNiBDIDE4LjA2MjUgNiAyMC4wNDY4NzUgNi42Mjg5MDYgMjEuNzM4MjgxIDcuODE2NDA2IEwgMjEuOTk2MDk0IDggTCAyNCA4IEwgMjQgMTAuMDAzOTA2IEwgMjQuMTc5Njg4IDEwLjI2MTcxOSBDIDI1LjM3MTA5NCAxMS45NTcwMzEgMjYgMTMuOTQxNDA2IDI2IDE2IEMgMjYgMTguMDYyNSAyNS4zNzEwOTQgMjAuMDQ2ODc1IDI0LjE3OTY4OCAyMS43MzgyODEgTCAyNCAyMS45OTYwOTQgTCAyNCAyNCBMIDIxLjk5NjA5NCAyNCBMIDIxLjczODI4MSAyNC4xNzk2ODggQyAyMC4wNDY4NzUgMjUuMzcxMDk0IDE4LjA2MjUgMjYgMTYgMjYgQyAxMy45NDE0MDYgMjYgMTEuOTU3MDMxIDI1LjM3MTA5NCAxMC4yNjE3MTkgMjQuMTc5Njg4IEwgMTAuMDAzOTA2IDI0IEwgOCAyNCBMIDggMjEuOTk2MDk0IEwgNy44MjAzMTIgMjEuNzM4MjgxIEMgNi42Mjg5MDYgMjAuMDQ2ODc1IDYgMTguMDYyNSA2IDE2IEMgNiAxMy45NDE0MDYgNi42Mjg5MDYgMTEuOTU3MDMxIDcuODIwMzEyIDEwLjI2MTcxOSBMIDggMTAuMDAzOTA2IEwgOCA4IEwgMTAuMDAzOTA2IDggTCAxMC4yNjE3MTkgNy44MTY0MDYgQyAxMS45NTcwMzEgNi42Mjg5MDYgMTMuOTQxNDA2IDYgMTYgNiBaIE0gOSAxMSBMIDkuNzk2ODc1IDExLjc5Njg3NSBDIDkuOTI5Njg4IDExLjkyOTY4OCAxMC4wMjM0MzggMTIuMDk3NjU2IDEwLjA2MjUgMTIuMjg1MTU2IEwgMTEuODgyODEyIDIwLjQ2NDg0NCBDIDExLjk1MzEyNSAyMC44MDA3ODEgMTEuODUxNTYyIDIxLjE0ODQzOCAxMS42MTMyODEgMjEuMzkwNjI1IEwgMTEgMjIgTCAxNSAyMiBMIDE0LjUzNTE1NiAyMS4wNzQyMTkgTCAxNiAxNyBMIDE3LjQ2NDg0NCAyMS4wNzQyMTkgTCAxNyAyMiBMIDIxIDIyIEwgMjAuMzg2NzE5IDIxLjM5MDYyNSBDIDIwLjE0ODQzOCAyMS4xNDg0MzggMjAuMDQ2ODc1IDIwLjgwMDc4MSAyMC4xMTcxODggMjAuNDY0ODQ0IEwgMjEuOTM3NSAxMi4yODUxNTYgQyAyMS45NzY1NjIgMTIuMDk3NjU2IDIyLjA3MDMxMiAxMS45Mjk2ODggMjIuMjAzMTI1IDExLjc5Njg3NSBMIDIzIDExIEwgMTkgMTEgTCAxOS4zMzU5MzggMTEuNjY3OTY5IEMgMTkuNDQxNDA2IDExLjg4MjgxMiAxOS40Njg3NSAxMi4xMjg5MDYgMTkuNDEwMTU2IDEyLjM1OTM3NSBMIDE4LjI4NTE1NiAxNi44NjcxODggTCAxNiAxMSBMIDEzLjcxNDg0NCAxNi44NjcxODggTCAxMi41ODk4NDQgMTIuMzU5Mzc1IEMgMTIuNTMxMjUgMTIuMTI4OTA2IDEyLjU1ODU5NCAxMS44ODI4MTIgMTIuNjY0MDYyIDExLjY2Nzk2OSBMIDEzIDExIFogTSA5IDExICIvPgo8L2c+Cjwvc3ZnPgo='
			//plugin_dir_url( __FILE__ ) . 'img/wow-icon.png'
        );

	}

	/**
	 * Renders a simple page to display for the theme menu defined above.
	 */
	public function render_settings_page_content( $active_tab = '' ) {
		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php _e( 'WoWpi Guild options', 'wowpi-guild' ); ?></h2>
			<?php settings_errors(); ?>

			<?php if( isset( $_GET[ 'tab' ] ) ) {
				$active_tab = sanitize_text_field($_GET[ 'tab' ]);
			} else {
				$active_tab = 'credential_section';
			} // end if/else ?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=wowpi_guild_settings&tab=credential_section" class="nav-tab <?php echo $active_tab == 'credential_section' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Step 1: Battle.net credentials', 'wowpi-guild' ); ?></a>
                <a href="?page=wowpi_guild_settings&tab=realm_guild_section" class="nav-tab <?php echo $active_tab == 'realm_guild_section' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Step 2: Realm and Guild', 'wowpi-guild' ); ?></a>
                <a href="?page=wowpi_guild_settings&tab=synchronizing_section" class="nav-tab <?php echo $active_tab == 'synchronizing_section' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Step 3: Synchronize data', 'wowpi-guild' ); ?></a>
			</h2>

			<form method="post" action="options.php">
				<?php

				if( $active_tab == 'credential_section' ) {
					settings_fields( 'wowpi_guild_credentials' );
					do_settings_sections( 'wowpi_guild_credentials' );
					submit_button();

				} elseif( $active_tab == 'realm_guild_section' ) {

					settings_fields( 'wowpi_guild_guild' );
					do_settings_sections( 'wowpi_guild_guild' );
					submit_button();

				} elseif( $active_tab == 'synchronizing_section') {
				    //settings_fields('wowpi_guild_syncronize');
					do_settings_sections( 'wowpi_guild_synchronize' );
				}

				?>
			</form>

		</div><!-- /.wrap -->
		<?php
	}

	//-------------------------------------------------- CREDENTIALS SECTION ------------------------------------------------//
	/**
	 * Initializes the Battle.net credentials page by registering the Sections,
	 * Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook inside 'includes/class-wowpi-guild'.
	 */
	public function initialize_credentials_settings() {

		add_settings_section( 'credentials_section', __( 'Battle.net credentials', 'wowpi-guild' ), array(
			$this,
			'credentials_callback'
		), 'wowpi_guild_credentials' );
		add_settings_field( 'client_id', __( 'Client ID', 'wowpi-guild-plugin' ), array(
			$this,
			'client_id_callback'
		), 'wowpi_guild_credentials', 'credentials_section', array( 'id' => 'client_id', ) );
		add_settings_field('client_secret', __( 'Client secret', 'wowpi-guild' ), array($this, 'client_secret_callback'), 'wowpi_guild_credentials', 'credentials_section', array( 'id' => 'client_secret', ) );
		add_settings_field('game', __( 'Game', 'wowpi-guild' ), array($this, 'game_callback'),'wowpi_guild_credentials', 'credentials_section', array( 'id' => 'game', ) );
		add_settings_field('region', __('Region', 'wowpi-guild' ), array($this, 'region_callback'),'wowpi_guild_credentials','credentials_section', array( 'id' => 'region', ));
		add_settings_field('locale', __('Locale', 'wowpi-guild' ), array($this, 'locale_callback'),'wowpi_guild_credentials','credentials_section', array( 'id' => 'locale', ));
		register_setting( 'wowpi_guild_credentials', 'wowpi_guild_credentials', array(
			'sanitize_callback' => array(
				$this,
				'sanitize_credentials'
			),
		) );
	}

	public function credentials_callback() {

		$text = '<p>It is very important to know that before you can use the APIs you need to <a href="https://develop.battle.net/access" target="_blank">register to Battle.net API</a>. Once you register, you must create an "application" inside the admin section of Battle.net to get an API key</p>';
		echo $text;
	}

	/**
	 * This function renders the interface elements for client ID.
	 *
	 * It accepts an array or arguments and expects the first element in the array to be the description
	 * to be displayed next to the checkbox.
	 */
	public function client_id_callback($args) {

		// First, we read the options collection
		$client_id = $this->credentials['client_id'];

		$html = '<input type="text" id="client_id" name="wowpi_guild_credentials[client_id]" value="' . $client_id . '" />';
		echo $html;

	} // end client_id_callback

	public function client_secret_callback($args) {

		$client_secret = $this->credentials['client_secret'];

		$html = '<input type="password" id="client_secret" name="wowpi_guild_credentials[client_secret]" value="' . $client_secret . '" />';
		echo $html;

	} // end client_secret_callback

	public function game_callback($args) {

		$html = '<select id="game" name="wowpi_guild_credentials[game]">';
		foreach($this->games as $game => $title) {
			$html .= '<option value="' . $game . '"' . ( $this->credentials['game'] == $game ? ' selected' : '' ) . '>' . $title . '</option>';
		}
		$html .= '</select>';
		echo $html;

	} // end client_secret_callback

	public function region_callback() {

		echo '<select id="region" name="wowpi_guild_credentials[region]">';
		foreach ( $this->regions as $region => $name ) {
			echo '<option value="' . $region . '"' . ( $this->credentials['region'] == $region ? ' selected' : '' ) . '>' . $name . '</option>';
		}
		echo '</select>';
	}

	public function locale_callback() {

		echo '<select id="locale" name="wowpi_guild_credentials[locale]">';

		foreach($this->locales as $locale => $name)
		{
			echo '<option value="'.$locale.'"'.($this->credentials['locale'] == $locale ? ' selected' : '').'>'.$name.'</option>';
		}
		echo '</select>';
	}

	public function sanitize_credentials( $input ) {

		if(!array_key_exists('wowpi_guild_credentials', $_POST)) {
			add_settings_error( 'wowpi_guild', '', 'There\'s nothing to save' );
		}

		// check if client id exists
		if(! $input['client_id']) {
			add_settings_error( 'wowpi_guild', '', 'You didn\'t provide Client ID connection credentials received from Battle.net Developer Portal' );
		}
		$this->credentials['client_id'] = $input['client_id'];

		// check if client secret exists
		if(! $input['client_secret']) {
			add_settings_error( 'wowpi_guild', '', 'You didn\'t provide Client secret connection credentials received from Battle.net Developer Portal' );
		}
		$this->credentials['client_secret'] = $input['client_secret'];

		if( ! array_key_exists($input['game'], $this->games)) {
			add_settings_error( 'wowpi_guild', '', 'You must select a proper World of Warcraft game' );
		}
		$this->credentials['game'] = $input['game'];

		// check for curl support
		if(in_array('curl', get_loaded_extensions())) {
			add_settings_error ( 'wowpi_guild', '', 'cURL (PHP extension) is available on your webserver', 'success');
		}
		else {
			add_settings_error('wowpi_guild', '', 'cURL (PHP extension) is not available on your webserver. Most likely the plugin won\'t work!');
		}

		$getRealms = true;
		// check if valid client id and client secret
		$connector = new Connector($this->credentials);
		if( ! $connector->getToken() ) {
			add_settings_error( 'client_id', '', 'The Client ID and Client secret does not seem to work... Make sure you have correct credentials.' );
			$getRealms = false;
		}
		else {
			add_settings_error('client_id', '', 'The Client ID and Client secret have been verified on Blizzard\'s Battle.net.', 'success');
		}

		if( ! array_key_exists($input['region'], $this->regions)) {
			$getRealms = false;
			add_settings_error( 'region', '', 'You must select a proper World of Warcraft Region' );
		}
		$this->credentials['region'] = $input['region'];

		if( ! array_key_exists($input['locale'], $this->locales)) {
			$getRealms = false;
			add_settings_error( 'locale', '', 'You must select a proper World of Warcraft locale' );
		}
		$this->credentials['locale'] = $input['locale'];

		if($getRealms) {
			$realmConnector = new Realm($this->credentials);
			$available_realms = $realmConnector->index();
			update_option('wowpi_guild_realms', $available_realms);
		}

		return $input;
	}

	//------------------------------------------------------ REALM AND GUILD SECTION --------------------------------------------------//
	public function initialize_realm_guild_settings() {

		add_settings_section('realm_guild_section',	__( 'Realm and Guild settings', 'wowpi-guild' ), array( $this, 'guild_callback'),'wowpi_guild_guild');
		add_settings_field('realm', __('Realm', 'wowpi-guild' ), array($this, 'realm_callback'),'wowpi_guild_guild','realm_guild_section', array( 'id' => 'realm', ));
		add_settings_field('guild', __('Guild', 'wowpi-guild' ), array($this, 'guild_name_callback'),'wowpi_guild_guild','realm_guild_section', array( 'id' => 'guild', ));
		register_setting('wowpi_guild_guild', 'wowpi_guild_guild', array('sanitize_callback' => array( $this, 'sanitize_realm_guild'),));

	}

	public function guild_callback() {
		$text = '<p>For this to work, make sure you\'ve saved the correct credentials in step 1.</p>';
		echo $text;
	}

	public function realm_callback() {

		$available_realms = get_option('wowpi_guild_realms', array());

		if(empty($available_realms)) {
			echo '<strong>Something must have gone wrong. Please, make sure you have saved Step 1, before going to Step 2</strong>';
		}

		else {
			echo '<select id="realm" name="wowpi_guild_guild[realm_slug]">';
			foreach ( $available_realms as $realm ) {
				echo '<option value="' . $realm['slug']
				     . '"' . ( $this->guild['realm_slug'] == $realm['slug'] ? ' selected' : '' )
				     . '>' . $realm['name'] . '</option>';
			}
			echo '</select>';
		}
	}

	public function guild_name_callback($args) {

		// First, we read the options collection
		$guild = $this->guild;

		$html = '<input type="text" id="guild" name="wowpi_guild_guild[slug]" value="' . $guild['slug'] . '" />';
		$html .= '<p>In here, please write the slug of the guild name.<br />You can find the slug by looking at the guild page on World of Warcraft official website.<br /> In there, the url should be something like: https://worldofwarcraft.com/en-gb/guild/eu/realm_slug/<strong>guild_slug</strong>. We need that guild_slug</p>';
		echo $html;

	}

	public function sanitize_realm_guild($input) {

		$guild_slug = $input['slug'];
		if(strtolower($guild_slug) != $guild_slug) {
			add_settings_error('guild', '', 'Make sure you have the correct guild name slug. If not, we will approximate the naming.','warning');
			$guild_slug = sanitize_title($guild_slug);
		}

		$guildConnector = new Guild($input['realm_slug'], $guild_slug);
		$guild = $guildConnector->guild(false);

		if( ! $guild ) {
			add_settings_error('guild', '', 'You didn\'t type a valid guild slug, or the guild was not found');
			return $input;
		}
		else {
		    $input = $guild;
		    $this->guild = $guild;
		}

		return $input;
	}

	//------------------------------------------------------ SYNCHRONIZING SECTION --------------------------------------------------//
	public function initialize_synchronizing() {

		add_settings_section('synchronize_section',	__( 'Synchronize static data', 'wowpi-guild' ), array( $this, 'synchronize_callback'),'wowpi_guild_synchronize');

	}

	public function synchronize_callback() {
		$text = '<p>For this to work, make sure you\'ve saved the correct credentials in step 1 and step 2. This allows you to synchronize data.</p>';

		$synchronize = array(
		    'races' => 'Synchronize races',
            'classes' => 'Synchronize classes and specializations',
            //'guild' => 'Synchronize guild data',
            //'achievements' => 'Synchronize achievements',
        );

		foreach($synchronize as $api => $title) {
			$text .= '<p><a href="javascript:void(0);" class="button button-primary synchronize" data-api="'.$api.'">' . $title . '</a></p>';
			$text .= '<div id="synchronize-' . $api . '-results" class="synch-results"><div class="lds-ripple"><div></div><div></div></div><div class="results"></div></div>';
		}

		$text .= '<p><a href="javascript:void(0);" class="button button-primary synchronize-guild" data-forced="true">Synchronize guild roster</a>&nbsp;&nbsp;<input type="checkbox" name="synchall" id="synchall"> Synch with all character data</p>';
		$text .= '<div id="synchronize-guild-results" class="synch-results"><div class="lds-ripple"><div></div><div></div></div><div class="results"></div></div>';
		echo $text;
	}






}