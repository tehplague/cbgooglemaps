<?php 
/**
 * Class with methods to extend flexforms with user fields 
 * @package				tx_cbgooglemaps_includeBeJavascript
 * @path 				tx_cbgooglemaps_includeBeJavascript.php
 * @version				3.0: tx_cbgooglemaps_includeBeJavascript.php,  17.04.2014
 * @copyright 			(c)2011-2014 Christian Brinert
 * @author 				Christian Brinkert <christian.brinkert@googlemail.com>
 */
namespace Cbgooglemaps\Service;

class IncludeBeJavascript{
	
	public function includeCbGoogleMapsJavascript($config){
		# deprecated and removed in TYPO3 7.0, use following class: 'TYPO3\CMS\Backend\Template\DocumentTemplate' instead	
		#$doc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Backend\Template\StandardDocumentTemplate');
		$doc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Backend\Template\DocumentTemplate');
		
        $filePath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('cbgooglemaps');
		
		$doc->JScode .= '<script src="https://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>';  
		$doc->JScode .= '<script src="'.$filePath.'Resources/Public/JavaScript/class.gmaps.js" type="text/javascript"></script>';  
		$doc->JScode .= '<script src="'.$filePath.'Resources/Public/JavaScript/cbgooglemaps_geocoding.js" type="text/javascript"></script>';  
		
		return $doc->JScode;
	}
}

