<?php
/**
 * Post Types
 *
 * Registers post types and taxonomies.
 *
 * @package Lightweight Countdowns\Classes\Timers
 * @version 2.5.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Post types Class.
 */
class Lightweight_Countdown_Post_Types {

	/**
	 * Hook in methods.
	 */
	public static function init() {

		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
		add_filter( 'gutenberg_can_edit_post_type', array( __CLASS__, 'gutenberg_can_edit_post_type' ), 10, 2 );
		add_filter( 'use_block_editor_for_post_type', array( __CLASS__, 'gutenberg_can_edit_post_type' ), 10, 2 );
	}

	/**
	 * Register core post types.
	 */
	public static function register_post_types() {

		if ( ! is_blog_installed() || post_type_exists( 'lwcd-timer' ) ) {
			return;
		}

		do_action( 'lwcd_register_post_type' );

		$supports   = array( 'title' );

		register_post_type(
			'lwcd-timer',
			apply_filters(
				'lwcd_register_post_type_timer',
				array(
					'labels'              => array(
						'name'                  => __( 'Timers', 'lightweight-countdown' ),
						'singular_name'         => __( 'Timer', 'lightweight-countdown' ),
						'all_items'             => __( 'All timers', 'lightweight-countdown' ),
						'menu_name'             => _x( 'Timers', 'Admin menu name', 'lightweight-countdown' ),
						'add_new'               => __( 'Add new', 'lightweight-countdown' ),
						'add_new_item'          => __( 'Add new timer', 'lightweight-countdown' ),
						'edit'                  => __( 'Edit', 'lightweight-countdown' ),
						'edit_item'             => __( 'Edit timer', 'lightweight-countdown' ),
						'new_item'              => __( 'New timer', 'lightweight-countdown' ),
						'view_item'             => __( 'View timer', 'lightweight-countdown' ),
						'view_items'            => __( 'View timers', 'lightweight-countdown' ),
						'search_items'          => __( 'Search timers', 'lightweight-countdown' ),
						'not_found'             => __( 'No timers found', 'lightweight-countdown' ),
						'not_found_in_trash'    => __( 'No timers found in trash', 'lightweight-countdown' ),
						'parent'                => __( 'Parent timer', 'lightweight-countdown' ),
						'featured_image'        => __( 'Timer image', 'lightweight-countdown' ),
						'set_featured_image'    => __( 'Set timer image', 'lightweight-countdown' ),
						'remove_featured_image' => __( 'Remove timer image', 'lightweight-countdown' ),
						'use_featured_image'    => __( 'Use as timer image', 'lightweight-countdown' ),
						'insert_into_item'      => __( 'Insert into timer', 'lightweight-countdown' ),
						'uploaded_to_this_item' => __( 'Uploaded to this timer', 'lightweight-countdown' ),
						'filter_items_list'     => __( 'Filter timers', 'lightweight-countdown' ),
						'items_list_navigation' => __( 'Timers navigation', 'lightweight-countdown' ),
						'items_list'            => __( 'Timers list', 'lightweight-countdown' ),
						'item_link'             => __( 'Timer link', 'lightweight-countdown' ),
						'item_link_description' => __( 'A link to a timer.', 'lightweight-countdown' ),
					),
					'description'         => __( 'This is where you can see the timers.', 'lightweight-countdown' ),
					'public'              => true,
					'show_ui'             => true,
					'menu_icon'           => 'dashicons-hourglass',
					'capability_type'     => 'post',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
					'rewrite'             => true,
					'query_var'           => true,
					'supports'            => $supports,
					'has_archive'         => false,
					'show_in_nav_menus'   => true,
					'show_in_rest'        => true,
				)
			)
		);

		do_action( 'after_register_post_type' );
	}

	/**
	 * Disable Gutenberg for timers.
	 *
	 * @param bool   $can_edit Whether the post type can be edited or not.
	 * @param string $post_type The post type being checked.
	 * @return bool
	 */
	public static function gutenberg_can_edit_post_type( $can_edit, $post_type ) {
		return 'lwcd-timer' === $post_type ? false : $can_edit;
	}
}

Lightweight_Countdown_Post_Types::init();