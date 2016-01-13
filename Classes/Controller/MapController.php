<?php 

/**
 * Class to extend the backend with a tca user field 
 * @package				Cbgooglemaps
 * @path 				Cbgooglemaps\Controller\MapController.php
 * @version				1.0: MapController.php,  03.07.2011
 * @copyright 			(c)2011-2014 Christian Brinert
 * @author 				Christian Brinkert <christian.brinkert@googlemail.com>
 */

class Tx_Cbgooglemaps_Controller_MapController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	protected $configurationManager;
	protected $ceData;
	protected $settings;
	
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
		
		// fix to the previous rows which includes the js file multiple times
        $GLOBALS['TSFE']->additionalHeaderData['cbgooglemaps'] = 
        	  '<script src="'. $this->settings['googleapi']['uri'] .'" type="text/javascript"></script>';  
	}  
	
	
	public function indexAction()
	{
		// assign contents to the view
		$this->view->assign('contentId', $this->ceData['uid']);

		// assign width and height of map
		if (0 < (int)$this->settings['cbgmMapWidth'])
			$width = $this->settings['cbgmMapWidth'];
		else
			$width = $this->settings['display']['width'];
		$this->view->assign('width', $width);


		if (0 < (int)$this->settings['cbgmMapHeight'])
			$height = $this->settings['cbgmMapHeight'];
		else
			$height = $this->settings['display']['height'];
		$this->view->assign('height', $height);

		// assign pin description text
		$infoText = ($this->settings['cbgmDescription']);
		$this->view->assign('infoText', urlencode($infoText));

		// assign icon if given by constant and/or typoscript
		if (!empty($this->settings['display']['icon'])
				&& file_exists(PATH_site . $this->settings['display']['icon'])
		)
			$this->view->assign('icon', TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . '/' . $this->settings['display']['icon']);
		else
			$this->view->assign('icon', null);

		// assign deactivation of zooming by mousewheel			
		$this->view->assign('useScrollwheel',
				($this->settings['options']['useScrollwheel'] ? 'true' : 'false'));

		// assign location (longitude and latitude) to the view
		$this->view->assign('latitude', (float)$this->settings['cbgmLatitude']);
		$this->view->assign('longitude', (float)$this->settings['cbgmLongitude']);

		// assign map zoom level to the view ,if given value is valid
		if (0 <= (int)$this->settings['cbgmScaleLevel'] && !empty($this->settings['cbgmScaleLevel']))
			$mapZoom = (int)$this->settings['cbgmScaleLevel'];
		else
			$mapZoom = $this->settings['display']['zoom'];
		$this->view->assign('mapZoom', $mapZoom);

		// assign map type to the view, if given value is valid
		if (in_array((string)$this->settings['cbgmMapType'],
				preg_split("/[\s]*[,][\s]*/", $this->settings['valid']['mapTypes'])))
			$mapType = $this->settings['cbgmMapType'];
		else
			$mapType = $this->settings['display']['mapType'];
		$this->view->assign('mapType', $mapType);

		// assign navigation controls to the view
		if (in_array((string)$this->settings['cbgmNavigationControl'],
				preg_split("/[\s]*[,][\s]*/", $this->settings['valid']['navigationControl'])))
			$navigationControl = $this->settings['cbgmNavigationControl'];
		else
			$navigationControl = $this->settings['display']['navigationControl'];

		$this->view->assign('mapControl', $navigationControl);

		// assign map styling, if given
		$this->view->assign('mapStyling', null);

		if (!empty($this->settings['display']['mapStyling'])
		 && file_exists(PATH_site . $this->settings['display']['mapStyling']) ){

			$styling = file_get_contents(PATH_site. $this->settings['display']['mapStyling']);

			if (!is_null(json_decode($styling))) {
				$this->view->assign('mapStyling', $styling);
			}
		} else if (!empty($this->settings['display']['mapStyling'])
		 		&& !is_null(json_decode($this->settings['display']['mapStyling']))){
			$this->view->assign('mapStyling', $this->settings['display']['mapStyling']);
		}

		$this->view->assign('braceStart', '{');
		$this->view->assign('braceEnd', '}');
		

		// assign auto open flag to the view
		$this->view->assign('openInfoBox', ($this->settings['cbgmAutoOpen'])?true:false );

	}
}