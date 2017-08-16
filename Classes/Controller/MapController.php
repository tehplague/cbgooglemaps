<?php

namespace Brinkert\Cbgooglemaps\Controller;

/**
 * Class to extend the backend with a tca user field 
 * @package				Cbgooglemaps
 * @path 				Cbgooglemaps\Controller\MapController.php
 * @version				1.0: MapController.php,  03.07.2011
 * @copyright 			(c)2011-2016 Christian Brinkert
 * @author 				Christian Brinkert <christian.brinkert@googlemail.com>
 */

class MapController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	protected $configurationManager;
	protected $ceData;
	protected $settings;
	protected $cobj;
	
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
	}
	
	public function initializeAction() {
		// store content element data to local property
		$this->ceData = $this->configurationManager->getContentObject()->data;	
		
		$this->settings = $this->configurationManager->getConfiguration(
							\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
							'Cbgooglemaps',
							'Quickgooglemap');

		// add googlemaps library with api key - if given
        $googleMapsUri = $this->settings['googleapi']['uri'];
        if (!empty($this->settings['googleapi']['key']))
            $googleMapsUri .= '?key='. $this->settings['googleapi']['key'];

        $GLOBALS['TSFE']->additionalHeaderData['cbgooglemaps'] = 
        	  '<script src="'. $googleMapsUri .'" type="text/javascript"></script>';
	}  
	
	
	public function indexAction()
	{
		$this->cobj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
			'TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');


		// assign an unique id to view
		if (null != $this->ceData['uid'])
			$uid = $this->ceData['uid'];
		else
			$uid = rand(1, 999999);
		$uid .= '_'. $this->configurationManager->getContentObject()->parentRecord['data']['uid'];
		$this->view->assign('contentId', $uid);


		// assign width and height of map
		if (null != $this->ceData['width'])
			$width = $this->ceData['width'];
		else if (0 < (int)$this->settings['cbgmMapWidth'])
			$width = $this->settings['cbgmMapWidth'];
		else
			$width = $this->settings['display']['width'];
		$this->view->assign('width', $width);

		if (null != $this->ceData['height'])
			$height = $this->ceData['height'];
		else if (0 < (int)$this->settings['cbgmMapHeight'])
			$height = $this->settings['cbgmMapHeight'];
		else
			$height = $this->settings['display']['height'];
		$this->view->assign('height', $height);


		// assign pin description text
		if (null != $this->ceData['infoText'])
			$infoText = $this->ceData['infoText'];
		else if (isset($this->settings['cbgmDescription']))
			$infoText = $this->settings['cbgmDescription'];
		else
			$infoText = $this->settings['infoText'];
		$this->view->assign('infoText', urlencode($infoText));


		// assign icon if given by constant and/or typoscript
		if (null != $this->ceData['icon']
			&& file_exists(PATH_site . $this->ceData['icon']))
			$this->view->assign('icon', \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . '/' . $this->ceData['icon']);
		else if (!empty($this->settings['display']['icon'])
		 && file_exists(PATH_site . $this->settings['display']['icon']))
			$this->view->assign('icon', \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . '/' . $this->settings['display']['icon']);
		else
			$this->view->assign('icon', null);


		// assign deactivation of zooming by mousewheel
		if (null != $this->ceData['useScrollwheel'])
			$this->view->assign('useScrollwheel', $this->ceData['useScrollwheel'] );
		else
			$this->view->assign('useScrollwheel', $this->settings['options']['useScrollwheel'] );


		// assign location (longitude and latitude) to the view
		if (null != $this->ceData['latitude'])
			$latitude = (float) $this->ceData['latitude'];
		else if (isset($this->settings['cbgmLatitude']))
			$latitude = (float) $this->settings['cbgmLatitude'];
		else
			$latitude = (float) $this->settings['latitude'];
		$this->view->assign('latitude', $latitude);


		if (null != $this->ceData['longitude'])
			$longitude = (float) $this->ceData['longitude'];
		else if (isset($this->settings['cbgmLongitude']))
			$longitude = (float) $this->settings['cbgmLongitude'];
		else
			$longitude = (float) $this->settings['longitude'];
		$this->view->assign('longitude', $longitude);


		// assign map zoom level to the view ,if given value is valid
		if (null != $this->ceData['zoom'])
			$mapZoom = (int) $this->ceData['zoom'];
		else if (0 <= (int)$this->settings['cbgmScaleLevel']
		  && !empty($this->settings['cbgmScaleLevel']))
			$mapZoom = (int) $this->settings['cbgmScaleLevel'];
		else
			$mapZoom = $this->settings['display']['zoom'];
		$this->view->assign('mapZoom', $mapZoom);


		// assign map type to the view, if given value is valid
		if (null != $this->ceData['mapType']
			&& in_array((string) $this->ceData['mapType'],
				preg_split("/[\s]*[,][\s]*/", $this->settings['valid']['mapTypes'])))
			$mapType = $this->ceData['mapType'];
		else if (in_array((string)$this->settings['cbgmMapType'],
			preg_split("/[\s]*[,][\s]*/", $this->settings['valid']['mapTypes'])))
			$mapType = $this->settings['cbgmMapType'];
		else
			$mapType = $this->settings['display']['mapType'];
		$this->view->assign('mapType', $mapType);


		// assign navigation controls to the view
		if (null != $this->ceData['navigationControl']
			&& in_array((string) $this->ceData['navigationControl'],
				preg_split("/[\s]*[,][\s]*/", $this->settings['valid']['navigationControl'])))
			$navigationControl = $this->ceData['navigationControl'];
		else if (in_array((string)$this->settings['cbgmNavigationControl'],
				preg_split("/[\s]*[,][\s]*/", $this->settings['valid']['navigationControl'])))
			$navigationControl = $this->settings['cbgmNavigationControl'];
		else
			$navigationControl = $this->settings['display']['navigationControl'];
		$this->view->assign('mapControl', $navigationControl);


		// assign map styling, if given
		$this->view->assign('mapStyling', null);
		if (null != $this->ceData['mapStyling']
			&& file_exists(PATH_site . $this->ceData['mapStyling']) ){

			$styling = file_get_contents(PATH_site. $this->ceData['mapStyling']);

			if (!is_null(json_decode($styling)))
				$this->view->assign('mapStyling', $styling);

		} else if (!empty($this->settings['display']['mapStyling'])
		 && file_exists(PATH_site . $this->settings['display']['mapStyling']) ) {

			$styling = file_get_contents(PATH_site . $this->settings['display']['mapStyling']);

			if (!is_null(json_decode($styling)))
				$this->view->assign('mapStyling', $styling);

		} else if (!empty($this->settings['display']['mapStyling'])
		 		&& !is_null(json_decode($this->settings['display']['mapStyling']))){
			$this->view->assign('mapStyling', $this->settings['display']['mapStyling']);
		}

		$this->view->assign('braceStart', '{');
		$this->view->assign('braceEnd', '}');
		

		// assign auto open flag to the view
		if (isset($this->settings['cbgmAutoOpen']))
			$this->view->assign('openInfoBox', $this->settings['cbgmAutoOpen']);
		else
			$this->view->assign('openInfoBox', $this->settings['infoTextOpen']);


	}
}