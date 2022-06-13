<?php
/**
 * Product data meta box.
 *
 * @package Lightweight Countdowns\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="panel-wrap lwcd_submit_timer">

	<p><?php esc_html_e( 'Use the shortcodes below to display the timer or conditional content based on the timers deadline.', 'lightweight-countdown' ); ?></p>

	<h4><?php esc_html_e( 'Countdown timer text', 'lightweight-countdown' ); ?></h4>
	<code>[lwcd_timer id="<?php echo esc_attr( $thepostid ); ?>"]</code>

	<h4><?php esc_html_e( 'Conditional content before deadline', 'lightweight-countdown' ); ?></h4>
	<code>[lwcd_before_timer id="<?php echo esc_attr( $thepostid );  ?>"] <?php esc_html_e( 'This only shows before the deadline', 'lightweight-countdown' ); ?> [/lwcd-timer-before]</code>

	<h4><?php esc_html_e( 'Conditional content after deadline', 'lightweight-countdown' ); ?></h4>
	<code>[lwcd_after_timer id="<?php echo esc_attr( $thepostid ); ?>"] <?php esc_html_e( 'This only shows after the deadline', 'lightweight-countdown' ); ?> [/lwcd-timer-before]</code>


</div>