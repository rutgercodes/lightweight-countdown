
/**
 * Timers display
 */
var lwcd_timers = {

	init: function() {
		// Inititate timers for every timer send in the LWCD_TIMERS object
		if (
			typeof LWCD_TIMERS !== 'undefined' &&
			Array.isArray( LWCD_TIMERS )
		) {
			LWCD_TIMERS.forEach( this.initTimer )
		}
	},
	initTimer: function(timer) {
		// Set the text and set an interval based on the smallest visible time unit
		lwcd_timers.setTimerText( timer.id, timer );

		const now = new Date().getTime()
		if( timer.deadline - Math.round(now/1000) <= 0 ) return false;

		return setInterval( () => {
			lwcd_timers.setTimerText( timer.id, timer );
		}, timer.units.seconds ? 1000 : ( timer.units.minutes ? 3000 : 15000 ) )
	},
	setTimerText: function( id, timer ) {
		// Get the text and set as html
		const newText = lwcd_timers.getTimerText(timer) 
		document.getElementById( id ).innerHTML = newText
	},
	getTimerText: function(timer) {

		// Calcualte time left
		const now = new Date().getTime()
		var timeLeft = timer.deadline - Math.round(now/1000);

		let unitsArray = Object.values( timer.units );

		if( timeLeft <= 0 ) {
			unitsArray = unitsArray.slice(-1);
		}

		var string = '';
		unitsArray.forEach( (unit) => {
			// Calculate the number of time units & total time left 
			unitsLeft = 0;
			if( timeLeft >= unit.duration ) {
				if( timer.format !== 'single' || ( !string && timer.format === 'single' ) ) {
					unitsLeft = Math.floor( timeLeft /  unit.duration );
					timeLeft -= (unitsLeft *  unit.duration);
				}
			}

			// Plural object for internationalized plural forms
			// TODO: add support for "few" and "many" plural forms
			const pluralForms = {
				"one": unit.singular,
				"other": unit.plural
			}

			// Add unit output to string
			if( unitsLeft > 0 || ( timeLeft <= 0 && unit.id ===  unitsArray.pop()['id'] ) ) {
				string += ' ' + lwcd_timers.getPluralForm( pluralForms, unitsLeft, timer.locale ).replace('%s', unitsLeft);
			}
		})

		return string
	},
	getPluralForm: function( forms, count, locale ) {
		const matchingForm = new Intl.PluralRules(locale).select(count);
		return forms[matchingForm];

	}
}
lwcd_timers.init();
