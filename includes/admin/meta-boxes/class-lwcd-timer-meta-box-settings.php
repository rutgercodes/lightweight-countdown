<?php
/**
 * Shipment Data
 *
 * Displays the product data box, tabbed, with several panels covering price, stock etc.
 *
 * @package  Lightweight Countdowns\Admin\Meta Boxes
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * LWCD_Timer_Meta_Box_Settings Class.
 */
class LWCD_Timer_Meta_Box_Settings {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post Post object.
	 */
	public static function output( $post ) {
		global $thepostid, $post;

		$thepostid = $post->ID;
		wp_nonce_field( 'lwcd_save_data', 'lwcd_meta_nonce' );

		include __DIR__ . '/views/html-timer-settings.php';
	}



	/**
	 * Save meta box data.
	 *
	 * @param int     $post_id WP post id.
	 * @param WP_Post $post Post object.
	 */
	public static function save( $post_id, $post ) {

		if ( 
			! current_user_can( 'edit_post', $post_id )
		) {
			return;
		}

		if ( 
			array_key_exists( 'timer-deadline', $_POST ) &&
			DateTime::createFromFormat('Y-m-d H:i', $_POST['timer-deadline'])->format('Y-m-d H:i') === $_POST['timer-deadline']
		) {
			update_post_meta(
				$post_id,
				'_lwcd_timer_deadline',
				$_POST['timer-deadline']
			);
		}

		if ( 
			array_key_exists( 'timer-format', $_POST ) &&
			in_array( $_POST['timer-format'], array_keys( lwcd_get_formats() ) )
		) {
			update_post_meta(
				$post_id,
				'_lwcd_timer_format',
				$_POST['timer-format']
			);
		}


		if ( 
			array_key_exists( 'timer-units', $_POST ) &&
			is_array( $_POST['timer-units'] )
		) {

			$set_units = [];
			foreach( $_POST['timer-units'] as $key => $unit ) {
				if( in_array( $key, array_keys( lwcd_get_units() ) ) ) {
					$set_units[$key] = $unit;
				}
 			}

			update_post_meta(
				$post_id,
				'_lwcd_timer_units',
				$set_units
			);

		} else {
			update_post_meta(
				$post_id,
				'_lwcd_timer_units',
				array()
			);
		}

	}

}
