<?php

/**
 * Initialization file for the Ratings extension.
 * 
 * Documentation:	 		http://www.mediawiki.org/wiki/Extension:Ratings
 * Support					http://www.mediawiki.org/wiki/Extension_talk:Ratings
 * Source code:             http://svn.wikimedia.org/viewvc/mediawiki/trunk/extensions/Ratings
 *
 * @file Ratings.php
 * @ingroup Ratings
 *
 * @licence GNU GPL v3
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * This documenation group collects source code files belonging to Ratings.
 *
 * @defgroup Ratings Ratings
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( version_compare( $wgVersion, '1.16', '<' ) ) {
	die( 'Ratings requires MediaWiki 1.16 or above.' );
}

// Include the Validator extension if that hasn't been done yet, since it's required for Include WP to work.
if ( !defined( 'Validator_VERSION' ) ) {
	@include_once( dirname( __FILE__ ) . '/../Validator/Validator.php' );
}

// Only initialize the extension when all dependencies are present.
if ( ! defined( 'Validator_VERSION' ) ) {
	die( '<b>Error:</b> You need to have <a href="http://www.mediawiki.org/wiki/Extension:Validator">Validator</a> installed in order to use <a href="http://www.mediawiki.org/wiki/Extension:SubPageList">SubPageList</a>.<br />' );
}

define( 'Ratings_VERSION', '0.1 alpha' );

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Ratings',
	'version' => Ratings_VERSION,
	'author' => array(
		'[http://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]',
	),
	'url' => 'http://www.mediawiki.org/wiki/Extension:Ratings',
	'descriptionmsg' => 'ratings-desc'
);

$egRatingsScriptPath = $wgExtensionAssetsPath === false ? $wgScriptPath . '/extensions/Ratings' : $wgExtensionAssetsPath . '/Ratings';

$wgExtensionMessagesFiles['Ratings'] = dirname( __FILE__ ) . '/Ratings.i18n.php';

$wgAutoloadClasses['ApiDoRating'] = dirname( __FILE__ ) . '/api/ApiApiDoRating.php';
$wgAutoloadClasses['ApiQueryRatings'] = dirname( __FILE__ ) . '/api/ApiApiQueryRatings.php';

$wgAPIModules['dorating'] = 'ApiDoRating';
$wgAPIListModules['ratings'] = 'ApiQueryRatings';

$wgAutoloadClasses['RatingsStars'] = dirname( __FILE__ ) . '/starrating/RatingsStars.php';

$wgHooks['ParserFirstCallInit'][] = 'RatingsStars::staticInit';
$wgHooks['LanguageGetMagic'][] = 'RatingsStars::staticMagic';

$egRatingsStarsJSMessages = array(
);

// For backward compatibility with MW < 1.17.
if ( defined( 'MW_SUPPORTS_RESOURCE_MODULES' ) ) {
	$moduleTemplate = array(
		'localBasePath' => dirname( __FILE__ ),
		'remoteBasePath' => $egRatingsScriptPath
	);
	
	$wgResourceModules['ext.ratings.stars'] = $moduleTemplate + array(
		'styles' => array(
			'starrating/star-rating/jquery.rating.css'
		),
		'scripts' => array(
			'starrating/star-rating/jquery.rating.js',
			'starrating/ext.ratings.stars.js'
		),
		'dependencies' => array(),
		'messages' => $egRatingsStarsJSMessages
	);
}

require_once 'Ratings.settings.php';
