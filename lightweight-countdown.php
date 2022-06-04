<?php
/**
 * Plugin Name: Lightweight Countdown Timers
 * Description: A lightweight plugin to add countdown timers anywhere you like.
 * Author: Rutger van Wijngaarden
 * Version: 1.0.0
 * Text Domain: lightweight-countdown
 *
 * Copyright: (c) 2022 Rutger van Wijngaarden (wordpress@rutger.codes)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @author    Rutger van Wijngaarden
 * @copyright Copyright (c) 2022, Rutger van Wijngaarden
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! defined( 'LWCD_PLUGIN_FILE' ) ) {
	define( 'LWCD_PLUGIN_FILE', __FILE__ );
}

/**
 * Base class
 */
if ( ! class_exists( 'Lightweight_Countdown', false ) ) {
	include_once dirname( LWCD_PLUGIN_FILE ) . '/includes/class-lightweight-countdown.php';
}

/**
 * Returns the main instance of the plugin to prevent the need to use globals
 */
function LWCD() {
	return Lightweight_Countdown::instance();
}

/**
 * Global for backwards compatibility
 */
$GLOBALS['lwcd'] = LWCD();