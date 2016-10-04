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
	var street = ''; var zip = ''; var city = ''; var country = '';

	// fetch current inputs
	var fieldPrefix = "data[tt_content]["+ uid +"][pi_flexform][data][sDEF][lDEF]";

	// TYPO3 <= 6.2
	if (document.getElementsByName(fieldPrefix +"[settings.cbgmZip][vDEF]_hr")[0]){

		street =  document.getElementsByName(fieldPrefix + "[settings.cbgmStreet][vDEF]_hr")[0].value;
		zip =     document.getElementsByName(fieldPrefix + "[settings.cbgmZip][vDEF]_hr")[0].value;
		city =    document.getElementsByName(fieldPrefix + "[settings.cbgmCity][vDEF]_hr")[0].value;
		country = document.getElementsByName(fieldPrefix + "[settings.cbgmCountry][vDEF]_hr")[0].value;
	}
	// TYPO3 >= 7.x
	else if(TYPO3.jQuery
		&& TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmStreet][vDEF]\']") ){

		street =  TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmStreet][vDEF]\']").val();
		zip =     TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmZip][vDEF]\']").val();
		city = 	  TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmCity][vDEF]\']").val();
		country = TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmCountry][vDEF]\']").val();
	}

	// create gmaps object
	var gmap = new gmaps();

	// associate values to object
	gmap.setAddress(street, zip, city, country);
	// try to fetch coordinates by address, set callback method
	gmap.fetchCoordinatesByAddress(resultsHandling, uid);
}



/**
 * Result handle to check if gecoding was succesfull and display results in backend form
 */
function resultsHandling(latitude, longitude, uid){	
	// work with given results
	if (typeof(latitude) == 'number' && typeof(longitude) == 'number'){

		// write results to backend form
		var fieldPrefix = "data[tt_content][" + uid + "][pi_flexform][data][sDEF][lDEF]";

		// TYPO3 <= 6.2
		if (document.getElementsByName(fieldPrefix +"[settings.cbgmLatitude][vDEF]_hr")[0]) {

			document.getElementsByName(fieldPrefix + "[settings.cbgmLatitude][vDEF]_hr")[0].value = latitude;
			document.getElementsByName(fieldPrefix + "[settings.cbgmLatitude][vDEF]")[0].value = latitude;
			document.getElementsByName(fieldPrefix + "[settings.cbgmLongitude][vDEF]_hr")[0].value = longitude;
			document.getElementsByName(fieldPrefix + "[settings.cbgmLongitude][vDEF]")[0].value = longitude;
		}
		// TYPO3 >= 7.x
		else if(TYPO3.jQuery
			&& TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmLatitude][vDEF]\']") ){

			TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmLatitude][vDEF]\']").val(latitude);
			TYPO3.jQuery("input[name*=\'"+ fieldPrefix +"[settings.cbgmLatitude][vDEF]\']").val(latitude);
			TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmLongitude][vDEF]\']").val(longitude);
			TYPO3.jQuery("input[name*=\'"+ fieldPrefix +"[settings.cbgmLongitude][vDEF]\']").val(longitude);
		}

	} else {
		alert("Die angegebene Adresse konnte nicht lokalisiert werden.");
	}
}



/*
 * Display results in a new windows with coordinates and google maps preview
 */
