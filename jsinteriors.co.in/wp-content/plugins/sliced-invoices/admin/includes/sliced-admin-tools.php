<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class called from sliced-admin-options.php.
 * All extra pages are added from there
 */
class Sliced_Tools {

    //public $pagehook = 'sliced_tools';

    public function __construct() {

    	$importer = new Sliced_Csv_Importer();
		$exporter = new Sliced_Csv_Exporter();
    	add_action( 'sliced_tools_tab_system_info', array(&$this, 'sliced_tools_system_info_display' ) );
    	add_action( 'sliced_tools_tab_importer', array(&$importer, 'display_importer_page' ) );
		add_action( 'sliced_tools_tab_exporter', array(&$exporter, 'display_exporter_page' ) );
    	add_action( 'admin_init', array( &$this, 'sliced_tools_system_info_download' ) );

    }


    /**
     * Setup the page display.
     *
     * @since   2.06
     */
    public function display_tools_page() {

		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'system_info';
		?>
		<div class="wrap">
			<h2><?php _e( 'Sliced Invoices Tools', 'sliced-invoices' ); ?></h2>
			<h2 class="nav-tab-wrapper">
				<?php
				foreach( $this->sliced_get_tools_tabs() as $tab_id => $tab_name ) {

					$tab_url = add_query_arg( array(
						'tab' => $tab_id
					) );

					$tab_url = remove_query_arg( array(
						'sliced-message'
					), $tab_url );

					$active = $active_tab == $tab_id ? ' nav-tab-active' : '';
					echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">' . esc_html( $tab_name ) . '</a>';

				}
				?>
			</h2>
			<div class="metabox-holder">
				<?php
				do_action( 'sliced_tools_tab_' . $active_tab );
				?>
			</div><!-- .metabox-holder -->
		</div><!-- .wrap -->
		<?php
	}


	/**
	 * Retrieve tools tabs
	 *
	 * @since       2.0
	 * @return      array
	 */
	public function sliced_get_tools_tabs() {

		$tabs                  	= array();
		$tabs['system_info']   	= __( 'System Info', 'sliced-invoices' );
		$tabs['importer'] 		= __( 'Import CSV', 'sliced-invoices' );
		$tabs['exporter'] 		= __( 'Export CSV', 'sliced-invoices' );

		return apply_filters( 'sliced_tools_tabs', $tabs );
	}

	/**
	 * Display the system info tab
	 *
	 * @since       2.0
	 * @return      void
	 */
	public function sliced_tools_system_info_display() {

		if( ! current_user_can( 'manage_options' ) ) {
			return;
		}
	?>
		<form action="<?php echo esc_url( admin_url( 'admin.php?page=sliced_tools&tab=system_info' ) ); ?>" method="post" dir="ltr">
			<textarea rows="30" cols="80" readonly="readonly" onclick="this.focus(); this.select()" id="system-info-textarea" name="sliced-sysinfo" title="To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac)."><?php echo $this->get_system_info(); ?></textarea>
			<p class="submit">
				<input type="hidden" name="sliced-action" value="download_sysinfo" />
				<?php submit_button( 'Download System Info File', 'primary', 'sliced-download-sysinfo', false ); ?>
			</p>
		</form>
	<?php
	}


