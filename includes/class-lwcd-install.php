<?php
/**
 * Installation related functions and actions.
 *
 * @package Lightweight_Countdown\Classes
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lightweight_Countdown_Install Class.
 */
class Lightweight_Countdown_Install {

	/**
	 * Install LWCD.
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'lwcd_installing' ) ) {
			return;
		}

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'lwcd_installing', 'yes', MINUTE_IN_SECONDS * 10 );

		// Do the actual install action
		self::setup_environment();

		delete_transient( 'lwcd_installing' );

		do_action( Lightweight_Countdown::$plugin_prefix.'installed' );
	}

	/**
	 * Setup LWCD environment - post types.
	 *
	 * @since 1.0.0
	 */
	private static function setup_environment() {
		Lightweight_Countdown_Post_Types::register_post_types();
	}

}