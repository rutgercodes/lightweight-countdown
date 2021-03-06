<?php
/**
 * Lightweight Countdowns LWCD_AJAX. AJAX Event Handlers.
 *
 * @class   LWCD_AJAX
 * @package Lightweight Countdowns\Classes
 */

defined( 'ABSPATH' ) || exit;

/**
 * LWCD_AJAX class.
 */
class LWCD_AJAX {

	/**
	 * Hook in ajax handlers.
	 */
	public static function init() {
		self::add_ajax_events();
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 */
	public static function add_ajax_events() {
		
		$ajax_events = array(
			'get_localized_timestamp',
		);

		foreach ( $ajax_events as $ajax_event ) {
			add_action( 'wp_ajax_lwcd_' . $ajax_event, array( __CLASS__, $ajax_event ) );
		}
	}	

	/**
	 * AJAX update shipment status.
	 */
	public static function get_localized_timestamp() {

		if ( 
			! isset( $_POST['time_string'] ) 
		) {
			wp_die( -1 );
		}
		
		$date_time = lwcd_get_localized_datetime_from_string( $_POST['time_string'] );
		if ( !$date_time ) {
			wp_die( -1 );
		}

		wp_send_json_success( array(
			'timestamp' => $date_time->getTimestamp()
		));

	}

}

LWCD_AJAX::init();
