<?php

/**
 * Class to render star ratings.
 * 
 * @since 0.1
 * 
 * @file RatingsStars.php
 * @ingroup Ratings
 * 
 * @licence GNU GPL v3 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
final class RatingsStars extends ParserHook {
	
	/**
	 * No LSB in pre-5.3 PHP *sigh*.
	 * This is to be refactored as soon as php >=5.3 becomes acceptable.
	 */
	public static function staticMagic( array &$magicWords, $langCode ) {
		$className = __CLASS__;
		$instance = new $className();
		return $instance->magic( $magicWords, $langCode );
	}
	
	/**
	 * No LSB in pre-5.3 PHP *sigh*.
	 * This is to be refactored as soon as php >=5.3 becomes acceptable.
	 */	
	public static function staticInit( Parser &$parser ) {
		$className = __CLASS__;
		$instance = new $className();
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
		return array( 'starrating' );
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
		global $egRatingsShowWhenDisabled;
		
		$params = array();
		
		$params['page'] = new Parameter( 'page' );
		$params['page']->setDescription( wfMsg( 'ratings-par-page' ) );
		
		$params['tag'] = new Parameter( 'tag' );
		$params['tag']->setDescription( wfMsg( 'ratings-par-tag' ) );		
		
		$params['showdisabled'] = new Parameter( 'showdisabled', Parameter::TYPE_BOOLEAN );
		$params['showdisabled']->setDescription( wfMsg( 'ratings-par-showdisabled' ) );			
		$params['showdisabled']->setDefault( $egRatingsShowWhenDisabled );
		
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
		// TODO
	}
	
	/**
	 * Loads the needed JavaScript.
	 * Takes care of non-RL compatibility.
	 * 
	 * @since 0.1
	 */
	protected function loadJs() {
		static $loadedJs = false;
		
		if ( $loadedJs ) {
			return;
		}
		
		$loadedJs = true;
		
		$this->addJSWikiData();
		
		// For backward compatibility with MW < 1.17.
		if ( is_callable( array( $this->parser->getOutput(), 'addModules' ) ) ) {
			$this->parser->getOutput()->addModules( 'ext.ratings.stars' );
		}
		else {
			global $egIncWPScriptPath, $wgStylePath, $wgStyleVersion;
			
			$this->addJSLocalisation();

			$this->parser->getOutput()->addHeadItem(
				Html::linkedScript( "$wgStylePath/common/jquery.min.js?$wgStyleVersion" ),
				'jQuery'
			);
			
			$this->parser->getOutput()->addHeadItem(
				Html::linkedScript( $egRatingsScriptPath . '/ext.rattings.stars.js' ),
				'ext.ratings.stars'
			);
		}		
	}	
	
	/**
	 * Ouput the wiki data needed to display the licence links.
	 * 
	 * @since 0.1
	 */
	protected function addJSWikiData() {
		$this->parser->getOutput()->addHeadItem(
			Html::inlineScript(
				'' //'var wgIncWPWikis =' . json_encode( $egIncWPWikis ) . ';'
			)
		);
	}
	
	/**
	 * Adds the needed JS messages to the page output.
	 * This is for backward compatibility with pre-RL MediaWiki.
	 * 
	 * @since 0.1
	 */
	protected function addJSLocalisation() {
		global $egRatingsStarsJSMessages;
		
		$data = array();
	
		foreach ( $egRatingsStarsJSMessages as $msg ) {
			$data[$msg] = wfMsgNoTrans( $msg );
		}

		$this->parser->getOutput()->addHeadItem( Html::inlineScript( 'var wgRatingsStarsMessages = ' . json_encode( $data ) . ';' ) );
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
