/*
 * Provides functionality to geocode, localize positions by using
 * google maps api v3 and display given geocoded postions in
 * google maps views.
 *
 * @package				class.gmaps
 * @version				1.0: gmaps,  03-2011
 * @copyright 			(c)2011 Christian Brinkert
 * @author 				Christian Brinkert <christian.brinkert@googlemail.com>
 */

function gmaps(){
	// set self
	var self = this;

	// set private properties
	var street = null;
	var zip = null;
	var city = null;
	var country = null;

	// initialize defauls
	var lat = null;
	var lng = null;
	
	// current uid
	var uid = null;



	// helper methods
	function trim(givenString){
		return givenString.replace (/^\s+/, '').replace (/\s+$/, '');
	}



	// getter & setter
	this.setStreet = function(streetString){
		if (typeof(streetString) == 'string' && '' != streetString)
			self.street = streetString;
	}
	this.getStreet = function(){
		return self.street;
	}


	this.setZip = function(zipCode){
		if (typeof(zipCode) == 'string' && '' != zipCode)
			self.zip = zipCode;
	}
	this.getZip = function(){
		return self.zip;
	}


	this.setCity = function(cityString){
		if (typeof(cityString) == 'string' && '' != cityString)
			self.city = cityString;
	}
	this.getCity = function(){
		return self.city;
	}


	this.setCountry = function(countryString){
		if (typeof(countryString) == 'string' && '' != countryString)
			self.country = countryString;
	}
	this.getCountry = function(){
		return self.country;
	}


	this.setLatitude = function(latitude){
		if (typeof(latitude) == 'number')
			self.lat = latitude;
	}
	this.getLatitude = function(){
		return self.lat;
	}


	this.setLongitude = function(longitude){
		if (typeof(longitude) == 'number')
			self.lng = longitude;
	}
	this.getLongitude = function(){
		return self.lng;
	}


	this.setUid = function(uid){
		self.uid = uid;
	}
	this.getUid = function(){
		return self.uid;
	}




	// get geocoordinates
	this.fetchCoordinatesByAddress = function(callback, uid){
        // set uid
        self.setUid(uid);

		// create google geocoder object
		var geocoder = new google.maps.Geocoder();

		if (geocoder) {
			// try to fetch coordinates and save them to local property
			geocoder.geocode( {'address': this.getAddressAsString()}, function(results, status) {
				// check if geocoding was successfull					
				if (status == google.maps.GeocoderStatus.OK) {
					self.setLatitude(results[0].geometry.location.lat());
					self.setLongitude(results[0].geometry.location.lng());
					// return values to callback method
					callback(self.getLatitude(), self.getLongitude(), self.getUid());
				}
			});
		}
	}


	// set location by one method
	this.setAddress = function(street, zip, city, country){
		self.setStreet(street);
		self.setZip(zip);
		self.setCity(city);
		self.setCountry(country);
	}

	// return complete location as comma separated string
	this.getAddressAsString = function(){
		var address = new Array();
		if(null != self.getStreet())
			address.push(self.getStreet());
		if(null != self.getZip() && null != self.getCity()){
			address.push(trim(self.getZip() +" "+ self.getCity()));
		}else if(null != self.getZip()){
			address.push(self.getZip());
		}else if(null != self.getCity()){
			address.push(self.getCity());
		}
		if(null != self.getCountry())
			address.push(self.getCountry());

		// return array as string
		var addressAsString = address.join(", ");
		return addressAsString;
	}

	// set coordinates by one call
	this.setCoordinates = function(lat, lng){
		self.lat = latitude;
		self.lng = longitude;
	}
}