	/**
	 * Get system info.
	 * 
	 * @version 3.9.1
	 * @since   2.0
	 * 
	 * @global  object $wpdb Used to query the database using the WordPress Database API
	 * @return  string $return A string containing the info to output
	 */
	public function get_system_info() {
		global $wpdb;

		if( !class_exists( 'Browser' ) )
			require_once 'browser.php';

		$browser = new Browser();

		// Get theme info
		$theme_data = wp_get_theme();
		$theme      = $theme_data->Name . ' ' . $theme_data->Version;

		// Try to identify the hosting provider
		$host = $this->get_host();

		$return  = '### Begin System Info ###' . "\n\n";

		// Start with the basics...
		$return .= '/////-- Site Info' . "\n\n";
		$return .= 'Site URL:                 ' . site_url() . "\n";
		$return .= 'Home URL:                 ' . home_url() . "\n";
		$return .= 'Multisite:                ' . ( is_multisite() ? 'Yes' : 'No' ) . "\n";

		$return  = apply_filters( 'sliced_sysinfo_after_site_info', $return );

		// Can we determine the site's host?
		if( $host ) {
			$return .= "\n" . '/////-- Hosting Provider' . "\n\n";
			$return .= 'Host:                     ' . $host . "\n";

			$return  = apply_filters( 'sliced_sysinfo_after_host_info', $return );
		}

		// The local users' browser information, handled by the Browser class
		$return .= "\n" . '/////-- User Browser' . "\n\n";
		$return .= $browser;

		$return  = apply_filters( 'sliced_sysinfo_after_user_browser', $return );

		// WordPress configuration
		$return .= "\n" . '/////-- WordPress Configuration' . "\n\n";

		$return .= 'Version:                  ' . get_bloginfo( 'version' ) . "\n";
		$return .= 'Language:                 ' . ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' ) . "\n";
		$return .= 'Permalink Structure:      ' . ( get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default' ) . "\n";
		$return .= 'Active Theme:             ' . $theme . "\n";
		$return .= 'Show On Front:            ' . get_option( 'show_on_front' ) . "\n";

		// Only show page specs if frontpage is set to 'page'
		if( get_option( 'show_on_front' ) == 'page' ) {
			$front_page_id = get_option( 'page_on_front' );
			$blog_page_id = get_option( 'page_for_posts' );

			$return .= 'Page On Front:            ' . ( $front_page_id != 0 ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset' ) . "\n";
			$return .= 'Page For Posts:           ' . ( $blog_page_id != 0 ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset' ) . "\n";
		}

		// Make sure wp_remote_post() is working
		$request['cmd'] = '_notify-validate';

		$params = array(
			'sslverify'     => false,
			'timeout'       => 60,
			'user-agent'    => 'sliced/',
			'body'          => $request
		);

		$response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );

		if( !is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
			$WP_REMOTE_POST = 'wp_remote_post() works';
		} else {
			$WP_REMOTE_POST = 'wp_remote_post() does not work';
		}

		$return .= 'Remote Post:              ' . $WP_REMOTE_POST . "\n";
		$return .= 'Table Prefix:             ' . 'Length: ' . strlen( $wpdb->prefix ) . '   Status: ' . ( strlen( $wpdb->prefix ) > 16 ? 'ERROR: Too long' : 'Acceptable' ) . "\n";
		// Commented out per https://github.com/easydigitaldownloads/Easy-Digital-Downloads/issues/3475
		//$return .= 'Admin AJAX:               ' . ( sliced_test_ajax_works() ? 'Accessible' : 'Inaccessible' ) . "\n";
		$return .= 'WP_DEBUG:                 ' . ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
		$return .= 'Memory Limit:             ' . WP_MEMORY_LIMIT . "\n";
		$return .= 'Registered Post Status:   ' . implode( ', ', get_post_stati() ) . "\n";

		$return  = apply_filters( 'sliced_sysinfo_after_wordpress_config', $return );

		// sliced configuration
		$plugin_data = get_plugin_data( SLICED_PATH . 'sliced-invoices.php', false );
		// database settings
		$general_opt  = get_option('sliced_general');
		$business_opt = get_option('sliced_business');
		$payment_opt  = get_option('sliced_payments');
		$tax_opt      = get_option('sliced_tax');
		$invoices_opt = get_option('sliced_invoices');
		$quotes_opt   = get_option('sliced_quotes');
		$email_opt    = get_option('sliced_emails');
		$pdf_opt      = get_option( 'sliced_pdf' );
		
		// mask sensitive info
		$sensitive_infos = apply_filters( 'sliced_sysinfo_sensitive_infos', array(
			'bank',
			'generic_pay',
			'paypal_username',
			'paypal_username_sandbox',
			'paypal_password',
			'paypal_password_sandbox',
			'paypal_signature',
			'paypal_signature_sandbox',
			'2checkout_account',
			'2checkout_secret_word',
			'2checkout_publishable_key',
			'2checkout_private_key',
			'2checkout_admin_api_username',
			'2checkout_admin_api_password',
			'authorize_net_live_id',
			'authorize_net_live_key',
			'authorize_net_live_signature',
			'authorize_net_sandbox_id',
			'authorize_net_sandbox_key',
			'authorize_net_sandbox_signature',
			'braintree_merchant_id',
			'braintree_public_key',
			'braintree_private_key',
			'braintree_sandbox_merchant_id',
			'braintree_sandbox_public_key',
			'braintree_sandbox_private_key',
			'stripe_secret',
			'stripe_secret_test',
			'stripe_publishable',
			'stripe_publishable_test',
		) );
		foreach ( $sensitive_infos as $sensitive_info ) {
			if ( isset( $payment_opt[$sensitive_info] ) ) {
				$payment_opt[$sensitive_info] = empty( $payment_opt[$sensitive_info] ) ? '<not set>' : '<set>';
			}
		}

		$return .= "\n" . '/////-- Sliced Invoices Configuration' . "\n\n";
		$return .= 'Version:                  ' . $plugin_data['Version'] . "\n";
		$return .= "\n";
		$return .= '== General Settings ==         ' . "\n";
		foreach ($general_opt as $key => $value) {
			if ( is_array( $value ) ) $value = implode( ', ', $value );
			$return .= $key . ':					' . $value . "\n";
		}
		$return .= "\n";
		$return .= '== Business Settings ==         ' . "\n";
		foreach ($business_opt as $key => $value) {
			if ( is_array( $value ) ) $value = implode( ', ', $value );
			$return .= $key . ':					' . $value . "\n";
		}
		$return .= "\n";
		$return .= '== Payment Settings ==         ' . "\n";
		foreach ($payment_opt as $key => $value) {
			if ( is_array( $value ) ) $value = implode( ', ', $value );
			$return .= $key . ':					' . $value . "\n";
		}
		$return .= "\n";
		$return .= '== Tax Settings ==         ' . "\n";
		foreach ($tax_opt as $key => $value) {
			if ( is_array( $value ) ) $value = implode( ', ', $value );
			$return .= $key . ':					' . $value . "\n";
		}
		$return .= "\n";
		$return .= '== Invoices Settings ==         ' . "\n";
		foreach ($invoices_opt as $key => $value) {
			if ( is_array( $value ) ) $value = implode( ', ', $value );
			$return .= $key . ':					' . $value . "\n";
		}
		$return .= "\n";
		$return .= '== Quotes Settings ==         ' . "\n";
		foreach ($quotes_opt as $key => $value) {
			if ( is_array( $value ) ) $value = implode( ', ', $value );
			$return .= $key . ':					' . $value . "\n";
		}
		$return .= "\n";
		$return .= '== Email Settings ==         ' . "\n";
		foreach ($email_opt as $key => $value) {
			if ( is_array( $value ) ) $value = implode( ', ', $value );
			$return .= $key . ':					' . $value . "\n";
		}
		$return .= "\n";
		$return .= '== PDF Settings ==         ' . "\n";
		foreach ( $pdf_opt as $key => $value ) {
			if ( is_array( $value ) ) $value = implode( ', ', $value );
			$return .= $key . ':					' . $value . "\n";
		}
		
		$return = apply_filters( 'sliced_sysinfo_after_sliced_config', $return );
		
		// sliced Templates
		$dir = get_stylesheet_directory() . '/sliced';
		if( is_dir( $dir ) ) {
			$directory = array_diff(scandir($dir), array('..', '.'));
		}

		if( is_dir( $dir ) && ( count( $directory ) !== 0 ) ) {
			$return .= "\n" . '/////-- Sliced Template Overrides' . "\n\n";

			foreach( $directory as $file ) {
				$return .= 'Filename:                 ' . basename( $file ) . "\n";
			}

			$return  = apply_filters( 'sliced_sysinfo_after_sliced_templates', $return );
		}

		// Get plugins that have an update
		$updates = get_plugin_updates();

		// Must-use plugins
		// NOTE: MU plugins can't show updates!
		$muplugins = get_mu_plugins();
		if( count( $muplugins ) > 0 ) {
			$return .= "\n" . '/////-- Must-Use Plugins' . "\n\n";

			foreach( $muplugins as $plugin => $plugin_data ) {
				$return .= $plugin_data['Name'] . ': ' . $plugin_data['Version'] . "\n";
			}

			$return = apply_filters( 'sliced_sysinfo_after_wordpress_mu_plugins', $return );
		}

		// WordPress active plugins
		$return .= "\n" . '/////-- WordPress Active Plugins' . "\n\n";

		$plugins = get_plugins();
		$active_plugins = get_option( 'active_plugins', array() );

		foreach( $plugins as $plugin_path => $plugin ) {
			if( !in_array( $plugin_path, $active_plugins ) )
				continue;

			$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '';
			$return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
		}

		$return  = apply_filters( 'sliced_sysinfo_after_wordpress_plugins', $return );

		// WordPress inactive plugins
		$return .= "\n" . '/////-- WordPress Inactive Plugins' . "\n\n";

		foreach( $plugins as $plugin_path => $plugin ) {
			if( in_array( $plugin_path, $active_plugins ) )
				continue;

			$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '';
			$return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
		}

		$return  = apply_filters( 'sliced_sysinfo_after_wordpress_plugins_inactive', $return );

		if( is_multisite() ) {
			// WordPress Multisite active plugins
			$return .= "\n" . '/////-- Network Active Plugins' . "\n\n";

			$plugins = wp_get_active_network_plugins();
			$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

			foreach( $plugins as $plugin_path ) {
				$plugin_base = plugin_basename( $plugin_path );

				if( !array_key_exists( $plugin_base, $active_plugins ) )
					continue;

				$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '';
				$plugin  = get_plugin_data( $plugin_path );
				$return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
			}

			$return  = apply_filters( 'sliced_sysinfo_after_wordpress_ms_plugins', $return );
		}

		// Server configuration (really just versioning)
		$return .= "\n" . '/////-- Webserver Configuration' . "\n\n";
		$return .= 'PHP Version:              ' . PHP_VERSION . "\n";
		$return .= 'MySQL Version:            ' . $wpdb->db_version() . "\n";
		$return .= 'Webserver Info:           ' . $_SERVER['SERVER_SOFTWARE'] . "\n";

		$return  = apply_filters( 'sliced_sysinfo_after_webserver_config', $return );

		// PHP configs... now we're getting to the important stuff
		$return .= "\n" . '/////-- PHP Configuration' . "\n\n";
		$return .= 'Safe Mode:                ' . ( ini_get( 'safe_mode' ) ? 'Enabled' : 'Disabled' . "\n" );
		$return .= 'Memory Limit:             ' . ini_get( 'memory_limit' ) . "\n";
		$return .= 'Upload Max Size:          ' . ini_get( 'upload_max_filesize' ) . "\n";
		$return .= 'Post Max Size:            ' . ini_get( 'post_max_size' ) . "\n";
		$return .= 'Upload Max Filesize:      ' . ini_get( 'upload_max_filesize' ) . "\n";
		$return .= 'Time Limit:               ' . ini_get( 'max_execution_time' ) . "\n";
		$return .= 'Max Input Vars:           ' . ini_get( 'max_input_vars' ) . "\n";
		$return .= 'Display Errors:           ' . ( ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' ) . "\n";

		$return  = apply_filters( 'sliced_sysinfo_after_php_config', $return );

		// PHP extensions and such
		$return .= "\n" . '/////-- PHP Extensions' . "\n\n";
		$return .= 'cURL:                     ' . ( function_exists( 'curl_init' ) ? 'Supported' : 'Not Supported' ) . "\n";
		$return .= 'fsockopen:                ' . ( function_exists( 'fsockopen' ) ? 'Supported' : 'Not Supported' ) . "\n";
		$return .= 'SOAP Client:              ' . ( class_exists( 'SoapClient' ) ? 'Installed' : 'Not Installed' ) . "\n";
		$return .= 'Suhosin:                  ' . ( extension_loaded( 'suhosin' ) ? 'Installed' : 'Not Installed' ) . "\n";
		$return .= 'Mbstring:                  ' . ( extension_loaded( 'mbstring' ) ? 'Installed' : 'Not Installed' ) . "\n";

		$return  = apply_filters( 'sliced_sysinfo_after_php_ext', $return );

		// Session stuff
		$return .= "\n" . '/////-- Session Configuration' . "\n\n";
		$return .= 'Session:                  ' . ( isset( $_SESSION ) ? 'Enabled' : 'Disabled' ) . "\n";

		// The rest of this is only relevant is session is enabled
		if( isset( $_SESSION ) ) {
			$return .= 'Session Name:             ' . esc_html( ini_get( 'session.name' ) ) . "\n";
			$return .= 'Cookie Path:              ' . esc_html( ini_get( 'session.cookie_path' ) ) . "\n";
			$return .= 'Save Path:                ' . esc_html( ini_get( 'session.save_path' ) ) . "\n";
			$return .= 'Use Cookies:              ' . ( ini_get( 'session.use_cookies' ) ? 'On' : 'Off' ) . "\n";
			$return .= 'Use Only Cookies:         ' . ( ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off' ) . "\n";
		}

		$return  = apply_filters( 'sliced_sysinfo_after_session_config', $return );

		$return .= "\n" . '### End System Info ###';

		return $return;
	}

	/**
	 * Get user host
	 *
	 * Returns the webhost this site is using if possible
	 *
	 * @since 2.06
	 * @return mixed string $host if detected, false otherwise
	 */
	private function get_host() {
		$host = false;

		if( defined( 'WPE_APIKEY' ) ) {
			$host = 'WP Engine';
		} elseif( defined( 'PAGELYBIN' ) ) {
			$host = 'Pagely';
		} elseif( DB_HOST == 'localhost:/tmp/mysql5.sock' ) {
			$host = 'ICDSoft';
		} elseif( DB_HOST == 'mysqlv5' ) {
			$host = 'NetworkSolutions';
		} elseif( strpos( DB_HOST, 'ipagemysql.com' ) !== false ) {
			$host = 'iPage';
		} elseif( strpos( DB_HOST, 'ipowermysql.com' ) !== false ) {
			$host = 'IPower';
		} elseif( strpos( DB_HOST, '.gridserver.com' ) !== false ) {
			$host = 'MediaTemple Grid';
		} elseif( strpos( DB_HOST, '.pair.com' ) !== false ) {
			$host = 'pair Networks';
		} elseif( strpos( DB_HOST, '.stabletransit.com' ) !== false ) {
			$host = 'Rackspace Cloud';
		} elseif( strpos( DB_HOST, '.sysfix.eu' ) !== false ) {
			$host = 'SysFix.eu Power Hosting';
		} elseif( strpos( $_SERVER['SERVER_NAME'], 'Flywheel' ) !== false ) {
			$host = 'Flywheel';
		} else {
			// Adding a general fallback for data gathering
			$host = 'DBH: ' . DB_HOST . ', SRV: ' . $_SERVER['SERVER_NAME'];
		}

		return $host;
	}
	/**
	 * Generates a System Info download file
	 *
	 * @since       2.0
	 * @return      void
	 */
	public function sliced_tools_system_info_download() {

		if( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if( ! isset( $_POST['sliced-sysinfo'] ) )
			return;

		nocache_headers();

		header( 'Content-Type: text/plain' );
		header( 'Content-Disposition: attachment; filename="sliced-invoices-system-info.txt"' );

		echo wp_strip_all_tags( $_POST['sliced-sysinfo'] );
		die();
	}


}
