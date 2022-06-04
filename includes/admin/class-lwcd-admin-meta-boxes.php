<?php
/**
 * Lightweight Coutndown Meta Boxes
 *
 * Sets up the write panels used by products and orders (custom post types).
 *
 * @package Lightweight Coutndown\Admin\Meta Boxes
 */

defined( 'ABSPATH' ) || exit;

/**
 * LWCD_Admin_Meta_Boxes.
 */
class LWCD_Admin_Meta_Boxes {

	/**
	 * Is meta boxes saved once?
	 *
	 * @var boolean
	 */
	private static $saved_meta_boxes = false;

	/**
	 * Meta box error messages.
	 *
	 * @var array
	 */
	public static $meta_box_errors = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );

		// Save Shipment Meta Boxes.
		add_action( 'lwcd_process_timer_meta', 'LWCD_Timer_Meta_Box_Settings::save', 10, 2 );
	}

	/**
	 * Add LWCD Meta boxes.
	 */
	public function add_meta_boxes() {
		global $post;

		// Timers.
		add_meta_box( 'lwcd-timer-settings', __( 'Settings', 'lightweight-countdown' ), 'LWCD_Timer_Meta_Box_Settings::output', 'lwcd-timer', 'normal', 'high' );
		add_meta_box( 'lwcd-timer-preview', __( 'Preview', 'lightweight-countdown' ), 'LWCD_Timer_Meta_Box_Preview::output', 'lwcd-timer', 'side', 'low' );

		// Edit only screens
		if (get_post_status( $post->ID ) != 'auto-draft') {
			add_meta_box( 'lwcd-timer-shortcodes', __( 'Shortcodes', 'lightweight-countdown' ), 'LWCD_Timer_Meta_Box_Shortcodes::output', 'lwcd-timer', 'normal', 'low' );
		}

	}

	/**
	 * Check if we're saving, then trigger an action based on the post type.
	 *
	 * @param  int    $post_id Post ID.
	 * @param  object $post Post object.
	 */
	public function save_meta_boxes( $post_id, $post ) {
		$post_id = absint( $post_id );

		// $post_id and $post are required
		if ( empty( $post_id ) || empty( $post ) || self::$saved_meta_boxes ) {
			return;
		}

		// Dont' save meta boxes for revisions or autosaves.
		if ( is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		// Check the nonce.
		if ( empty( $_POST['lwcd_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['lwcd_meta_nonce'] ), 'lwcd_save_data' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
		if ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) !== $post_id ) {
			return;
		}

		// Check user has permission to edit.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check the post type.
		if ( in_array( $post->post_type, array( 'lwcd-timer' ), true ) ) {
			$short_post_type = str_replace( 'lwcd-', '',  $post->post_type );
			do_action( 'lwcd_process_' . $short_post_type . '_meta', $post_id, $post );
		}
	}

	/**
	 * Remove bloat.
	 */
	public function remove_meta_boxes() {
		// TODO: replace submitdiv with custom meta box
		// remove_meta_box( 'submitdiv', 'lwcd-timer', 'side' );
		remove_meta_box( 'pageparentdiv', 'lwcd-timer', 'side' );
	}
}

new LWCD_Admin_Meta_Boxes();
