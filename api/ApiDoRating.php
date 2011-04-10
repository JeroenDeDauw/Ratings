<?php

/**
 * API module to rate properties of pages.
 *
 * @since 0.1
 *
 * @file ApiDoRating.php
 * @ingroup Ratings
 *
 * @licence GNU GPL v3 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ApiDoRating extends ApiBase {
	
	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}
	
	public function execute() {
		$params = $this->extractRequestParams();
		
		global $wgUser;
		if ( !$wgUser->isAllowed( 'rate' ) || $wgUser->isBlocked() 
			|| !array_key_exists( 'token', $params ) || !$wgUser->matchEditToken( $params['token'] ) ) {
			$this->dieUsageMsg( array( 'badaccess-groups' ) );
		}		
		
		// In MW 1.17 and above ApiBase::PARAM_REQUIRED can be used, this is for b/c with 1.16.
		foreach ( array( 'tag', 'pagename', 'value' ) as $requiredParam ) {
			if ( !isset( $params[$requiredParam] ) ) {
				$this->dieUsageMsg( array( 'missingparam', $requiredParam ) );
			}
		}
		
		$page = Title::newFromText( $params['pagename'], NS_MAIN );
		
		if ( !$page->exists() ) {
			$this->dieUsageMsg( array( 'notanarticle' ) );
		}
		
		// TODO: Check if the user already voted
		
		$this->getResult()->addValue(
			null,
			null,
			$this->setRating( $page, $params['tags'], $params['value'], $revId )
		);
	}
	
	/**
	 * 
	 * 
	 * @since 1.1
	 * 
	 * @param Title $page
	 * @param string $tagName
	 * @param integer $value
	 * 
	 * @return
	 */
	protected function setRating( Title $page, $tagName, $value, $revId ) {
		global $wgUser;
		
		$dbw = wfGetDB( DB_MASTER );
		
		
		
	}
	
	public function getAllowedParams() {
		return array(
			'tag' => array(
				ApiBase::PARAM_TYPE => 'string',
				//ApiBase::PARAM_REQUIRED => true,
			),
			'pagename' => array(
				ApiBase::PARAM_TYPE => 'string',
				//ApiBase::PARAM_REQUIRED => true,
			),
			'value' => array(
				ApiBase::PARAM_TYPE => 'integer',
				//ApiBase::PARAM_REQUIRED => true,
			),
			'revid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),			
			'token' => null,			
		);
	}
	
	public function getParamDescription() {
		return array(
			'tag' => 'The tag that is rated',
			'pagename' => 'Name of the page',
			'value' => 'The value of the rating'
		);
	}
	
	public function getDescription() {
		return array(
			'Allows rating a single tag for a single page.'
		);
	}
	
	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'badaccess-groups' ),
			array( 'missingparam', 'tag' ),
			array( 'missingparam', 'pagename' ),
			array( 'missingparam', 'value' ),
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=dorating&pagename=User:Jeroen_De_Dauw&tag=awesomeness&value=9001&token=ABC012',
		);
	}
	
	public function needsToken() {
		return true;
	}	

	public function getVersion() {
		return __CLASS__ . ': $Id: $';
	}	
	
}
