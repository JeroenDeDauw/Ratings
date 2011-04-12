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
	
	/**
	 * Self executing function to setup the rating stars on the page.
	 * This is done by finding all tags for all pages that should
	 * be displayed and then gathering this data via the API to show
	 * the current vote values.
	 */
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
	
	/**
	 * Obtain the vote values for a set of tags of a single page,
	 * and then find and update the corresponding rating stars.
	 * 
	 * @param {string} page
	 * @param {Array} tags
	 */
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
	
	/**
	 * Submit a rating.
	 * 
	 * @param {string} page
	 * @param {string} tag
	 * @param {integer} value
	 */
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