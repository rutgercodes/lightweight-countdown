<?php
/**
 * Lightweight Countdowns Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @package Lightweight Countdowns\Functions
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the units with optinal filter.
 *
 * @param array $filter Array of unit to filter by.
 * @return array
 */
function lwcd_get_units( $filter = array() ) {

	$units = array(
		'years' => array(
			'id' => 'years',
			'label' => __( 'Years', 'lightweight-countdown' ),
			'duration' => 60 * 60 * 24 * 365,
			'singular' => __( '%s year', 'lightweight-countdown' ),
			'plural'   =>  __( '%s years', 'lightweight-countdown' )
		),
		'weeks' => array(
			'id' => 'weeks',
			'label' => __( 'Weeks', 'lightweight-countdown' ),
			'duration' => 60 * 60 * 24 * 7,
			'singular' =>  __( '%s week', 'lightweight-countdown' ),
			'plural'   =>  __( '%s weeks', 'lightweight-countdown' )
		),
		'days' => array(
			'id' => 'days',
			'label' => __( 'Days', 'lightweight-countdown' ),
			'duration' => 60 * 60 * 24,
			'plural'   =>  __( '%s days', 'lightweight-countdown' ),
			'singular' =>  __( '%s day', 'lightweight-countdown' )
		),
		'hours' => array(
			'id' => 'hours',
			'label' => __( 'Hours', 'lightweight-countdown' ),
			'duration' => 60 * 60,
			'singular' =>  __( '%s hour', 'lightweight-countdown' ),
			'plural'   =>  __( '%s hours', 'lightweight-countdown' )
		),
		'minutes' => array(
			'id' => 'minutes',
			'label' => __( 'Minutes', 'lightweight-countdown' ),
			'duration' => 60,
			'singular' =>  __( '%s minute', 'lightweight-countdown' ),
			'plural'   =>  __( '%s minutes', 'lightweight-countdown' )
		),
		'seconds' => array(
			'id' => 'seconds',
			'label' => __( 'Seconds', 'lightweight-countdown' ),
			'duration' => 1,
			'singular' =>  __( '%s second', 'lightweight-countdown' ),
			'plural'   =>  __( '%s seconds', 'lightweight-countdown' )
		)
	);

	if( !$filter ) return $units;

	$filtered_units = array();	
	foreach( $units as $key => $unit ) {
		if( in_array( $key,  $filter ) ) {
			$filtered_units[$key] = $unit;
		}
	}

	return apply_filters( 'lwcd_get_units', $filtered_units, $filter );

}

/**
 * Get the available formats.
 *
 * @return array
 */
function lwcd_get_formats() {

	$formats = array(
		'full' => array(
			'default' => true,
			'id' => 'full',
			'label' => __( 'Full', 'lightweight-countdown' )
		),
		'single' => array(
			'id' => 'single',
			'label' => __( 'Biggest value only', 'lightweight-countdown' )
		)
	);

	return apply_filters( 'lwcd_get_formats', $formats );

}


/**
 * Get the default format.
 *
 * @return array
 */
function lwcd_get_default_format() {
	
	foreach( lwcd_get_formats() as $format ) {
		if( $format['deafult'] ) {
			return apply_filters( 'lwcd_get_default_format', $format );
		}
	}

	return false;

}


/**
 * Get timestamp from string using WP locale
 *
 * @param string $time_string Time string.
 * @return DateTime
 */
function lwcd_get_localized_datetime_from_string( $time_string ) {

	return new DateTime( $time_string, new DateTimeZone( wp_timezone_string() ) );

}

/**
 * Adjust time format from php to js
 *
 * @param string $format Time format string in php format.
 * @return string
 */
function lwcd_time_format_to_js( $format ) {

	// php format used: https://wordpress.org/support/article/formatting-date-and-time/
	// js format used: https://flatpickr.js.org/formatting/
	
	$replacements = array(
		'a' => 'K',
		'A' => 'K',
		'G' => 'H',
		'g' => 'h',
		'h' => 'G',
		's' => 'S',
	);

	return str_replace( array_keys($replacements), array_values($replacements), $format );

}

/**
 * Adjust date format from php to js
 *
 * @param string $format Time format string in php format.
 * @return string
 */
function lwcd_date_format_to_js( $format ) {

	// php format used: https://wordpress.org/support/article/formatting-date-and-time/
	// js format used: https://flatpickr.js.org/formatting/
	
	$replacements = array(
		'jS' => 'J',
		'S' => ''
	);

	return str_replace( array_keys($replacements), array_values($replacements), $format );

}
