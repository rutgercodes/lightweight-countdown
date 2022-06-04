<?php
/**
 * Lightweight Countdowns Admin
 *
 * @class    LWCD_Admin
 * @package  Lightweight Countdowns\Admin
 * @version  2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * LWCD_Admin class.
 */
class LWCD_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) ); 
	}



	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		include_once __DIR__ . '/lwcd-admin-functions.php';
		include_once __DIR__ . '/class-lwcd-admin-assets.php';
	}

}

return new LWCD_Admin();
