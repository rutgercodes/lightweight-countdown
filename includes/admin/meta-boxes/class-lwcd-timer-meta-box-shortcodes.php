<?php
/**
 * Shipment Data
 *
 * Displays the product data box, tabbed, with several panels covering price, stock etc.
 *
 * @package  Lightweight Countdowns\Admin\Meta Boxes
 * @version  3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * LWCD_Timer_Meta_Box_Shortcodes Class.
 */
class LWCD_Timer_Meta_Box_Shortcodes {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post Post object.
	 */
	public static function output( $post ) {
		global $thepostid, $post;

		$thepostid = $post->ID;

		include __DIR__ . '/views/html-timer-shortcodes.php';
	}

}
