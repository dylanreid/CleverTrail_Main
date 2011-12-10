/* Copyright CleverTrail.com and Dylan Reid */

var imgTrailLoading = new Image();
imgTrailLoading.src = "http://clevertrail.com/images/load_large.gif";

var imgUpArrow = new Image();
imgUpArrow.src = "http://clevertrail.com/images/icons/icon-up-brown25.png";

var imgDownArrow = new Image();
imgDownArrow.src = "http://clevertrail.com/images/icons/icon-down-brown25.png";

var myMap;
var myMarkers;
var myBounds = null;
var bMapLoaded = false;
var bMarkersLoaded = false;
var bBoundsChange;
var bFilterIsOpenDuringReload = false;
var bResettingFilterValues = false;
var prefix = "";
var pageName = "";
var maxZoomLevel = 14;

function createTrailMap() {
	myZoom = 2;
				
	//geocoder
	myGeocoder = new google.maps.Geocoder();
	
	//map options
    var myOptions = {
		zoom: myZoom,
		center: new google.maps.LatLng(12.8597277, 3.1938125), //default center of the world
		disableDoubleClickZoom: false,
		streetViewControl: false,
		disableDefaultUI: true,
		zoomControl: true,
		backgroundColor: "#E9F0EC",
		mapTypeControl: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP,  
		mapTypeControlOptions: {  
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
			mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.TERRAIN, google.maps.MapTypeId.SATELLITE]
			}  
    };

	//create the map
    myMap = new google.maps.Map(document.getElementById("div" + prefix + "TrailMap"), myOptions);
	
	google.maps.event.addListener(myMap, 'tilesloaded', function() {		
		bMapLoaded = true;
		checkToDisplayMapLoading();				
		google.maps.event.clearListeners(myMap, 'tilesloaded');
		
	});	
	
	//if we change the bounds and zoom way in, let's make sure there is a minimum zoom
	google.maps.event.addListener(myMap, 'zoom_changed', function() {
		if (bBoundsChange) {
			bBoundsChange = false;
			if (myMap.getZoom() > maxZoomLevel)
				myMap.setZoom(maxZoomLevel);
		}
	});
	
}

function checkToDisplayMapLoading() {
	if (bMapLoaded && bMarkersLoaded) {
		$('#div' + prefix + 'TrailMap').css('display', 'block');
		$('#div' + prefix + 'SearchMap').css('display', 'block');
		if (bFilterIsOpenDuringReload)
			$('#divFilter' + prefix + 'TrailMap').css('display', 'block');
		$('#divFilter' + prefix + 'TrailMapExpand').css('display', 'block');
		$('#div' + prefix + 'TrailMapLoading').css('display', 'none');
		
		//fix the google map bug
		var center = myMap.getCenter(); 
		google.maps.event.trigger(myMap, 'resize'); 
		myMap.setCenter(center); 	
		
		//change the viewport bounds to show all the markers
		if (prefix != "Homepage" && myBounds != null){
			bBoundsChange = true;
			myMap.fitBounds(myBounds);
		}
			
	} else {
		$('#div' + prefix + 'TrailMap').css('display', 'none');
		$('#div' + prefix + 'SearchMap').css('display', 'none');
		$('#divFilter' + prefix + 'TrailMapExpand').css('display', 'none');
		$('#divFilter' + prefix + 'TrailMap').css('display', 'none');
		$('#div' + prefix + 'TrailMapLoading').css('display', 'block');
		
	}
}

