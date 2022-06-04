jQuery( function ( $ ) {
	
	var preview_interval;

	/**
	 * Settings Panel
	 */
	var lwcd_meta_boxes_settings = {
		init: function() {
			const { date_format = 'j F, Y', time_format = 'H:i' } = LWCD_TIMER_META_BOXES;

			$( '#timer-deadline' ).flatpickr({
				enableTime: true,
				time_24hr: !time_format.includes('K'),
				altInput: true,
				altFormat: date_format + " " + time_format,
				dateFormat: "Y-m-d H:i",
				minDate: "today",
			});

			lwcd_meta_boxes_settings.maybeUpdatePreview();
			$('.lwcd_submit_timer input').change( lwcd_meta_boxes_settings.maybeUpdatePreview );
		},

		maybeUpdatePreview: function() {

			const { ajax_url, units } = LWCD_TIMER_META_BOXES;

			if( !ajax_url || !units ) return;

			const previewTextId = 'lwcd-timer-preview-text';
			const $previewText = $('#'+previewTextId);
			const deadline = $('#timer-deadline').val();
			const format = $("input[name='timer-format']:checked").val();
			
			let selected_units = {};
			$('input[id^=timer-units]:checked').each(function() {
				const unitId = $(this).attr('name').match(/\[(.*)\]/).pop();
				selected_units[ unitId ] = ( units[ unitId ] );
			});

			if( !deadline || Object.keys(selected_units).length === 0 ) {
				if( preview_interval ) clearInterval( preview_interval );
				return $previewText.text('Fill in all required fields to see a preview');
			}
			
			var data = {
				time_string: deadline,
				action     : 'lwcd_get_localized_timestamp'
			};

			$.ajax({
				url    : ajax_url,
				data   : data,
				type   : 'POST',
				success: function( response ) {
					if ( response && response.success ) {

						const timer = {
							id: previewTextId,
							deadline: response.data.timestamp,
							format: format,
							units: selected_units
						}
						clearInterval( preview_interval );
						preview_interval = lwcd_timers.initTimer(timer);
						
					}
				}
			});

		}

	}

	lwcd_meta_boxes_settings.init();

});