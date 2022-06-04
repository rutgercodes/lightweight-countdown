<?php
/**
 * Product data meta box.
 *
 * @package Lightweight Countdowns\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$units = get_post_meta( $thepostid, '_lwcd_timer_units', true ) ?? array();
?>

<div class="panel-wrap lwcd_submit_timer">

	<p>
		<fieldset>
			<label for="timer-deadline"><?php esc_html_e( 'Deadline', 'lightweight-countdown' ); ?></label>
			<input type="text" class="flatpickr" name="timer-deadline" id="timer-deadline" placeholder="<?php esc_attr_e( 'Select a date and time', 'lightweight-countdown' ); ?>" value="<?php echo esc_attr( get_post_meta( $thepostid, '_lwcd_timer_deadline', true ) ) ?>" ></input>
		</fieldset>
	</p>


	<p>
		<fieldset>
			<label for="format"><?php esc_html_e( 'Format', 'lightweight-countdown' ); ?></label>
			<?php foreach( lwcd_get_formats() as $format ) { ?>
				<?php $current_format = get_post_meta( $thepostid, '_lwcd_timer_format', true ) ?? lwcd_get_default_format() ?>
				<input type="radio" name="timer-format" id="timer-format-<?php echo esc_attr( $format['id'] ) ?>" value="<?php echo esc_attr( $format['id'] ); ?>" <?php echo $current_format == $format['id'] ? 'checked' : '' ?>></input><label for="timer-format-<?php echo esc_attr( $format['id'] ) ?>"><?php echo esc_html( $format['label'] ) ?></label>
			<?php } // end foreach formats ?>
		</fieldset>
	</p>

	<p>
		<fieldset>
			<label for="units"><?php esc_html_e( 'Show time in', 'lightweight-countdown' ); ?></label>
			<?php foreach( lwcd_get_units() as $unit_key => $unit ) { ?>
				<input type="checkbox" name="timer-units[<?php echo esc_attr( $unit_key ) ?>]" id="timer-units-<?php echo esc_attr( $unit_key ) ?>" value="show" <?php echo array_key_exists( $unit_key, $units) ? 'checked' : '' ?>></input><label for="timer-units-<?php echo esc_attr( $unit_key ) ?>"><?php echo esc_html( $unit['label'] ) ?></label>
			<?php } // end foreach ?>
		</fieldset>
	</p>

</div>