function createTrailMapMarkers(){

	if (bResettingFilterValues || !validateNumericInput())
		return;
		
	bFilterIsOpenDuringReload = $('#divFilter' + prefix + 'TrailMap').is(":visible");
	
	//throw up loading box
	bMarkersLoaded = false;
	checkToDisplayMapLoading();
	
	if (myMarkers)
		myMarkers.clearMarkers();
		
	$(".infoBox").hide();
	
	var trailUses = "";
	$('input:checkbox[name="chkFilterTrailUse[]"]:checked').each(function(index) { 
		trailUses += $(this).val() + ",";
	});
	
	var distanceMin = $('#txtFilterDistanceMin').val();
	var distanceMax = $('#txtFilterDistanceMax').val();
	var distanceUnits = $('#selFilterDistanceUnits').val();	
	if (distanceUnits == "kilometers"){
		distanceMin = distanceMin * 0.6;
		distanceMax = distanceMax * 0.6;
	}
	
	var timeMin = $('#txtFilterTimeMin').val();
	var timeMax = $('#txtFilterTimeMax').val();
	var timeUnits = $('#selFilterTimeUnits').val();
	if (timeUnits == "hours"){
		timeMin = timeMin * 60;
		timeMax = timeMax * 60;
	}
	if (timeUnits == "days"){
		timeMin = timeMin * 60 * 24;
		timeMax = timeMax * 60 * 24;
	}
	
	var difficultyMin = $('#selFilterDifficultyMin').val();
	var difficultyMax = $('#selFilterDifficultyMax').val();
	var trailType = $('#selFilterTrailType').val();	
	var includeNoData = $('#chkFilterIncludeNoData').is(":checked");
	
	var postData = "trailuse=" + trailUses + "&distanceMin=" + distanceMin + "&distanceMax=" + distanceMax +
				   "&timeMin=" + timeMin + "&timeMax=" + timeMax + "&difficultyMin=" + difficultyMin + "&difficultyMax=" + difficultyMax +
				   "&trailType=" + trailType + "&includeNoData=" + includeNoData;
			   
	var postUrl = wgServer;
	if (prefix == "Category") {
		postUrl += "/ajax/handleGetCategoryTrailMapMarkers.php";
		postData += "&category=" + pageName;
	} else if (prefix == "User") {
		postUrl += "/ajax/handleGetUserTrailMapMarkers.php";
		postData += "&user=" + pageName;
	} else {
		postUrl += "/ajax/handleGetHomepageTrailMapMarkers.php";
	}
			
	$.ajax({
		type: "POST",
		url: postUrl,
		data: postData,
		dataType: "json",
		beforeSend: function(x) {
		  if(x && x.overrideMimeType) {
		   x.overrideMimeType("application/j-son;charset=UTF-8");
		  }
		 },
		success: function(response){
			var arMarkersResponse = response.markers;
		
			if (arMarkersResponse.length > 0)
				myBounds = new google.maps.LatLngBounds();
			else
				myBounds = null;
			
			//create the markers			
			var arMarkersGoogle = [];
			var markers;
			var latLng;
			for (i = 0; i < arMarkersResponse.length; i++) {
				latLng = new google.maps.LatLng(arMarkersResponse[i][0], arMarkersResponse[i][1]);
				
				var boxText = document.createElement("div");
				boxText.innerHTML = arMarkersResponse[i][3];
				boxText.style.cssText = arMarkersResponse[i][4];				
		 
				var infobox = new InfoBox({
					 content: boxText
					,disableAutoPan: false
					,maxWidth: 0
					,pixelOffset: new google.maps.Size(-150, 0)
					,zIndex: null
					,boxStyle: { 
					  opacity: 0.85
					  ,width: "330px"
					 }
					,closeBoxMargin: "10px 2px 2px 2px"
					,closeBoxURL: "http://clevertrail.com/images/icons/icon-delete-brown25.png"
					,infoBoxClearance: new google.maps.Size(1, 1)
					,isHidden: false
					,pane: "floatPane"
					,enableEventPropagation: false
				});
								
				marker = new google.maps.Marker({ 
					position: latLng, 
    			    title: arMarkersResponse[i][2], 
				    icon: 'http://clevertrail.com/images/icons/hikingmarker.png',
				    info: infobox
				});				
				
				google.maps.event.addListener(marker, "click", function (e) {
					this.info.open(myMap, this);
				});			
				
				arMarkersGoogle.push(marker);
				myBounds.extend(latLng);
			}				

			//create all the markers and marker clusters
			myMarkers = new MarkerClusterer(myMap, arMarkersGoogle, {maxZoom: maxZoomLevel});
			
		},
		complete: function()
		{
			//toggle the loading screen
			bMarkersLoaded = true;
			checkToDisplayMapLoading();
		},
		error: function (data, status, e)
		{
			if (data.responseText != "")
				drawCleverTrailAlert(data.responseText);
		}
	});			
}

