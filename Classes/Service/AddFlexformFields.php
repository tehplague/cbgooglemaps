<?php 
/**
 * Class with methods to extend flexforms with user fields 
 * @package				tx_cbgooglemaps_addFlexformFields
 * @path 				tx_cbgooglemaps_addFlexformFields.php
 * @version				3.0: tx_cbgooglemaps_addFlexformFields.php,  17.04.2014
 * @copyright 			(c)2011-2014 Christian Brinert
 * @author 				Christian Brinkert <christian.brinkert@googlemail.com>
 */
namespace Cbgooglemaps\Service;

class AddFlexformFields
{	
	public function addGeocodingButton($config)
	{		
		$fiedset =  '<div class="cbgm_geocoding">';
		$fiedset .= '<input type="button" onclick="doGeocoding(\''.$config['row']['uid'].'\')" value="fetch coordinates from above-mentioned address">';
		$fiedset .= '<input type="button" onclick="displayLocation(\''.$config['row']['uid'].'\')" value="display current location">';
		$fiedset .= '<div id="cbgm_previewLocation"></div>';
		$fiedset .= '</div>';
		
		return $fiedset;
	}

	public function addPreviewButton($config)
	{
		// instantiate TYPO3\CMS\Frontend\Page\PageRepository object
		$sysPageObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Page\PageRepository');
		// get rootline from current page ($config['row']['pid'])
		$rootLine = $sysPageObj->getRootLine($config['row']['pid']);
		// instantiate TYPO3\CMS\Core\TypoScript\ExtendedTemplateService object
		$TSObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\TypoScript\ExtendedTemplateService');
		// set top top level of rootline, deactivate tt-timeobject logging, 
		// initialize object, run through templates und create typoscript configuration tree
		$TSObj->tt_track = 0;
		$TSObj->init();
		$TSObj->runThroughTemplates($rootLine);
		$TSObj->generateConfig();
		// select requested typoscript from own extension
		$this->conf = $TSObj->setup['plugin.']['tx_cbgooglemaps.'];
			
		$fiedset =  '<div class="cbgm_preview">';
		$fiedset .= '<input type="button" onclick="displayPreview(\''
				  . $config['row']['uid'].'\',\''
				  . $this->conf['settings.']['display.']['zoom'].'\',\''
				  . $this->conf['settings.']['display.']['mapType'].'\',\''
				  . $this->conf['settings.']['display.']['navigationControl'].'\')" '
				  . 'value="display current map">';
		$fiedset .= '<div id="cbgm_previewMap"></div>';
		$fiedset .= '</div>';
		
		return $fiedset;
	}
}

?>