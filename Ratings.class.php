<?php

/**
 * Static class for general functions of the Ratings extension.
 * 
 * @since 0.1
 * 
 * @file Ratings.php
 * @ingroup Ratings
 * 
 * @licence GNU GPL v3
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
final class Ratings {
	
	/**
	 * Schema update to set up the needed database tables.
	 * 
	 * @since 0.1
	 * 
	 * @param DatabaseUpdater $updater
	 * 
	 * @return true
	 */	
	public static function getPageRatings( Title $page, $forceUpdate = false ) {
		if ( !$forceUpdate ) {
			$cached = self::getCachedPageRatings( $page );
			
			if ( $cached !== false ) {
				return $cached;
			}
		}
		
		return self::getAndCalcPageRatings( $page );
	}
	
	protected static function getAndCalcPageRatings( Title $page ) {
		$tags = array();
		
		foreach ( self::getTagNames() as $tagName => $tagId ) {
			$tags[$tagId] = array( 'count' => 0, 'total' => 0, 'name' => $tagName );
		}		
		
		$dbr = wfGetDb( DB_SLAVE );
		
		$votes = $dbr->select(
			'votes',
			array(
				'vote_prop_id',
				'vote_value'
			),
			array(
				'vote_page_id' => $page->getArticleId()
			)
		);	
		
		while ( $vote = $votes->fetchObject() ) {
			$tags[$vote->vote_prop_id]['count']++;
			$tags[$vote->vote_prop_id]['total'] += $vote->vote_value;
		}
		
		foreach ( $tags as &$tag ) {
			$tag['avarage'] = $tag['total'] / $tag['count'];
		}
		
		return $tags;
	}
	
	protected static function getCachedPageRatings( Title $page ) {
		return false;
	}
	
	public static function getTagNames() {
		$dbr = wfGetDb( DB_SLAVE );
		
		$props = $dbr->select(
			'vote_props',
			array( 'prop_id', 'prop_name' )
		);

		$tags = array();
		
		while ( $tag = $props->fetchObject() ) {
			$tags[$tag->prop_name] = $tag->prop_id;
		}
		
		return $tags;
	}
	
}
