//javascript for the Special:EditTrail page

var imgInvalidphotopath = new Image();
imgInvalidphotopath.src = "http://clevertrail.com/images/Invalidphotopath.jpg";
var imgLoadingphoto = new Image();
imgLoadingphoto.src = "http://clevertrail.com/images/Loadingphoto.jpg";

var arPhotoGalleryImages = [];
var arCategories = [];

function addImageToPhotoGalleryListbox(imageName, addNotice){
	if (arPhotoGalleryImages.length == 10)
		return;
		
	if (imageName && imageName != ''){
		arPhotoGalleryImages.push(imageName);
		createPhotoListAndGallery();
	}
	
	if (addNotice)
		changeNoticeUnsavedChanges();
}

function moveImageInPhotoGalleryListbox(bUp){
	var photoList = document.getElementById("selEditTrailSectionPhotoGalleryImageList");
	position = 0;

	if (bUp){
	
		for (i=0; i<photoList.options.length; i++) {
			if (photoList.options[i].selected) {
				if (i == 0)
					return;
				
				position = i-1;
				
				temp = arPhotoGalleryImages[i-1];
				arPhotoGalleryImages[i-1] = arPhotoGalleryImages[i];
				arPhotoGalleryImages[i] = temp;
			
				break;
			}
		}
	}
	else{
		for (i=0; i<photoList.options.length; i++) {
			if (photoList.options[i].selected) {
				if (i == photoList.options.length-1)
					return;
				
				position = i+1;
				
				temp = arPhotoGalleryImages[i+1];
				arPhotoGalleryImages[i+1] = arPhotoGalleryImages[i];
				arPhotoGalleryImages[i] = temp;
			
				break;
			}
		}	
	}
	
	createPhotoListAndGallery();
	photoList.options[position].selected = true;
}

function removeImageFromPhotoGalleryListbox(){
	var photoList = document.getElementById("selEditTrailSectionPhotoGalleryImageList");
	
	if (photoList){
		arPhotoGalleryImages.length = 0;
		position = 0;
		
		for (i=0; i<photoList.options.length; i++) {
			if (!photoList.options[i].selected) {
				arPhotoGalleryImages.push(photoList.options[i].value);
			}
			else{
				if (i == photoList.options.length - 1)
					position = i - 1;
				else
					position = i;
			}
		}
		
		createPhotoListAndGallery();
		photoList.options[position].selected = true;
	}
	
	changeNoticeUnsavedChanges();
}

//add photos to the select box and reference the images as well
function createPhotoListAndGallery(){

	var photoList = document.getElementById("selEditTrailSectionPhotoGalleryImageList");
	
	if (photoList){
		photoList.options.length = 0;
		numImages = arPhotoGalleryImages.length;
		for (var i =0; i < 10; i++) {
			var spanElementId = '#spnEditTrailPhotoGalleryImage'+(i+1);
			var hiddenTextboxId = '#txtEditTrailSectionPhotoGalleryImageList'+(i+1);
			if (i < numImages) {			
				imageName = arPhotoGalleryImages[i];
				photoList.options[i]=new Option(imageName, imageName, false, false);
				imagePath = getPathOfFileName(imageName, 120);
				
				$(spanElementId).html('<img style="max-width:120px; max-height:120px" src="' + imagePath + '" title="' + imageName + '">');
				$(spanElementId).attr('width', 'auto');
				$(spanElementId).attr('height', 'auto');
				$(hiddenTextboxId).val(imageName);
			} else {
				$(spanElementId).html('');
				$(spanElementId).attr('width', 0);
				$(spanElementId).attr('height', 0);
				$(hiddenTextboxId).val('');
			}
			
		}
		
	}
}

//add this image to the photo list if pressing enter
function addImageToPhotoGalleryIfEnter(e){
	var c = document.all? event.keyCode : e.which;
    if(c == 13) 
	{
		addImageToPhotoGalleryListbox(document.getElementById('txtEditTrailSectionPhotoGalleryAddImage').value, true);
		return false;
	}
    return true;
}

function addToCategoriesListbox(categoryName, addNotice){
	if (arCategories.length == 10)
		return;
		
	if (categoryName && categoryName != ''){
		arCategories.push(categoryName);
		createCategoryList();
	}
	
	if (addNotice)
		changeNoticeUnsavedChanges();
}

