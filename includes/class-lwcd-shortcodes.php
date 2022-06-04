<?php
/**
 * Shortcodes
 *
 * @package Lightweight Countdowns\Classes
 * @version 3.2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lightweight Countdowns Shortcodes class.
 */
class LWCD_Shortcodes {

	/**
	 * Init shortcodes.
	 */
	public static function init() {
		$shortcodes = array(
			'timer' => __CLASS__ . '::timer',
			'before_timer' => __CLASS__ . '::before_timer',
			'after_timer' => __CLASS__ . '::after_timer',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "lwcd_{$shortcode}_shortcode_tag", "lwcd_" . $shortcode ), $function );
		}

		self::register_scripts();
	}


	/**
	 * Register scripts needed by the shortcodes
	 *
	 */
	protected static function register_scripts() {

		$version = Lightweight_Countdown::$plugin_version;
		wp_register_script( 'lwcd-timers', Lightweight_Countdown::$plugin_url . '/assets/js/timers.js', array(), $version );
		
	}

	/**
	 * Show timer frontend shortcode.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function timer( $atts ) {

		shortcode_atts( array(
			'id' => 0,
		), $atts, 'lwcd_timer');
		
		if( ! $atts['id'] ) {
			return null;
		}

		$deadline = get_post_meta( $atts['id'], '_lwcd_timer_deadline', true );
		$format = get_post_meta( $atts['id'], '_lwcd_timer_format', true );
		$display_units = get_post_meta( $atts['id'], '_lwcd_timer_units', true );
		$timer_id = 'lwcd-timer-' . $atts['id'];

		$deadline_date_time = lwcd_get_localized_datetime_from_string( $deadline );
		$now = new DateTime();
		$time_left =  $deadline_date_time->getTimestamp() - $now->getTimestamp();
		
		$output = '';
		$units = lwcd_get_units( array_keys( $display_units ) );

		if( $time_left > 0 && ( $units['seconds'] || $units['minutes'] ) ) {
			wp_enqueue_script( 'lwcd-timers' );

			// Add timer data to global js var (create var if it doesn't exist)
			wp_add_inline_script( 'lwcd-timers', 'var timer = ' . json_encode( array(
				'id' => $timer_id,
				'deadline' => $deadline_date_time->getTimestamp(),
				'format' => $format,
				'units' => $units, // pass only values to make it an array in js
				'locale' => explode( "_", get_locale() )[0]
			) ) . ';
			if(typeof LWCD_TIMERS !== "undefined") {
				LWCD_TIMERS.push( timer );
			} else {
				var LWCD_TIMERS = [ timer ];
			}', 'before' );
		}

		foreach( $units as $key => $unit ) {

			// Calculate the number of time units left 
			$units_left = 0;
			if( $time_left > $unit['duration'] ) {
				if( $format !== 'single' || ( !$output && $format === 'single' ) ) {
					$units_left = floor( $time_left /  $unit['duration'] );
					$time_left -= ($units_left *  $unit['duration']);
				}
			}

			// Check if need output in current format
			if( $units_left > 0 || ( $time_left <= 0 && $key === array_key_last( $units ) ) ) {
				$output .= ' ' . sprintf( ngettext( $unit['singular'], $unit['plural'], $units_left ), $units_left);
			}

		}

		return '<span class="lwcd-timer" id="' . $timer_id . '">' . esc_html( $output ) . '</span>';
	}


	/**
	 * Shortocde to show content only before deadline.
	 *
	 * @param array $atts Attributes.
	 * @param array $content Content.
	 * @return string
	 */
	public static function before_timer( $atts, $content ) {

		if( !$content ) return;

		shortcode_atts( array(
			'id' => 0,
		), $atts, 'lwcd_berfore_timer');

		if( !$atts['id'] ) return;

		$time_left = self::get_time_left( $atts['id'] );
		

		return $time_left > 0 ? $content : '';

	}

	/**
	 * Shortocde to show content only before deadline.
	 *
	 * @param array $atts Attributes.
	 * @param array $content Content.
	 * @return string
	 */
	public static function after_timer( $atts, $content ) {

		if( !$content ) return;

		shortcode_atts( array(
			'id' => 0,
		), $atts, 'lwcd_berfore_timer');

		if( !$atts['id'] ) return;

		$time_left = self::get_time_left( $atts['id'] );
		

		return $time_left <= 0 ? $content : '';

	}	
	
	/**
	* Get time left till deadline from post id
	*
	* @param array $id Post id.
	* @return int
	*/
	private static function get_time_left( $id ) {

		if( !$id ) return false;

		$deadline = get_post_meta( $id, '_lwcd_timer_deadline', true );
		if( !$deadline ) return;

		$deadline_date_time = new DateTime( $deadline, new DateTimeZone( wp_timezone_string() ) );
		$now = new DateTime();
		return $deadline_date_time->getTimestamp() - $now->getTimestamp();

	}



}
