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

        // get objectManager
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            'TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        // get configuration manager
        $this->configurationManager = $objectManager->get(
            'TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');

		// get typoscript configuration
		$this->settings = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
			'Cbgooglemaps',
			'Quickgooglemap'
		);

		# deprecated and removed in TYPO3 7.0, use following class: 'TYPO3\CMS\Backend\Template\DocumentTemplate' instead	
		$doc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Backend\Template\DocumentTemplate');

		$filePath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('cbgooglemaps');

		// build googlemaps library url with optional api key - if given
		$googleMapsUri = $this->settings['plugin.']['tx_cbgooglemaps.']['settings.']['googleapi.']['uri'];
		if ($this->settings['plugin.']['tx_cbgooglemaps.']['settings.']['googleapi.']['key'])
			$googleMapsUri .= '?key='. $this->settings['plugin.']['tx_cbgooglemaps.']['settings.']['googleapi.']['key'];

		// add javascripts
		$doc->JScode .= '<script src="'. $googleMapsUri .'" type="text/javascript"></script>';
		$doc->JScode .= '<script src="'.$filePath.'Resources/Public/JavaScript/class.gmaps.js" type="text/javascript"></script>';  
		$doc->JScode .= '<script src="'.$filePath.'Resources/Public/JavaScript/cbgooglemaps_geocoding.js" type="text/javascript"></script>';  
		
		return $doc->JScode;
	}
}