function moveCategoryInListbox(bUp){
	var categoryList = document.getElementById("selEditTrailSectionCategoriesList");
	position = 0;

	if (bUp){
	
		for (i=0; i<categoryList.options.length; i++) {
			if (categoryList.options[i].selected) {
				if (i == 0)
					return;
				
				position = i-1;
				
				temp = arCategories[i-1];
				arCategories[i-1] = arCategories[i];
				arCategories[i] = temp;
			
				break;
			}
		}
	}
	else{
		for (i=0; i<categoryList.options.length; i++) {
			if (categoryList.options[i].selected) {
				if (i == categoryList.options.length-1)
					return;
				
				position = i+1;
				
				temp = arCategories[i+1];
				arCategories[i+1] = arCategories[i];
				arCategories[i] = temp;
			
				break;
			}
		}	
	}
	
	createCategoryList();
	categoryList.options[position].selected = true;
}

function removeFromCategoriesListbox(){
	var categoryList = document.getElementById("selEditTrailSectionCategoriesList");
	
	if (categoryList){
		arCategories.length = 0;
		position = 0;
		
		for (i=0; i<categoryList.options.length; i++) {
			if (!categoryList.options[i].selected) {
				arCategories.push(categoryList.options[i].value);
			}
			else {
				if (i == categoryList.options.length - 1)
					position = i - 1;
				else
					position = i;
			}
		}
		
		createCategoryList();
		categoryList.options[position].selected = true;
	}
	
	changeNoticeUnsavedChanges();
}

//add categories to the select box
function createCategoryList(){

	var categoryList = document.getElementById("selEditTrailSectionCategoriesList");
	
	if (categoryList){
		categoryList.options.length = 0;
		numCategories = arCategories.length;
		for (var i =0; i < 10; i++) {
			var hiddenTextboxId = '#txtEditTrailSectionCategoriesList'+(i+1);
			if (i < numCategories) {			
				categoryName = arCategories[i];
				categoryList.options[i]=new Option(categoryName, categoryName, false, false);
				$(hiddenTextboxId).val(categoryName);
			} else {
				$(hiddenTextboxId).val('');
			}			
		}		
	}
}

//add this category to the list if pressing enter
function addToCategoryIfEnter(e){
	var c = document.all? event.keyCode : e.which;
    if(c == 13) 
	{
		addToCategoriesListbox(document.getElementById('txtEditTrailSectionCategoriesAdd').value, true);
		document.getElementById('txtEditTrailSectionCategoriesAdd').value = "";
		return false;
	}
    return true;
}

//disable Enter key for form submissions
function disableEnter(e){
    var c = document.all? event.keyCode : e.which;
    if(c == 13) return false;
    return true;
}

//call the searchForAddress function CleverTrailMaps.js if the enter button is pressed
function searchForAddressIfEnter(e){
	var c = document.all? event.keyCode : e.which;
    if(c == 13) 
	{
		searchForAddress();
		return false;
	}
    return true;
}

//dynamically load the main image for the trail
function loadMainImage(){
	//throw up the loading image
	$('#imgEditTrailQuickFactsImage').attr('src', imgLoadingphoto.src);
	
	fileName = document.getElementById('txtEditTrailQuickFactsImage').value;
	//trim whitespace off ends
	fileName = jQuery.trim(fileName);
	
	//if the filename is empty we don't need the ajax call
	if (fileName.length == 0) {
		fileName = 'Nophotoavailable.jpg';
		imagePath = getPathOfFileName(fileName, 300);	
		$('#txtEditTrailQuickFactsImageValid').val("1");
		$('#imgEditTrailQuickFactsImage').attr('src', imagePath);
	} else { //if the file name is not empty, let's see if it's valid
	
		//for some bizarre reason if you copy and paste from a mediawiki file page
		//you can sometimes get a unicode character 8206 (left-to-right mark) at the end
		var lastCharCode = fileName.charCodeAt(fileName.length-1);
		if (lastCharCode == 8206) {
			fileName = fileName.slice(0,fileName.length-1); 
		}
		
		var postData = "fileName=" + fileName;
		
		$.ajax({
			type: "POST",
			url: wgServer + "/ajax/handleLoadEditTrailImage.php",
			data: postData,
			dataType: "json",
			beforeSend: function(x) {
			  if(x && x.overrideMimeType) {
			   x.overrideMimeType("application/j-son;charset=UTF-8");
			  }
			 },
			success: function(response){
				//check if path is invalid
				if (response.imageCode == 1) { 
					ajaxImagePath = imgInvalidphotopath.src;
					$('#txtEditTrailQuickFactsImageValid').val("0");
				} else {
					ajaxImagePath = response.imagePath;
					$('#txtEditTrailQuickFactsImageValid').val("1");
				}
				
				$('#imgEditTrailQuickFactsImage').attr('src', ajaxImagePath);
				
			},
			complete: function() {
			},
			error: function (data, status, e)
			{
				if (data.responseText != "")
					drawCleverTrailAlert(data.responseText);
			}
		});			
	}
}

