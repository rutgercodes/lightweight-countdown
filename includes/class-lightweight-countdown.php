<?php
/**
 * Lightweight Countdowns setup
 *
 * @package Lightweight Countdowns
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Main Lightweight Countdowns class.
 *
 * @class Lightweight_Countdown
 */
final class Lightweight_Countdown {
	
	/**
	 * The single instance of the class
	 * 
	 * @var Lightweight_Countdown
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * Product factory instance.
	 *
	 * @var Lightweight_Countdown_Shipment_Factory
	 */
	public $product_factory = null;

	/**
	 * Default properties
	 */
	public static $plugin_version;
	public static $plugin_tag;
	public static $plugin_prefix;
	public static $plugin_url;
	public static $plugin_path;
	public static $plugin_basefile;
	public static $plugin_basefile_path;
	public static $plugin_text_domain;
	
	/**
	 * Sub class instances
	 */
	public $settings;
	public $orderpanel;
	public $email;
	public $notifier;



	/**
	 * Main instance.
	 *
	 * Ensures only one instance is loaded or can be loaded.
	 *
	 * @since 2.1
	 * @static
	 * @see LWCD()
	 * @return Lightweight_Countdown - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	/**
	 * Constructor
	 */
	public function __construct() {

		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		// Send out the load action
		do_action( 'lwcd_load');
	}

	/**
	 * Hook into actions and filters
	 */
	public function init_hooks() {
		register_activation_hook( LWCD_PLUGIN_FILE, array( 'Lightweight_Countdown_Install', 'install' ) );
		add_action( 'init', array( 'LWCD_Shortcodes', 'init' ) );
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Define LWCD Constants
	 */
	private function define_constants() {
		/**
		 * Local constants
		 */
		self::$plugin_version = '1.0.0';
		self::$plugin_tag = 'lwcd';
		self::$plugin_prefix = self::$plugin_tag . '_';
		self::$plugin_basefile_path = dirname( LWCD_PLUGIN_FILE ) . '/';
		self::$plugin_basefile = plugin_basename( self::$plugin_basefile_path );
		self::$plugin_url =  plugin_dir_url( LWCD_PLUGIN_FILE );
		self::$plugin_path = trailingslashit( dirname( self::$plugin_basefile_path ) );	
		self::$plugin_text_domain = trim( self::$plugin_basefile );
	}

	/**
	 * Include the main plugin classes and functions
	 */
	public function includes() {

		/**
		 * Meta boxes
		 */
		include_once self::$plugin_basefile_path . 'includes/admin/class-lwcd-admin-meta-boxes.php';
		include_once self::$plugin_basefile_path . 'includes/admin/meta-boxes/class-lwcd-timer-meta-box-shortcodes.php';
		include_once self::$plugin_basefile_path . 'includes/admin/meta-boxes/class-lwcd-timer-meta-box-settings.php';
		include_once self::$plugin_basefile_path . 'includes/admin/meta-boxes/class-lwcd-timer-meta-box-preview.php';
		

		/**
		 * Core classes
		 */
		include_once self::$plugin_basefile_path . 'includes/lwcd-core-functions.php';
		include_once self::$plugin_basefile_path . 'includes/class-lwcd-install.php';
		include_once self::$plugin_basefile_path . 'includes/class-lwcd-post-types.php';
		include_once self::$plugin_basefile_path . 'includes/class-lwcd-ajax.php';
		include_once self::$plugin_basefile_path . 'includes/class-lwcd-shortcodes.php';

		/**
		 * Admin classes
		 */
		if ( $this->is_request( 'admin' ) ) {
			include_once self::$plugin_basefile_path . 'includes/admin/class-lwcd-admin.php';
		}

	}


	/**
	 * Load the main plugin classes and functions
	 */
	public function init() {
		
		// Send out the before init action
		do_action( self::$plugin_prefix . 'before_init' );

		// TODO: Set up localisation.
		$this->load_plugin_textdomain();
		
		// Send out the init action
		do_action( self::$plugin_prefix . 'init' );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/lightweight-countdown/lightweight-countdown-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/lightweight-countdown-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = determine_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'lightweight-countdown' );

		unload_textdomain( 'lightweight-countdown' );
		load_textdomain( 'lightweight-countdown', WP_LANG_DIR . '/lightweight-countdown/lightweight-countdown-' . $locale . '.mo' );
		load_plugin_textdomain( 'lightweight-countdown', false, plugin_basename( dirname( LWCD_PLUGIN_FILE ) ) . '/languages' );
	}


	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! $this->is_rest_api_request();
		}
	}

}