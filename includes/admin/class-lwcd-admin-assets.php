<?php
/**
 * Load assets
 *
 * @package Lightweight Countdowns\Admin
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'LWCD_Admin_Assets', false ) ) :

	/**
	 * LWCD_Admin_Assets Class.
	 */
	class LWCD_Admin_Assets {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		}

		/**
		 * Enqueue styles.
		 */
		public function admin_styles() {

			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';

			// Register admin styles.
			wp_register_style( 'lwcd-admin-styles', Lightweight_Countdown::$plugin_url . 'assets/css/admin.css', array(), Lightweight_Countdown::$plugin_version );
			wp_register_style( 'lwcd-flatpickr-styles-default', 'https://npmcdn.com/flatpickr/dist/flatpickr.css', array(), Lightweight_Countdown::$plugin_version );

			// Admin styles for LWCD pages only.
			if ( in_array( $screen_id, lwcd_get_screen_ids() ) ) {
				wp_enqueue_style( 'lwcd-admin-styles' );
				wp_enqueue_style( 'lwcd-flatpickr-styles-default' );
			}
		}


		/**
		 * Enqueue scripts.
		 */
		public function admin_scripts() {
			global $wp_query, $post;

			$screen       = get_current_screen();
			$screen_id    = $screen ? $screen->id : '';
			$suffix       = '';
			// $suffix       = Constants::is_true( 'SCRIPT_DEBUG' ) ? '' : '.min';
			$version      = Lightweight_Countdown::$plugin_version;

			// Register admin scripts.
			wp_register_script( 'lwcd-flatpickr-script', 'https://npmcdn.com/flatpickr/dist/flatpickr.min.js', array(), Lightweight_Countdown::$plugin_version );
			wp_register_script( 'lwcd-admin-timer-meta-boxes', Lightweight_Countdown::$plugin_url . '/assets/js/admin/meta-boxes-timer-settings' . $suffix . '.js', array( 'lwcd-flatpickr-script' ), $version );


			// Admin scripts for timer pages only.
			if ( in_array( $screen_id, array( 'lwcd-timer', 'edit-lwcd-timer' ) ) ) {
				
				wp_enqueue_script( 'lwcd-flatpickr-script' );
				wp_enqueue_script( 'lwcd-admin-timer-meta-boxes' );
				wp_enqueue_script( 'lwcd-timers' );

				wp_add_inline_script( 'lwcd-admin-timer-meta-boxes', 'const LWCD_TIMER_META_BOXES = {
					ajax_url: "' . admin_url( 'admin-ajax.php' ) . '",
					units: ' . json_encode( lwcd_get_units() ) . ',
					date_format: "' . get_option('date_format') . '",
					time_format: "' . lwcd_time_format_to_js( get_option('time_format') ) . '"
				}');
			}

		}

	}

endif;

return new LWCD_Admin_Assets();