function displayLocation(uid){
	var latitude = 0; var longitude = 0;

	// get coordinates
	// write results to backend form
	var fieldPrefix = "data[tt_content][" + uid + "][pi_flexform][data][sDEF][lDEF]";

	// TYPO3 <= 6.2
	if (document.getElementsByName(fieldPrefix +"[settings.cbgmLatitude][vDEF]_hr")[0]) {
		latitude  = parseFloat(document.getElementsByName(fieldPrefix +"[settings.cbgmLatitude][vDEF]_hr")[0].value);
		longitude = parseFloat(document.getElementsByName(fieldPrefix +"[settings.cbgmLongitude][vDEF]_hr")[0].value);
	}
	// TYPO3 >= 7.x
	else if(TYPO3.jQuery
		&& TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmLatitude][vDEF]\']") ){

		latitude = parseFloat(
			TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmLatitude][vDEF]\']").val()
		);
		longitude = parseFloat(
			TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmLongitude][vDEF]\']").val()
		);
	}

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
			var fieldPrefix = "data[tt_content][" + uid + "][pi_flexform][data][sDEF][lDEF]";

			// TYPO3 <= 6.2
			if (document.getElementsByName(fieldPrefix +"[settings.cbgmLatitude][vDEF]_hr")[0]) {

				document.getElementsByName(fieldPrefix +"[settings.cbgmLatitude][vDEF]_hr")[0].value =  marker.getPosition().lat();
				document.getElementsByName(fieldPrefix +"[settings.cbgmLatitude][vDEF]")[0].value =  marker.getPosition().lat();

				document.getElementsByName(fieldPrefix +"[settings.cbgmLatitude][vDEF]_hr")[0].value =  marker.getPosition().lng();
				document.getElementsByName(fieldPrefix +"[settings.cbgmLatitude][vDEF]")[0].value =  marker.getPosition().lng();
			}
			// TYPO3 >= 7.x
			else if(TYPO3.jQuery
				&& TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmLatitude][vDEF]\']") ){

				TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmLatitude][vDEF]\']").val(marker.getPosition().lat());
				TYPO3.jQuery("input[name*=\'"+ fieldPrefix +"[settings.cbgmLatitude][vDEF]\']").val(marker.getPosition().lat());
				TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix +"[settings.cbgmLongitude][vDEF]\']").val(marker.getPosition().lng());
				TYPO3.jQuery("input[name*=\'"+ fieldPrefix +"[settings.cbgmLongitude][vDEF]\']").val(marker.getPosition().lng());
			}

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
	var latitude = 0; var longitude = 0; var infoText = ''; var zoom = 10; var mapType = 'ROADMAP'; var mapControl = 'DEFAULT';

	// fetch current inputs
	var fieldPrefix1 = "data[tt_content]["+ uid +"][pi_flexform][data][sDEF][lDEF]";
	var fieldPrefix2 = "data[tt_content]["+ uid +"][pi_flexform][data][s_displayproperties][lDEF]";

	// TYPO3 <= 6.2
	if (document.getElementsByName(fieldPrefix1 +"[settings.cbgmZip][vDEF]_hr")[0]){

		latitude =   parseFloat(document.getElementsByName(fieldPrefix1 + "[settings.cbgmLatitude][vDEF]_hr")[0].value);
		longitude =  parseFloat(document.getElementsByName(fieldPrefix1 + "[settings.cbgmLongitude][vDEF]_hr")[0].value);
		infoText =   document.getElementsByName(fieldPrefix2 + "[settings.cbgmDescription][vDEF]")[0].value;
		zoom = 		 parseInt(document.getElementsByName(fieldPrefix2 + "[settings.cbgmScaleLevel][vDEF]_hr")[0].value,10);
		mapType = 	 document.getElementsByName(fieldPrefix2 + "[settings.cbgmMapType][vDEF]")[0].value;
		mapControl = document.getElementsByName(fieldPrefix2 + "[settings.cbgmNavigationControl][vDEF]")[0].value;
	}
	// TYPO3 >= 7.x
	else if(TYPO3.jQuery
		&& TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix1 +"[settings.cbgmStreet][vDEF]\']") ){

		latitude =   parseFloat(TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix1 +"[settings.cbgmLatitude][vDEF]\']").val());
		longitude =  parseFloat(TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix1 +"[settings.cbgmLongitude][vDEF]\']").val());
		infoText = 	 TYPO3.jQuery("textarea[name*=\'"+ fieldPrefix2 +"[settings.cbgmDescription][vDEF]\']").val();
		zoom = 		 parseInt(TYPO3.jQuery("input[data-formengine-input-name*=\'"+ fieldPrefix2 +"[settings.cbgmScaleLevel][vDEF]\']").val(),10);
		mapType = 	 TYPO3.jQuery("select[name*=\'"+ fieldPrefix2 +"[settings.cbgmMapType][vDEF]\']").val();
		mapControl = TYPO3.jQuery("select[name*=\'"+ fieldPrefix2 +"[settings.cbgmNavigationControl][vDEF]\']").val();
	}


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
