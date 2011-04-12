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
	
	(function initGetRatings() {
		var ratings = {};
		
		$.each($(".starrating"), function(i,v) {
			var self = $(this);
			
			if ( typeof self.attr( 'page' ) != 'undefined' ) {
				if ( !ratings[self.attr( 'page' )] ) {
					ratings[self.attr( 'page' )] = [];
				}
				
				ratings[self.attr( 'page' )].push( self.attr( 'tag' ) );				
			}
		});
		
		for ( i in ratings ) {
			getRatingsForPage( i, $.unique( ratings[i] ) );
		}
	})();
	
	function getRatingsForPage( page, tags ) {
		$.getJSON(
			wgScriptPath + '/api.php',
			{
				'action': 'query',
				'format': 'json',
				'list': 'ratings',
				'page': page,
				'tags': tags.join( '|' )
			},
			function( data ) {
				// TODO
			}
		); 		
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