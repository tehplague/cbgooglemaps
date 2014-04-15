<?php
/**
 * Class with methods to extend flexforms with user fields
 * @package				Tx_Cbgooglemaps_addFlexformFields
 * @path 				Tx_Cbgooglemaps_addFlexformFields.php
 * @version				1.0: Tx_Cbgooglemaps_addFlexformFields.php,  03.07.2011
 * @copyright 			(c)2011 Christian Brinert
 * @author 				Christian Brinkert <christian.brinkert@googlemail.com>
 */

class tx_cbgooglemaps_addFlexformFields
{
	public function addGeocodingButton($config)
	{
		$fiedset = '';
		$fiedset =  '<div class="cbgm_geocoding">';
		$fiedset .= '<input type="button" onclick="doGeocoding(\''.$config['row']['uid'].'\')" value="fetch coordinates from above-mentioned address">';
		$fiedset .= '<input type="button" onclick="displayLocation(\''.$config['row']['uid'].'\')" value="display current location">';
		$fiedset .= '<div id="cbgm_previewLocation"></div>';
		$fiedset .= '</div>';

		return $fiedset;
	}

	public function addPreviewButton($config)
	{
		// load current extension typoscript and provide settings to $this->conf
		// require_once (PATH_t3lib.'class.t3lib_page.php');
		// require_once (PATH_t3lib.'class.t3lib_tstemplate.php');
		// require_once (PATH_t3lib.'class.t3lib_tsparser_ext.php');

		$sysPageObj = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $sysPageObj->getRootLine($config['row']['pid']);
		$TSObj = t3lib_div::makeInstance('t3lib_tsparser_ext');
		$TSObj->tt_track = 0;
		$TSObj->init();
		$TSObj->runThroughTemplates($rootLine);
		$TSObj->generateConfig();
		$this->conf = $TSObj->setup['plugin.']['tx_cbgooglemaps.'];

		$fiedset = '';
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
