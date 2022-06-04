<?php
/**
 * Lightweight Countdown Admin Functions
 *
 * @package  Lightweight_Countdown\Admin\Functions
 * @version  2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get all Lightweight_Countdown screen ids.
 *
 * @return array
 */
function lwcd_get_screen_ids() {

	$screen_ids   = array(
		'edit-lwcd-timer',
		'lwcd-timer'
	);

	return apply_filters( 'lwcd_screen_ids', $screen_ids );
}