//display a notice that the user has unsaved changes
function changeNoticeUnsavedChanges(){
	$('#divEditTrailNotice').show();
	$('#divEditTrailNotice').html("&nbsp;You have unsaved changes&nbsp;");
	window.onbeforeunload = confirmLeavePageWithUnsavedChanges;
}

function confirmLeavePageWithUnsavedChanges(){
	return "You have unsaved changes to this trail.";
}

//change the page type: trail, redirect, or disambiguation
function changeArticleType(){
	articleType = document.getElementById('selEditTrailArticleType').value;
	divTrail = document.getElementById('divEditTrailTrail');
	divRedirect = document.getElementById('divEditTrailRedirect');
	spnDisambiguationCheckbox = document.getElementById('spnEditTrailDisambiguationNote');
	
	if (articleType == 'Trail') {
		$(divTrail).css('display', 'block');
		$(spnDisambiguationCheckbox).show();
		if ($('#chkEditTrailAddDisambiguation').is(':checked'))
			$('#divDisambiguationBase').show();
	}
	else {
		$(divTrail).css('display', 'none');
		$(spnDisambiguationCheckbox).hide();
		$('#divDisambiguationBase').hide();
	}
		
	if (articleType == 'Redirect')
		$(divRedirect).css('display', 'block');
	else
		$(divRedirect).css('display', 'none');
		
}

//submit the EditTrailForm (called from a submit button
//we want to have a bit more control over a submit than a standard form
function submitEditTrailForm(submitButton){
	
	document.getElementById('submitValue').value = submitButton;
	window.onbeforeunload = null;
	
	if (submitButton == 'Cancel') {
		$('#divEditTrailNotice').show();
		$('#divEditTrailNotice').html("&nbsp;Canceling...&nbsp;");		
		document.getElementById('frmEditTrailForm').submit();
	} else {
		isValid = true;
		articleType = document.getElementById('selEditTrailArticleType').value;
		
		if (articleType == 'Trail') {
			organizeMarkers();
			isValid = validateNumericInput();
			isValid = isValid && validateMapInput();
		}
		
		if (isValid) {
			$('#divEditTrailNotice').show();
			$('#divEditTrailNotice').html("&nbsp;Saving...&nbsp;");	
			if (articleType == 'Trail') {
				createMarkerData();
			}
			document.getElementById('frmEditTrailForm').submit();
		}
	}
}

//validate map inputs
function validateMapInput() {
	sErrors = "";
	
	//since we just ordered the markers (organizeMarkers()), the first one should be a trailhead
	foundTrailhead = ((myMarkers.length > 0) && (myMarkers[0].markerType == "Trailhead"));
	
	if (!foundTrailhead) {
		trailheadImg =  "http://clevertrail.com/images/icons/googlemaps/Trailhead.png";
		sErrors += '<li>You must have at least one Trailhead marker <img src="' + trailheadImg + '"> on the map</li>';
	}
		
	if (sErrors != "")
	{	
		sErrors = "<b>Please correct some errors that were found:</b><br><br><ul>" + sErrors + "</ul>";
		
		$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
		$('#divEditTrailSectionMap').fadeIn(400, function() {});
		$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
		$('#divEditTrailHelpfulTipsMap').fadeIn(400, function() {});		
		$('#txtEditTrailSectionNumber').val(9);
		$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
		$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
		
		//init the google map	
		initGoogleMap();
		if (sErrors != "")
			drawCleverTrailAlert(sErrors);
		return false;
	} else {
		return true;
	}
}

