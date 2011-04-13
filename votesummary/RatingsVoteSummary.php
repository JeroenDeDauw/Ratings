<?php

/**
 * Class to show a summary of votes for a single page, tag combination.
 * 
 * @since 0.1
 * 
 * @file RatingsVoteSummary.php
 * @ingroup Ratings
 * 
 * @licence GNU GPL v3 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
final class RatingsVoteSummary extends ParserHook {
	
	protected static $pageRatings = array();
	
	/**
	 * No LSB in pre-5.3 PHP *sigh*.
	 * This is to be refactored as soon as php >=5.3 becomes acceptable.
	 */
	public static function staticMagic( array &$magicWords, $langCode ) {
		$instance = new self;
		return $instance->magic( $magicWords, $langCode );
	}
	
	/**
	 * No LSB in pre-5.3 PHP *sigh*.
	 * This is to be refactored as soon as php >=5.3 becomes acceptable.
	 */
	public static function staticInit( Parser &$parser ) {
		$instance = new self;
		return $instance->init( $parser );
	}

	/**
	 * Gets the name of the parser hook.
	 * @see ParserHook::getName
	 * 
	 * @since 0.1
	 * 
	 * @return string
	 */
	protected function getName() {
		return array( 'votesummary' );
	}
	
	/**
	 * Returns an array containing the parameter info.
	 * @see ParserHook::getParameterInfo
	 * 
	 * @since 0.1
	 * 
	 * @return array
	 */
	protected function getParameterInfo( $type ) {
		$params = array();
		
		$params['page'] = new Parameter( 'page' );
		$params['page']->setDescription( wfMsg( 'ratings-par-page' ) );
		$params['page']->setDefault( false, false );
		
		$params['tag'] = new Parameter( 'tag' );
		$params['tag']->setDescription( wfMsg( 'ratings-par-tag' ) );		
		
		return $params;
	}
	
	/**
	 * Returns the list of default parameters.
	 * @see ParserHook::getDefaultParameters
	 * 
	 * @since 0.1
	 * 
	 * @return array
	 */
	protected function getDefaultParameters( $type ) {
		return array( 'tag', 'page' );
	}
	
	/**
	 * Renders and returns the output.
	 * @see ParserHook::render
	 * 
	 * @since 0.1
	 * 
	 * @param array $parameters
	 * 
	 * @return string
	 */
	public function render( array $parameters ) {
		$this->loadJs();
		
		$parameters['page'] = $parameters['page'] === false ? $GLOBALS['wgTitle'] : Title::newFromText( $parameters['page'] ); 
		
		static $ratingStarNr = 0; $ratingStarNr++;
		
		$inputs = array();
		
		for ( $i = 0; $i < 5; $i++ ) {
			$inputs[] = Html::element(
				'input',
				array(
					'class' => 'starrating',
					'type' => 'radio',
					'name' => 'ratingstars_' . $ratingStarNr,
					'value' => $i,
					'page' => $parameters['page']->getFullText(),
					'tag' => $parameters['tag'],
				)
			);
		}
		
		if ( true ) {
			$tagData = $this->getCurrentRating( $parameters['tag'] );
			
			$message = htmlspecialchars( wfMsgExt(
				'ratings-stars-current-score',
				'parsemag',
				$tagData['avarage'] + 1, // Internal representatation is 0 based, don't confuse poor users :)
				$tagData['count']
			) );
			
			array_unshift( $inputs, $message . '<br />' );
		}
		
		return Html::rawElement(
			'div',
			array( 'style' => 'display:inline; position:static' ),
			implode( '', $inputs )
		);
	}
	
	/**
	 * Returns the data for the tag in an array, or false is there is no data.
	 * 
	 * @param string $tagName
	 * 
	 * @return false or array
	 */
	protected function getCurrentRating( $tagName ) {
		$title = $GLOBALS['wgTitle']->getFullText();
		
		if ( !array_key_exists( $title, self::$pageRatings ) ) {
			self::$pageRatings[$title] = array();
			
			// The keys are the tag ids, but they are not known here, so change to tag names, which are known.
			foreach ( Ratings::getPageRatings( $GLOBALS['wgTitle'] ) as $tagId => $tagData ) {
				self::$pageRatings[$title][$tagData['name']] = array_merge( array( 'id' => $tagId ), $tagData );
			}
		}
		
		return array_key_exists( $tagName, self::$pageRatings[$title] ) ? self::$pageRatings[$title][$tagName] : false;
	}
	
	/**
	 * Returns the parser function otpions.
	 * @see ParserHook::getFunctionOptions
	 * 
	 * @since 0.1
	 * 
	 * @return array
	 */
	protected function getFunctionOptions() {
		return array(
			'noparse' => true,
			'isHTML' => true
		);
	}
	
	/**
	 * @see ParserHook::getDescription()
	 * 
	 * @since 0.1
	 */
	public function getDescription() {
		return wfMsg( 'ratings-starsratings-desc' );
	}		
	
}
