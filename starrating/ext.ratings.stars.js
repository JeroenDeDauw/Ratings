/**
 * JavasSript for the Ratings extension.
 * @see http://www.mediawiki.org/wiki/Extension:Ratings
 * 
 * @licence GNU GPL v3 or later
 * @author Jeroen De Dauw <jeroendedauw at gmail dot com>
 */

(function($) { $( document ).ready( function() {

	$( '.starrating' ).rating({
		callback: function( value, link ){
			var self = $(this);
			submitRating( self.attr( 'page' ), self.attr( 'tag' ), value );
		}
	});
	
	function getRating() {
		
	}
	
	function submitRating( page, tag, value ) {
		$.post(
			wgScriptPath + '/api.php',
			{
				'action': 'dorating',
				'format': 'json',
				'pagename': page,
				'tag': tag,
				'value': value
			},
			function( data ) {
				if ( data.error && data.error.info ) {
					alert( data.error.info );
				}				
				else if ( data.result.success ) {
					// TODO
				}
				else {
					alert( 'Failed to submit rating' ) // TODO
				}
			},
			'json'
		);
	}
	
} ); })(jQuery);