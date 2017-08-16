<?php

namespace Brinkert\Cbgooglemaps\Service;

/**
 * Class with methods to extend flexforms with user fields
 *
 * @package				includeBeJavascript
 * @path 				includeBeJavascript.php
 * @version				3.0: includeBeJavascript.php,  17.04.2014
 * @copyright 			(c)2011-2014 Christian Brinkert
 * @author 				Christian Brinkert <christian.brinkert@googlemail.com>
 */

class IncludeBeJavascript{

	protected $configurationManager = null;
	protected $settings = null;

	public function includeCbGoogleMapsJavascript($config){

        // fetch current extension typoscript configuration
        $sysPageObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
        $TSObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\TemplateService');
        $TSObj->tt_track = 0;
        $TSObj->init();
        $TSObj->runThroughTemplates($sysPageObj->getRootLine($config['row']['pid']));
        $TSObj->generateConfig();
        $this->settings = $TSObj->setup['plugin.']['tx_cbgooglemaps.'];

		// build googlemaps library url with optional api key - if given
		$googleMapsUri = $this->settings['settings.']['googleapi.']['uri'];
		if ($this->settings['settings.']['googleapi.']['key'])
			$googleMapsUri .= '?key='. $this->settings['settings.']['googleapi.']['key'];

        $doc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Backend\Template\DocumentTemplate');
        $filePath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('cbgooglemaps');

		// add javascripts
		$doc->JScode .= '<script src="'. $googleMapsUri .'" type="text/javascript"></script>';
		$doc->JScode .= '<script src="'.$filePath.'Resources/Public/JavaScript/class.gmaps.js" type="text/javascript"></script>';  
		$doc->JScode .= '<script src="'.$filePath.'Resources/Public/JavaScript/cbgooglemaps_geocoding.js" type="text/javascript"></script>';  
		
		return $doc->JScode;
	}
}

