<?php 

class tx_cbgooglemaps_includeBeJavascript{
	
	public function includeCbGoogleMapsJavascript($config){
				
		$doc = t3lib_div::makeInstance('noDoc');
		$filePath = $doc->backPath.t3lib_extMgm::extRelPath('cbgooglemaps');
		
		$doc->JScode .= '<script src="https://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>';  
		$doc->JScode .= '<script src="'.$filePath.'Resources/Public/JavaScript/class.gmaps.js" type="text/javascript"></script>';  
		$doc->JScode .= '<script src="'.$filePath.'Resources/Public/JavaScript/cbgooglemaps_geocoding.js" type="text/javascript"></script>';  
		
		return $doc->JScode;
	}
}