//validate numbers in the quick facts section
function validateNumericInput() {
	sErrors = "";
	
	distance = document.getElementById('txtEditTrailQuickFactsDistance').value;
	if (!isNumber(distance)) {
		sErrors += "<li>Distance value is not a valid number (should be only numbers and decimals, e.g. \"1234.5\")</li>";
	}
	
	timerequiredmin = document.getElementById('txtEditTrailQuickFactsTimeRequiredMin').value;
	if (!isNumber(timerequiredmin)) {
		sErrors += "<li>Minimum Time Required value is not a valid number (should be only numbers and decimals, e.g. \"1234.5\")</li>";
	}
	
	timerequiredmax = document.getElementById('txtEditTrailQuickFactsTimeRequiredMax').value;
	if (!isNumber(timerequiredmax)) {
		sErrors += "<li>Maximum Time Required value is not a valid number (should be only numbers and decimals, e.g. \"1234.5\")</li>";
	}
	
	elevationgain = document.getElementById('txtEditTrailQuickFactsElevationGain').value;
	if (!isNumber(elevationgain)) {
		sErrors += "<li>Elevation Gain value is not a valid number (should be only numbers and decimals, e.g. \"1234.5\")</li>";
	}
	
	highpoint = document.getElementById('txtEditTrailQuickFactsHighPoint').value;
	if (!isNumber(highpoint)) {
		sErrors += "<li>High Point value is not a valid number (should be only numbers and decimals, e.g. \"1234.5\")</li>";
	}
	
	lowpoint = document.getElementById('txtEditTrailQuickFactsLowPoint').value;
	if (!isNumber(lowpoint)) {
		sErrors += "<li>Low Point value is not a valid number (should be only numbers and decimals, e.g. \"1234.5\")</li>";
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


/* GOOGLE MAP JAVASCRIPT */

var myGeocoder;
var myMap;
var myMarkers = [];
var cancelSingleClick = false;

//create a class to hold marker data
function CTMarker(gmMarker, markerType, description) {
	this.markerType = markerType;
	this.description = description;
	this.gmMarker = gmMarker;
}

//create the google map given the input values
function initGoogleMap() {
	mapType = document.getElementById('txtEditTrailGoogleMapType').value;
	centerLat = parseFloat(document.getElementById('txtEditTrailGoogleMapCenterLat').value);			
	centerLong = parseFloat(document.getElementById('txtEditTrailGoogleMapCenterLong').value);
	zoom = parseInt(document.getElementById('txtEditTrailGoogleMapZoom').value);
		
	//initial lat/long
	var centerLatLong;
	
	//default is the united states
	if (isNaN(centerLat) || isNaN(centerLong)){
		centerLatLong = new google.maps.LatLng(12.8597277, 3.1938125);
		nZoom = 1;
	}
	else{
		centerLatLong = new google.maps.LatLng(centerLat, centerLong);
	}
			
	//geocoder
	myGeocoder = new google.maps.Geocoder();
	
	//map type
	googleMapType = google.maps.MapTypeId.ROADMAP;
	if (mapType == "terrain")
		googleMapType = google.maps.MapTypeId.TERRAIN;
	if (mapType == "satellite")
		googleMapType = google.maps.MapTypeId.SATELLITE;
	
	//map options
    var myOptions = {
		zoom: zoom,
		center: centerLatLong,
		disableDoubleClickZoom: false,
		streetViewControl: false,
		disableDefaultUI: true,
		zoomControl: true,
		mapTypeControl: true,
		mapTypeId: googleMapType,  
		mapTypeControlOptions: {  
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
			mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.TERRAIN, google.maps.MapTypeId.SATELLITE]
			}  	  
    };
	
	//create the map
    myMap = new google.maps.Map(document.getElementById("divEditTrailGoogleMap"), myOptions);
	
	//assign to the markers this new map
	for (i=0; i<myMarkers.length; i++){
		myMarkers[i].gmMarker.setMap(myMap);
	}
	
	//set up some listeners	
	//map type change
	google.maps.event.addListener(myMap, 'maptypeid_changed', function () {
		var txtType = document.getElementById("txtEditTrailGoogleMapType");
		if (txtType != null)
		{
			if (myMap.getMapTypeId() == google.maps.MapTypeId.ROADMAP)
				txtType.value = 'roadmap';
			if (myMap.getMapTypeId() == google.maps.MapTypeId.TERRAIN)
				txtType.value = 'terrain';
			if (myMap.getMapTypeId() == google.maps.MapTypeId.SATELLITE)
				txtType.value = 'satellite';
				
			changeNoticeUnsavedChanges();
		}
	});
	
	//zoom
	google.maps.event.addListener(myMap, 'zoom_changed', function() {
		var txtZoom = document.getElementById("txtEditTrailGoogleMapZoom");
		if (txtZoom != null)
		{
			txtZoom.value = myMap.getZoom();
			changeNoticeUnsavedChanges();
		}
	});
	
	//map moved
	google.maps.event.addListener(myMap, 'center_changed', function() {
		var txtCenterLat = document.getElementById("txtEditTrailGoogleMapCenterLat");
		var txtCenterLong = document.getElementById("txtEditTrailGoogleMapCenterLong");
		if (txtCenterLat != null && txtCenterLong != null)
		{
			var centerLatLng = myMap.getCenter();
			txtCenterLat.value = Math.round(centerLatLng.lat() * 10000000) / 10000000;
			txtCenterLong.value = Math.round(centerLatLng.lng() * 10000000) / 10000000;
			changeNoticeUnsavedChanges();
		}
	});
	
	//click
	google.maps.event.addListener(myMap, 'click', function(event) {
		setTimeout("showMarkerEditor(" + event.latLng.lat() + ", " + event.latLng.lng() + ")", 500);
		changeNoticeUnsavedChanges();
	});	
	
	//double click
	google.maps.event.addListener(myMap, 'dblclick', function(event) {
		cancelSingleClick = true;
	});
}

function showMarkerEditor(lat, lng) {
	
	existingMarker = findCTMarkerObject(lat, lng);
	
	//update?
	if (existingMarker) {
		$('#spnEditTrailGoogleMapMarkerEditorTitle').html("Editing existing marker");
		$('#btnGoogleMapMarkerEditorDelete').val('Delete Marker');
		$('#btnGoogleMapMarkerEditorDelete').show();
		$('#txtGoogleMapMarkerDescription').val(existingMarker.description);
		$("[name=rbEditTrailGoogleMapMarkerType]").filter("[value="+existingMarker.markerType+"]").attr("checked","checked");
	} else {
		//if this is an add, make sure it's not a double click zoom
		if (cancelSingleClick) {
			cancelSingleClick = false;
			return;
		}
		
		$('#spnEditTrailGoogleMapMarkerEditorTitle').html("Adding marker to Map");
		$('#btnGoogleMapMarkerEditorDelete').hide();
		$('#txtGoogleMapMarkerDescription').val("");
		$("[name=rbEditTrailGoogleMapMarkerType]").filter("[value=trailhead]").attr("checked","checked");
	}
	$('#txtEditTrailGoogleMapMarkerLat').val(lat);
	$('#txtEditTrailGoogleMapMarkerLong').val(lng);
	$('#divEditTrailGoogleMapMarkerEditor').show();
}	

$('#btnGoogleMapMarkerEditorSave').live('click', function() {
	createOrEditMarker($('#txtEditTrailGoogleMapMarkerLat').val(), 
						$('#txtEditTrailGoogleMapMarkerLong').val(),
						$('input[name=rbEditTrailGoogleMapMarkerType]:checked').val(),
						$('#txtGoogleMapMarkerDescription').val());
	$('#divEditTrailGoogleMapMarkerEditor').hide();
	changeNoticeUnsavedChanges();
});

$('#btnGoogleMapMarkerEditorDelete').live('click', function() {
	$('#divEditTrailGoogleMapMarkerEditor').hide();
	marker = findCTMarkerObject($('#txtEditTrailGoogleMapMarkerLat').val(), $('#txtEditTrailGoogleMapMarkerLong').val());
	if (marker)
		marker.gmMarker.setMap(null);
	posToRemove = findCTMarkerPosition($('#txtEditTrailGoogleMapMarkerLat').val(), $('#txtEditTrailGoogleMapMarkerLong').val());
	myMarkers.splice(posToRemove, 1);
	calculateMarkerIcons();
	changeNoticeUnsavedChanges();
});

$('#btnGoogleMapMarkerEditorCancel').live('click', function() {
	$('#divEditTrailGoogleMapMarkerEditor').hide();
});

function findCTMarkerObject(lat, lng) {
	for (i=0; i<myMarkers.length; i++) {
		marker = myMarkers[i];
		if (marker.gmMarker.getPosition().lat() == lat && marker.gmMarker.getPosition().lng() == lng)
			return marker;
	}
	
	return false;
}

//return a CTMarker object's position in the array (do this instead of the object for a needed splice)
function findCTMarkerPosition(lat, lng) {
	for (i=0; i<myMarkers.length; i++) {
		marker = myMarkers[i];
		if (marker.gmMarker.getPosition().lat() == lat && marker.gmMarker.getPosition().lng() == lng)
			return i;
	}
	
	return 0;
}


function findCTMarkerTypePosition(ctMarker) {
	position = 1;
	for (i=0; i<myMarkers.length; i++) {
		marker = myMarkers[i];
		if (marker.gmMarker.getPosition().lat() == ctMarker.gmMarker.getPosition().lat() && 
			marker.gmMarker.getPosition().lng() == ctMarker.gmMarker.getPosition().lng())
			break;
		if (marker.markerType == ctMarker.markerType)
			position++;
	}
	return position;
}

function calculateMarkerIcons(){
	trailhead = 1;
	poi = 1;
	trailend = 1;
	for (i=0; i<myMarkers.length; i++) {
		marker = myMarkers[i];
		iconPath = "http://clevertrail.com/images/icons/googlemaps/";
		
		if (marker.markerType == "Trailhead"){
			iconPath += marker.markerType + trailhead + ".png";
			trailhead++;
		}
		if (marker.markerType == "Poi"){
			iconPath += marker.markerType + poi + ".png";
			poi++;
		}
		if (marker.markerType == "Trailend"){
			iconPath += marker.markerType + trailend + ".png";
			trailend++;
		}
		
		marker.gmMarker.setIcon(iconPath);
	}
}


//put the trailhead first, then poi, then trailend
//also remove markers that are duplicates of any others
function organizeMarkers() {
	var arOrdered = [];	
	for (i = 0; i < 3; i++) {
		markerType = "Trailhead";
		if (i == 1)
			markerType = "Poi";
		if (i == 2)
			markerType = "Trailend";
		
		for (j=0; j<myMarkers.length; j++){
			marker = myMarkers[j];
			if (marker.markerType == markerType)
				arOrdered.push(marker);
		}
	}
	
	//remove duplicates
	for (i=0; i<arOrdered.length; i++){
		firstMarker = arOrdered[i].gmMarker;
		
		for (j=i+1; j<arOrdered.length; j++){
			secondMarker = arOrdered[j].gmMarker;
			
			if (firstMarker.getPosition().lat() == secondMarker.getPosition().lat() &&
				firstMarker.getPosition().lng() == secondMarker.getPosition().lng()) {
			
				arOrdered.splice(j, 1);
				j--;
			}
		}
	}	
	
	myMarkers = arOrdered;
}

function createOrEditMarker(lat, lng, markerType, description) {
	latLong = new google.maps.LatLng(lat, lng);
	
	if (latLong.lat() == "" || latLong.lng() == "" || isNaN(latLong.lat()) || isNaN(latLong.lng()))
		return;
	
	//are we editing or creating?
	existingMarker = findCTMarkerObject(lat, lng);
	iconPath = "http://clevertrail.com/images/icons/googlemaps/";		
		
	if (existingMarker) {
		if (existingMarker.markerType != markerType) {
			existingMarker.markerType = markerType;
			posToRemove = findCTMarkerPosition(lat, lng);			
			myMarkers.splice(posToRemove, 1);
			myMarkers.push(existingMarker);
			calculateMarkerIcons();
		}
		else {
			iconPos = findCTMarkerTypePosition(existingMarker);
			iconPath += existingMarker.markerType + iconPos + ".png";
			existingMarker.gmMarker.setIcon(iconPath);
		}
		
		existingMarker.description = description;
		existingMarker.gmMarker.setTitle(description);
		
	} else {		
		//create the google map marker
		gmMarker = createGMMarker(lat, lng, description, "");
		//create the new clevertrail marker and save it to the array
		var ctMarker = new CTMarker(gmMarker, markerType, description);
		myMarkers.push(ctMarker);
		
		iconPos = findCTMarkerTypePosition(ctMarker);
		iconPath += ctMarker.markerType + iconPos + ".png";
		
		gmMarker.setIcon(iconPath);		
	}
}

function createGMMarker(lat, lng, description, icon){
	markerLatLng = new google.maps.LatLng(lat, lng);
	
	//create the new google map marker
	var gmMarker = new google.maps.Marker({
		position: markerLatLng, 
		map: myMap,
		draggable: true,
		title: description,
		flat: true
	});	
	
	if (icon != "")
		gmMarker.setIcon(icon);

	//allow editing of this marker
	google.maps.event.addListener(gmMarker, 'click', function(event) {
		showMarkerEditor(this.getPosition().lat(), this.getPosition().lng());
	});
	
	//show unsaved changes message when moving markers
	google.maps.event.addListener(gmMarker, 'dragend', function(event) {
		changeNoticeUnsavedChanges();
	});	
	
	return gmMarker;
}

function createMarkerData() {
	for (var i=0; i<myMarkers.length; i++) {
		marker = myMarkers[i];
		var newTextbox = $(document.createElement('input'))
			.attr("type", "hidden")
			.attr("name", "txtEditTrailGoogleMapMarkerLats[]")
			.attr("value", marker.gmMarker.getPosition().lat());
		
		newTextbox.appendTo("#divEditTrailSectionMap");
		
		newTextbox = $(document.createElement('input'))
			.attr("type", "hidden")
			.attr("name", "txtEditTrailGoogleMapMarkerLongs[]")
			.attr("value", marker.gmMarker.getPosition().lng());
		
		newTextbox.appendTo("#divEditTrailSectionMap");
		position = findCTMarkerTypePosition(marker);
		newTextbox = $(document.createElement('input'))
			.attr("type", "hidden")
			.attr("name", "txtEditTrailGoogleMapMarkerIcons[]")
			.attr("value", marker.markerType + position + ".png");
		
		newTextbox.appendTo("#divEditTrailSectionMap");
		
		newTextbox = $(document.createElement('input'))
			.attr("type", "hidden")
			.attr("name", "txtEditTrailGoogleMapMarkerTypes[]")
			.attr("value", marker.markerType);
		
		newTextbox.appendTo("#divEditTrailSectionMap");
		
		newTextbox = $(document.createElement('input'))
			.attr("type", "hidden")
			.attr("name", "txtEditTrailGoogleMapMarkerDescriptions[]")
			.attr("value", marker.description);
		
		newTextbox.appendTo("#divEditTrailSectionMap");
	}	
}

//use google's geocoder to search for an input address or zipcode
function searchForAddress() {
    var address = document.getElementById("txtEditTrailGoogleMapSearch").value;
    myGeocoder.geocode( { 'address': address}, function(results, status) {
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


//begin: jquery effects

//fade in the section being clicked and fade out the current one
$('#btnEditTrailSectionLinkQuickFacts').live('click', function() {
	$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
	$('#divEditTrailSectionQuickFacts').fadeIn(400, function() {$('#txtEditTrailQuickFactsImage').focus();});
	$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
	$('#divEditTrailHelpfulTipsQuickFacts').fadeIn(400, function() {});
	$('#txtEditTrailSectionNumber').val(-1);
	$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
	$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
});

$('#btnEditTrailSectionLinkMap').live('click', function() {
	$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
	$('#divEditTrailSectionMap').fadeIn(400, function() {});
	$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
	$('#divEditTrailHelpfulTipsMap').fadeIn(400, function() {});		
	$('#txtEditTrailSectionNumber').val(9);
	$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
	$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
	
	//init the google map	
	initGoogleMap();
});	

$('#btnEditTrailSectionLinkOverview').live('click', function() {
	$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
	$('#divEditTrailSectionOverview').fadeIn(400, function() {$('#txtEditTrailOverview').focus();});
	$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
	$('#divEditTrailHelpfulTipsTextInput').fadeIn(400, function() {});
	$('#txtEditTrailSectionNumber').val(1);
	$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
	$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
});	

$('#btnEditTrailSectionLinkDirections').live('click', function() {
	$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
	$('#divEditTrailSectionDirections').fadeIn(400, function() {$('#txtEditTrailDirections').focus();});
	$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
	$('#divEditTrailHelpfulTipsTextInput').fadeIn(400, function() {});
	$('#txtEditTrailSectionNumber').val(2);
	$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
	$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
});	

$('#btnEditTrailSectionLinkDescription').live('click', function() {
	$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
	$('#divEditTrailSectionDescription').fadeIn(400, function() {$('#txtEditTrailDescription').focus();});
	$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
	$('#divEditTrailHelpfulTipsTextInput').fadeIn(400, function() {});
	$('#txtEditTrailSectionNumber').val(3);
	$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
	$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
});

$('#btnEditTrailSectionLinkConditions').live('click', function() {
	$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
	$('#divEditTrailSectionConditions').fadeIn(400, function() {$('#txtEditTrailConditions').focus();});
	$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
	$('#divEditTrailHelpfulTipsTextInput').fadeIn(400, function() {});
	$('#txtEditTrailSectionNumber').val(4);
	$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
	$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
});	

$('#btnEditTrailSectionLinkFees').live('click', function() {
	$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
	$('#divEditTrailSectionFees').fadeIn(400, function() {$('#txtEditTrailFees').focus();});
	$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
	$('#divEditTrailHelpfulTipsTextInput').fadeIn(400, function() {});
	$('#txtEditTrailSectionNumber').val(5);
	$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
	$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
});

$('#btnEditTrailSectionLinkAmenities').live('click', function() {
	$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
	$('#divEditTrailSectionAmenities').fadeIn(400, function() {$('#txtEditTrailAmenities').focus();});
	$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
	$('#divEditTrailHelpfulTipsTextInput').fadeIn(400, function() {});
	$('#txtEditTrailSectionNumber').val(6);
	$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
	$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
});

$('#btnEditTrailSectionLinkMisc').live('click', function() {
	$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
	$('#divEditTrailSectionMisc').fadeIn(400, function() {$('#txtEditTrailMisc').focus();});
	$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
	$('#divEditTrailHelpfulTipsTextInput').fadeIn(400, function() {});
	$('#txtEditTrailSectionNumber').val(7);
	$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
	$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
});

$('#btnEditTrailSectionLinkPhotoGallery').live('click', function() {
	$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
	$('#divEditTrailSectionPhotoGallery').fadeIn(400, function() {$('#txtEditTrailSectionPhotoGalleryAddImage').focus();});
	$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
	$('#divEditTrailHelpfulTipsPhotoGallery').fadeIn(400, function() {});
	$('#txtEditTrailSectionNumber').val(8);
	$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
	$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
});

$('#btnEditTrailSectionLinkCategories').live('click', function() {
	$('.clsEditTrailSectionContent').fadeOut(0, function() {});		
	$('#divEditTrailSectionCategories').fadeIn(400, function() {$('#txtEditTrailSectionCategoriesAdd').focus();});
	$('.clsEditTrailHelpfulTips').fadeOut(0, function() {});		
	$('#divEditTrailHelpfulTipsCategories').fadeIn(400, function() {});
	$('#txtEditTrailSectionNumber').val(-3);
	$('.clsEditTrailSectionLinkButtonSelected').attr('class', 'clsEditTrailSectionLinkButton');
	$(this).attr('class', 'clsEditTrailSectionLinkButtonSelected');
});	
		
//toggle enable/disabled between the best months radiobuttons
$('#rbEditTrailQuickFactsBestMonthsMonths').live('click', function() {
	$('#selEditTrailQuickFactsBestMonthsBegin').attr('disabled', false);
	$('#selEditTrailQuickFactsBestMonthsEnd').attr('disabled', false);
});
$('#rbEditTrailQuickFactsBestMonthsYearround').live('click', function() {
	$('#selEditTrailQuickFactsBestMonthsBegin').attr('disabled', true);
	$('#selEditTrailQuickFactsBestMonthsEnd').attr('disabled', true);
});

//toggle buttons onmouseover
$('#btnEditTrailSaveGoToTrail').live('mouseout', function() {
	$(this).css('background', '#777');
});
$('#btnEditTrailSaveGoToTrail').live('mouseover', function() {
	$(this).css('background', '#642200');
});	
$('#btnEditTrailSaveContinue').live('mouseout', function() {
	$(this).css('background', '#777');
});
$('#btnEditTrailSaveContinue').live('mouseover', function() {
	$(this).css('background', '#642200');
});	
$('#btnEditTrailCancel').live('mouseout', function() {
	$(this).css('background', '#777');
});
$('#btnEditTrailCancel').live('mouseover', function() {
	$(this).css('background', '#642200');
});	

//toggle disambiguation base text area
$('#chkEditTrailAddDisambiguation').live('click', function() {
	$('#divDisambiguationBase').toggle();
});

//change colors of trail search box when entering/leaving
$('#txtEditTrailGoogleMapSearch').live('focus', function () {
	if ($(this).val() == "search for a map location or lat/long") {
		$(this).val("");
		$(this).css("color", "#333");
	}
});
//end: jquery effects