function validateNumericInput() {
	sErrors = "";
	
	aNumber = document.getElementById('txtFilterDistanceMin').value;
	if (!isNumber(aNumber)) {
		sErrors += "<li>Minimum Distance value is not a valid number (should be only numbers and decimals, e.g. \"1234.5\")</li>";
	}
	
	aNumber2 = document.getElementById('txtFilterDistanceMax').value;
	if (!isNumber(aNumber2)) {
		sErrors += "<li>Maximum Distance value is not a valid number (should be only numbers and decimals, e.g. \"1234.5\")</li>";
	}
	
	if (aNumber > aNumber2 && aNumber2 != 0) {
		sErrors += "<li>Minimum Distance is greater than Maximum Distance</li>";
	}
	
	aNumber = document.getElementById('txtFilterTimeMin').value;
	if (!isNumber(aNumber)) {
		sErrors += "<li>Minimum Time value is not a valid number (should be only numbers and decimals, e.g. \"1234.5\")</li>";
	}
	
	aNumber2 = document.getElementById('txtFilterTimeMax').value;
	if (!isNumber(aNumber2)) {
		sErrors += "<li>Maximum Time value is not a valid number (should be only numbers and decimals, e.g. \"1234.5\")</li>";
	}
	
	if (aNumber > aNumber2 && aNumber2 != 0) {
		sErrors += "<li>Minimum Time is greater than Maximum Time</li>";
	}
	
	aNumber = document.getElementById('selFilterDifficultyMin').value;
	aNumber2 = document.getElementById('selFilterDifficultyMax').value;
	
	if (aNumber > aNumber2 && aNumber2 != 0) {
		sErrors += "<li>Minimum Difficulty is greater than Maximum Difficulty</li>";
	}

	if (sErrors != "")
	{	
		sErrors = "<b>Please correct some errors that were found:</b><br><br><ul>" + sErrors + "</ul>";
		
		$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
		$('#divEditTrailSectionQuickFacts').fadeIn(400, function() {});
		$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
		$('#divEditTrailHelpfulTipsQuickFacts').fadeIn(400, function() {});
		$('#txtEditTrailSectionNumber').val(-1);
		$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
		$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
		
		drawCleverTrailAlert(sErrors);
		return false;
	} else {
		return true;
	}
}

//use google's geocoder to search for an input location
function searchForLocation() {
    var location = document.getElementById("txtSearchMap").value;
    myGeocoder.geocode( { 'address': location}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        myMap.setCenter(results[0].geometry.location);
		myMap.fitBounds(results[0].geometry.bounds);
      } else {
		sErrors = "<b>Google Maps was unable to find a location for your search terms.<br>";
		sErrors += "Try searching for a more general location and then zooming in</b>";
		drawCleverTrailAlert(sErrors);
      }
	});
}

//call the searchForLocation function if the enter button is pressed
function searchForLocationIfEnter(e){
	var c = document.all? event.keyCode : e.which;
    if(c == 13) 
	{
		searchForLocation();
		return false;
	}
    return true;
}

	
//begin: jquery effects

//change colors of trail search box when entering/leaving
$('#txtSearchMap').live('focus', function () {
	if ($(this).val() == "search for a map location or lat/long") {
		$(this).val("");
		$(this).css("color", "#333");
	}
});

//filter map by trail use checkboxes
$(":checkbox").live('click', function () {
	createTrailMapMarkers();
});

$("#selFilterTrailType").live('change', function () {
	createTrailMapMarkers();
});

$("#txtFilterDistanceMin").live('change', function () {
	createTrailMapMarkers();
});

$("#txtFilterDistanceMax").live('change', function () {
	createTrailMapMarkers();
});

$("#txtFilterTimeMin").live('change', function () {
	createTrailMapMarkers();
});

$("#txtFilterTimeMax").live('change', function () {
	createTrailMapMarkers();
});

$("#selFilterDifficultyMin").live('change', function () {
	createTrailMapMarkers();
});

$("#selFilterDifficultyMax").live('change', function () {
	createTrailMapMarkers();
});

$("#selFilterDistanceUnits").live('change', function () {
	if ($("#txtFilterDistanceMax").val() != "" || $("#txtFilterDistanceMin").val() != "")
		createTrailMapMarkers();
});

$("#selFilterTimeUnits").live('change', function () {
	if ($("#txtFilterTimeMin").val() != "" || $("#txtFilterTimeMax").val() != "")
		createTrailMapMarkers();
});

$('.clsFilterTrailMapExpand').live('click', function () {
	if ($('#divFilter' + prefix + 'TrailMap').is(":hidden")){
		$(this).html('Hide Trail Map Filter <img src="http://clevertrail.com/images/icons/icon-up-brown25.png">');
		$('#divFilter' + prefix + 'TrailMap').show("fast");
	} else {
		$(this).html('Show Trail Map Filter <img src="http://clevertrail.com/images/icons/icon-down-brown25.png">');
		$('#divFilter' + prefix + 'TrailMap').hide("fast");
	}
	
});

$('#divFilterClearInput').live('click', function () {
	bResettingFilterValues = true;
	$("#txtFilterTimeMin").val("");
	$("#txtFilterTimeMax").val("");
	$("#txtFilterDistanceMin").val("");
	$("#txtFilterDistanceMax").val("");
	$("#selFilterDifficultyMin").val("");
	$("#selFilterDifficultyMax").val("");
	$("#selFilterTrailType").val("");
	$(":checkbox").prop("checked", false);
	$("#chkFilterIncludeNoData").prop("checked", true);
	bResettingFilterValues = false;
	createTrailMapMarkers();
	
});

//end: jquery effects