/*
 * Handles geocoding and displays google maps with given locations
 *
 * @package				class.gmaps
 * @version				1.0: gmaps,  03-2011
 * @copyright 			(c)2011 Christian Brinkert
 * @author 				Christian Brinkert <christian.brinkert@googlemail.com>
 */


/*
 * Try to find coordinates by given address
 */
function doGeocoding(uid){	

	// fetch current inputs 
	var street = document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmStreet][vDEF]_hr")[0].value;
	var zip = document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmZip][vDEF]_hr")[0].value;
	var city = document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmCity][vDEF]_hr")[0].value;
	var country = document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmCountry][vDEF]_hr")[0].value;

	// create gmaps object
	var gmap = new gmaps();

	// associate values to object
	gmap.setAddress(street, zip, city, country);
	// try to fetch coordinates by address, set callback method
	gmap.fetchCoordinatesByAddress(resultsHandling, uid);
}



/*
 * Result handle to check if gecoding was succesfull and display results in backend form
 */
function resultsHandling(latitude, longitude, uid){	
	// work with given results
	if (typeof(latitude) == 'number' && typeof(longitude) == 'number'){
		// write results to backend form		
		document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmLatitude][vDEF]_hr")[0].value = latitude;
		document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmLatitude][vDEF]")[0].value = latitude;
		document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmLongitude][vDEF]_hr")[0].value = longitude;
		document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmLongitude][vDEF]")[0].value = longitude;
	} else {
		alert("Die angegebene Adresse konnte nicht lokalisiert werden.");
	}
}



/*
 * Display results in a new windows with coordinates and google maps preview
 */
function displayLocation(uid){
    // get coordinates
	var latitude = parseFloat(document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmLatitude][vDEF]_hr")[0].value);
	var longitude = parseFloat(document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmLongitude][vDEF]_hr")[0].value);

	// check if coordinates are given
	if ( typeof(latitude) == 'number' && typeof(longitude) == 'number' ){
        // set attributes to the map
        document.getElementById('cbgm_previewLocation').setAttribute("style", "width:530px; height:300px; border:1px solid #8E8E8E; ", false);

        // create google maps LatLng object
        var latlng = new google.maps.LatLng(latitude, longitude);
        // set options object
        var myOptions = {
                        zoom: 15,
                        center: latlng,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
        // create map
        var map = new google.maps.Map(document.getElementById("cbgm_previewLocation"), myOptions);
        // create map marker
        var marker = new google.maps.Marker({
                        position: latlng,
                        draggable: true,
                        title:""
                        });
        // add listener
        google.maps.event.addListener(marker, 'dragend', function() {
                        // set new coordinates to the typo3 backend
        	document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmLatitude][vDEF]_hr")[0].value = marker.getPosition().lat();
    		document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmLatitude][vDEF]")[0].value = marker.getPosition().lat();
    		document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmLongitude][vDEF]_hr")[0].value = marker.getPosition().lng();
    		document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmLongitude][vDEF]")[0].value = marker.getPosition().lng();
    		});

        // assign marker to the map
        marker.setMap(map);

    } else {
        alert("No coordinates or location given.");
    }
}




/*
 * Display results in a new windows with coordinates and google maps preview
 */
function displayPreview(uid, defaultZoom, defaultMapType, defaultMapControl){
    // get coordinates				
	var latitude = parseFloat(document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmLatitude][vDEF]_hr")[0].value);
	var longitude = parseFloat(document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][sDEF][lDEF][settings.cbgmLongitude][vDEF]_hr")[0].value);
	var infoText = document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][s_displayproperties][lDEF][settings.cbgmDescription][vDEF]")[0].value;
	var zoom = parseInt(document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][s_displayproperties][lDEF][settings.cbgmScaleLevel][vDEF]_hr")[0].value);
	var mapType = document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][s_displayproperties][lDEF][settings.cbgmMapType][vDEF]")[0].value;
	var mapControl = document.getElementsByName("data[tt_content]["+uid+"][pi_flexform][data][s_displayproperties][lDEF][settings.cbgmNavigationControl][vDEF]")[0].value;
	
	if (isNaN(zoom)) zoom = parseInt(defaultZoom);
	if ('' == mapType) mapType = defaultMapType;
	if ('' == mapControl) mapControl = defaultMapControl;

	// split infoText into rows
	var rowsInfoText = infoText.split("\n");
	
	// check if coordinates are given
	if ( typeof(latitude) == 'number' && typeof(longitude) == 'number' ){
        // set attributes to the map
        document.getElementById('cbgm_previewMap').setAttribute("style", "width:530px; height:300px; border:1px solid #8E8E8E; ", false);

        // create google maps LatLng object
        var latlng = new google.maps.LatLng(latitude, longitude);
        // set options object
        var myOptions = {
                "zoom": zoom,
                "center": latlng,
                "navigationControl": true
                };
        
        // specifiy map type
        switch(mapType){
	        case "ROADMAP":
	        	myOptions.mapTypeId = google.maps.MapTypeId.ROADMAP;
	        	break;
	        case "TERRAIN":
	        	myOptions.mapTypeId = google.maps.MapTypeId.TERRAIN;
	        	break;
	        case "SATELLITE":
	        	myOptions.mapTypeId = google.maps.MapTypeId.SATELLITE;
	        	break;
	        default:
	        	myOptions.mapTypeId = google.maps.MapTypeId.HYBRID;
        }   
        
        // specifiy navigation control
        switch(mapControl){
	        case "SMALL":
	        	myOptions.navigationControlOptions = {style: google.maps.NavigationControlStyle.SMALL};
	        	break;
	        case "ZOOM_PAN":
	        	myOptions.navigationControlOptions = {style: google.maps.NavigationControlStyle.ZOOM_PAN};
	        	break;
	        case "ANDROID":
	        	myOptions.navigationControlOptions = {style: google.maps.NavigationControlStyle.ANDROID};
	        	break;
	        default:
	        	myOptions.navigationControlOptions = {style: google.maps.NavigationControlStyle.DEFAULT};
        }   
        
        // create map
        var map = new google.maps.Map(document.getElementById("cbgm_previewMap"), myOptions);
        // create map marker
        var marker = new google.maps.Marker({
                        position: latlng,
                        title:rowsInfoText[0]
                        });
        
        // set info window
        if ('' != infoText){
        	infoText = infoText.replace(/\n/g, "<br>");
        	
        	var infowindow = new google.maps.InfoWindow({ content: infoText });
        	// add listener
        	google.maps.event.addListener(marker, 'click', function(){ infowindow.open(map,marker); });
        }
        
        // assign marker to the map
        marker.setMap(map);

    } else {
        alert("No coordinates or location given.");
    }
}
