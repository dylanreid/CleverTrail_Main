<?php

//a wrapper class to hold all the info for a trail article
class CleverTrailArticle
{
	public static function drawTrailDifficultyOptions($nSelected = 0){
		$output = "";
		$arTrailDifficulty = CleverTrailArticle::getTrailDifficultyArray();
		$nItems = 0;
		foreach ($arTrailDifficulty as $difficulty) {				
			$output .= '<option value="' . $nItems . '" ';
			if ($nSelected == $nItems) 
				$output .= ' selected';
			$output .= '>' . $difficulty . '</option>';
			$nItems++;
		}
		return $output;
	}
	
	public static function drawTrailTypeOptions($nSelected = 0){
		$output = "";
		$arTrailType = CleverTrailArticle::getTrailTypeArray();
		$nItems = 0;
		foreach ($arTrailType as $type) {				
			$output .= '<option value="' . $nItems . '" ';
			if ($nSelected == $nItems) 
				$output .= ' selected';
			$output .= '>' . $type . '</option>';
			$nItems++;
		}
		return $output;
	}
	
	public static function drawUnitsOptions($type, $selected) {
		$text = '';
		
		if ($type == 'feet') {
			$text .= '<option value="feet"';
			if ($selected == "feet") $text .= ' selected ';
			$text .= '>feet</option>';
			$text .= '<option value="meters"';
			if ($selected == "meters") $text .= ' selected ';
			$text .= '>meters</option>';
		}
		
		if ($type == 'miles') {
			$text .= '<option value="miles"';
			if ($selected == "miles") $text .= ' selected ';
			$text .= '>miles</option>';
			$text .= '<option value="kilometers"';
			if ($selected == "kilometers") $text .= ' selected ';
			$text .= '>kilometers</option>';
		}
		
		if ($type == 'time') {
			$text .= '<option value="minutes"';
			if ($selected == "minutes") $text .= ' selected ';
			$text .= '>minutes</option>';
			$text .= '<option value="hours"';
			if ($selected == "hours") $text .= ' selected ';
			$text .= '>hours</option>';
			$text .= '<option value="days"';
			if ($selected == "days") $text .= ' selected ';
			$text .= '>days</option>';
		}
		
		return $text;
	}
	
	
	public static function getTrailDifficultyArray()
	{
		return array("", "Easy", "Easy/Moderate", "Moderate", "Moderate/Strenuous", "Strenuous", "Strenuous/Extreme", "Extreme");
	}
	
	public static function getTrailDifficultyValue($difficulty){
		switch($difficulty){
			case "Easy": return 1;
			case "Easy/Moderate": return 2;
			case "Moderate": return 3;
			case "Moderate/Strenuous": return 4;
			case "Strenuous": return 5;
			case "Strenuous/Extreme": return 6;
			case "Extreme": return 7;
			default: return 0;
		}
	}
	
	public static function getTrailDifficultyString($nDifficulty){
		$ar = CleverTrailArticle::getTrailDifficultyArray();
		$s = "";
		if ($nDifficulty < sizeof($ar)){
			$s = $ar[$nDifficulty];
		}
		return $s;
	}
	
	public static function getTrailTypeArray()
	{
		return array("", "There-And-Back", "Loop", "One Way", "Multiple Routes");
	}
	
	public static function getMonthsArray()
	{
		return array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	}
	
	public static function getTrailUseArray()
	{
		return array("Hike", "Bicycle", "Climb", "Handicap", "Horse", "Camp", "Swim", "Dog", "Fish", "Family");
	}
	
	public static function getTrailUseAltText($use){
		switch ($use){
			case "Hike": return "Hiking Trail";
			case "Bicycle": return "Bicycle Trail";
			case "Climb": return "Mountain Climbing";
			case "Handicap": return "Handicap Access";
			case "Horse": return "Horses Allowed";
			case "Camp": return "Camping Available";
			case "Swim": return "Swimming Nearby";
			case "Dog": return "Dogs Allowed";
			case "Fish": return "Fishing Available";
			case "Family": return "Family Friendly";
		}
		return "";
	}
	
	public static function getBestMonthFromNumber($nMonth)
	{
		$sMonth = "";
		switch($nMonth){
			case 0: $sMonth = ""; break;
			case 1: $sMonth = "January"; break;
			case 2: $sMonth = "February"; break;
			case 3: $sMonth = "March"; break;
			case 4: $sMonth = "April"; break;
			case 5: $sMonth = "May"; break;
			case 6: $sMonth = "June"; break;
			case 7: $sMonth = "July"; break;
			case 8: $sMonth = "August"; break;
			case 9: $sMonth = "September"; break;
			case 10: $sMonth = "October"; break;
			case 11: $sMonth = "November"; break;
			case 12: $sMonth = "December"; break;
			default: $sMonth = "January"; break;
		}
		
		return $sMonth;
	}
	
	public static function getBestMonthFromName($sMonth)
	{
		$nMonth = 0;
		switch($sMonth){
			case "" : $nMonth = 0; break;
			case "January": $nMonth = 1; break;
			case "February": $nMonth = 2; break;
			case "March": $nMonth = 3; break;
			case "April": $nMonth = 4; break;
			case "May": $nMonth = 5; break;
			case "June": $nMonth = 6; break;
			case "July": $nMonth = 7; break;
			case "August": $nMonth = 8; break;
			case "September": $nMonth = 9; break;
			case "October": $nMonth = 10; break;
			case "November": $nMonth = 11; break;
			case "December": $nMonth = 12; break;
			default: $nMonth = 0; break;
		}
		
		return $nMonth;
	}
	
	public $sArticleType = "Trail";
	
	public $sTitle = "";
	public $sImage = "";
	public $sImageCredit = "";
	public $bImageValid = true;
	public $nDifficulty = 0;
	public $sDistanceUnits = "";
	public $dDistanceValue = "";
	public $dTimeRequiredMinValue = 0;
	public $dTimeRequiredMaxValue = 0;
	public $sTimeRequiredUnits = "";
	public $arTrailUse = array();
	public $nTrailType = 0;
	public $dElevationGainValue = "";
	public $sElevationGainUnits = "";
	public $dHighPointValue = "";
	public $sHighPointUnits = "";
	public $dLowPointValue = "";
	public $sLowPointUnits = "";
	public $nBestMonthBegin = 0;
	public $nBestMonthEnd = 0;
	public $sNearestCity = "";
	public $dLatitude = "";
	public $dLongitude = "";
	//default united states
	public $dGoogleMapCenterLat = 12.8597277;
	public $dGoogleMapCenterLong = 3.1938125;
	public $sGoogleMapType = "roadmap";
	public $nGoogleMapZoom = 1;
	public $arGoogleMapMarkerLats = array();
	public $arGoogleMapMarkerLongs = array();
	public $arGoogleMapMarkerIcons = array();
	public $arGoogleMapMarkerDescriptions = array();
	public $arGoogleMapMarkerTypes = array();
	
	public $sOverview = "";
	public $sDirections = "";
	public $sDescription = "";
	public $sConditions = "";
	public $sFees = "";
	public $sAmenities = "";
	public $sMisc = "";
	
	public $arPhotos = array();
	public $arCategories = array();
	
	public $sRedirect = "";
	public $sDisambiguation = "";
	public $sDisambiguationBase = "";
	public $bDisambiguationNote = false;
	
	public function getMainImageWikitext(){
		if ($this->sImage == "" || $this->bImageValid == false)
			return "Nophotoavailable.jpg";
		else
			return $this->sImage;
	}
	
	public function getMainImageCreditWikitext(){
		if ($this->sImageCredit == "")
			return "";
		else
			return "Photo: " . $this->sImageCredit;
	}
	
	public function getTrailUseWikitext(){
		$text = "";
		if (sizeof($this->arTrailUse) <= 0)
			return $text;
			
		foreach ($this->arTrailUse as $use)
		{
			$text .= "{{TrailUse_$use}} ";			
		}
		return $text;
	}
	
	public function getDifficultyWikitext(){
		$ctTrailDifficulty = CleverTrailArticle::getTrailDifficultyArray();
		return $ctTrailDifficulty[$this->nDifficulty];
	}
	
	public function getDistanceWikitext(){
		if ($this->dDistanceValue == "")
			return "";
		else {
			if ($this->sDistanceUnits == 'miles') {
				$mi = round($this->dDistanceValue, 2);
				$km = round($mi / 0.621, 1);
				return $mi . " " . $this->sDistanceUnits . " (" . $km . " kilometers)";
			}
			if ($this->sDistanceUnits == 'kilometers') {
				$km = round($this->dDistanceValue, 2);
				$mi = round($km * 0.621, 1);
				return $km . " " . $this->sDistanceUnits . " (" . $mi . " miles)";
			}
		}
	}
	
	public function getTimeRequiredWikitext(){
		if ($this->dTimeRequiredMinValue == 0 && $this->dTimeRequiredMaxValue == 0)
			return "";
		else {
			if ($this->dTimeRequiredMaxValue == 0) {
				return $this->dTimeRequiredMinValue . " " . $this->sTimeRequiredUnits;
			} else if ($this->dTimeRequiredMinValue == 0) {
				return $this->dTimeRequiredMaxValue . " " . $this->sTimeRequiredUnits;
			} else {			
				return $this->dTimeRequiredMinValue . "-" . $this->dTimeRequiredMaxValue . " " . $this->sTimeRequiredUnits;
			}			
		}		
	}
	
	public function getElevationGainWikitext(){
		if ($this->dElevationGainValue == "")
			return "";
		else {
			if ($this->sElevationGainUnits == 'feet') {
				$ft = round($this->dElevationGainValue);
				$m = round($ft / 3.28, -1);
				return $ft . " " . $this->sElevationGainUnits . " (" . $m . " meters)";
			}
			if ($this->sElevationGainUnits == 'meters') {
				$m = round($this->dElevationGainValue);
				$ft = round($m * 3.28, -1);
				return $m . " " . $this->sElevationGainUnits . " (" . $ft . " feet)";
			}
		}
	}
	
	public function getHighPointWikitext(){
		if ($this->dHighPointValue == "")
			return "";
		else {
			if ($this->sHighPointUnits == 'feet') {
				$ft = round($this->dHighPointValue);
				$m = round($ft / 3.28, -1);
				return $ft . " " . $this->sHighPointUnits . " (" . $m . " meters)";
			}
			if ($this->sHighPointUnits == 'meters') {
				$m = round($this->dHighPointValue);
				$ft = round($m * 3.28, -1);
				return $m . " " . $this->sHighPointUnits . " (" . $ft . " feet)";
			}
		}
	}

	public function getLowPointWikitext(){
		if ($this->dLowPointValue == "")
			return "";
		else {
			if ($this->sLowPointUnits == 'feet') {
				$ft = round($this->dLowPointValue);
				$m = round($ft / 3.28, -1);
				return $ft . " " . $this->sLowPointUnits . " (" . $m . " meters)";
			}
			if ($this->sLowPointUnits == 'meters') {
				$m = round($this->dLowPointValue);
				$ft = round($m * 3.28, -1);
				return $m . " " . $this->sLowPointUnits . " (" . $ft . " feet)";
			}
		}
	}	
	
	public function setDifficultyValue($str){
		$ctTrailDifficulty = CleverTrailArticle::getTrailDifficultyArray();
		$this->nDifficulty = array_search($str, $ctTrailDifficulty);
	}
	
	public function getTrailTypeWikitext(){
		$ctTrailType = CleverTrailArticle::getTrailTypeArray();
		return $ctTrailType[$this->nTrailType];
	}
	
	public function setTypeValue($str){
		$ctTrailType = CleverTrailArticle::getTrailTypeArray();
		$this->nTrailType = array_search($str, $ctTrailType);
	}
	
	public function getBestMonthBegin(){
		return $this->getBestMonthFromNumber($this->nBestMonthBegin);
	}
	
	public function getBestMonthEnd(){
		return $this->getBestMonthFromNumber($this->nBestMonthEnd);
	}
	
	public function getBestMonthWikitext(){
		$monthBegin = $this->nBestMonthBegin;
		$monthEnd = $this->nBestMonthEnd;
		if ($monthBegin == 1 && $monthEnd == 12)
			return "Year Round";
		
		if ($monthBegin == 0){
			if ($monthEnd == 0)
				return "";
			else
				return $this->getBestMonthFromNumber($monthEnd);
		}
		
		if ($monthEnd == 0)
			return $this->getBestMonthFromNumber($monthBegin);
		
		return $this->getBestMonthFromNumber($monthBegin) . " - " . $this->getBestMonthFromNumber($monthEnd);
	}
		
	public function getGoogleMapWikitext(){
$markersHTML = '';
$output = '';

$output .= '<table id="tblTrailMap"><tr><td>
';
$output .= '{{#display_points:
';
for ($i=0; $i < sizeof($this->arGoogleMapMarkerLats) && $i < sizeof($this->arGoogleMapMarkerLongs); $i++) {
$lat = $this->arGoogleMapMarkerLats[$i];
$lng = $this->arGoogleMapMarkerLongs[$i];
$icon = $this->arGoogleMapMarkerIcons[$i];
$type = $this->arGoogleMapMarkerTypes[$i];
if ($type == "Trailhead")
	$type = "Trailhead";
if ($type == "Poi")
	$type = "Point Of Interest";
if ($type == "Trailend")
	$type = "Trail End";

$markersHTML .= '<b>' . $type . '</b> [[File:' . $icon . '|]] (' . round($lat, 6) . ', ' . round($lng, 6) . ')';

$desc = trim($this->arGoogleMapMarkerDescriptions[$i]);
$desc = preg_replace("/\n/s", " ", $desc);
$desc = preg_replace("/\r/s", " ", $desc);
$desc = preg_replace("/~/s", "-", $desc);
$desc = preg_replace("/;/s", ",", $desc);
$desc = preg_replace("/=/s", ":", $desc);
$desc = preg_replace("/\|/s", " ", $desc);
if ($desc != "") {
	$markersHTML .= ' : ' . $desc;
} else {
	$desc = " ";
}
$markersHTML .= '<BR>';

$output .= $lat . ',' . $lng . '~~' . $desc . '~File:' . $icon;
if ($i+1 < sizeof($this->arGoogleMapMarkerLats)) $output .= ';';

$output .= '
';
}

$output .= '| center=' . trim($this->dGoogleMapCenterLat) . ', ' . trim($this->dGoogleMapCenterLong) . '
| zoom=' . trim($this->nGoogleMapZoom) . '
| type=' . trim($this->sGoogleMapType) . '
| types=roadmap, satellite, terrain
| controls=zoom, type
| zoomstyle=large
| typestyle=dropdown
| height=400
}}
</td></tr>
<tr><td class="tdTrailMap">
';

$output .= $markersHTML;
$output .= '</td></tr></table>';
		
return $output;
	}
	
	public function setBestMonthBegin($sMonth){
		$nMonth = $this->getBestMonthFromName($sMonth);
		$this->nBestMonthBegin = $nMonth;
	}
	
	public function setBestMonthEnd($sMonth){
		$nMonth = $this->getBestMonthFromName($sMonth);	
		$this->nBestMonthEnd = $nMonth;
	}
	
	public function getEmptySectionText() {
		//if you change this, change it in extension also (meta stuff)
		return '<span class="clsEmptySectionText">This section is empty, help contribute by clicking the [edit] link!</span>';
	}
	
	public function printInfo()
	{
				
		$output = "";
		
		$output .= "ArticleType = " . $this->sArticleType . "<BR>";
		$output .= "Redirect = " . $this->sRedirect . "<BR>";
		$output .= "Disambiguation = " . $this->sDisambiguation . "<BR>";
		
		$output .= "Title = " . $this->sTitle . "<BR>"
		. "Image = " . $this->sImage . "<BR>"
		. "Difficulty = " . $this->getDifficultyWikitext() . "<BR>"
		. "Distance = " . $this->getDistanceWikitext() . "<BR>"
		. "TimeRequired = " . $this->getTimeRequiredWikitext() . "<BR>"
		. "Trail Type = " . $this->getTrailTypeWikitext() . "<BR>";
		
		$output .= "Trail Use = " . print_r($this->arTrailUse, true) . "<BR>";
		
		$output .= "Elevation Gain = " . $this->getElevationGainWikitext() . "<BR>"
		. "High Point = " . $this->getHighPointWikitext() . "<BR>"
		. "Low Point = " . $this->getLowPointWikitext() . "<BR>"
		. "Best Months = " . $this->getBestMonthWikitext() . "<BR>"
		. "Nearest City = " . $this->sNearestCity . "<BR>"
		. "Latitude = " . $this->dLatitude . "<BR>"
		. "Longitude = " . $this->dLongitude . "<BR>"
		. "Google Map Code = " . htmlspecialchars($this->getGoogleMapWikitext()) . "<BR>"
		. "Overview = " . $this->sOverview . "<BR>"
		. "Directions = " . $this->sDirections . "<BR>"
		. "Description = " . $this->sDescription . "<BR>"
		. "Conditions = " . $this->sConditions . "<BR>"
		. "Amenities = " . $this->sAmenities . "<BR>"
		. "Misc = " . $this->sMisc . "<BR>";
		
		$output .= "Photos = " . print_r($this->arPhotos, true) . "<BR>";
		$output .= "Categories = " . print_r($this->arCategories, true);
		
		die($output);
	}
	
	//returns a CleverTrailArticle with all the data from the article for the trail
	public static function createCleverTrailArticleFromText($text)
	{		
		$cta = new CleverTrailArticle();
		
		//is this a redirect?
		//match "#Redirect [[trail]]" with a regex
		preg_match_all("/#REDIRECT \[\[(.*)\]\]/", $text, $redirect);
		if (sizeof($redirect[1]) == 1) {
			$cta->sArticleType = "Redirect";
			$cta->sRedirect = $redirect[1][0];
			return $cta;
		}
		
		//otherwise this is a standard trail
		
		//image name
		preg_match_all("/\| ImageName=(.*)/", $text, $data);
		if (sizeof($data[1]) == 1) {
			$cta->sImage = trim($data[1][0]);
			if ($cta->sImage == "Nophotoavailable.jpg")
				$cta->sImage = "";
		}
		
		//image Credit
		preg_match_all("/\| ImageCredit=Photo: (.*)/", $text, $data);
		if (sizeof($data[1]) == 1) {
			$cta->sImageCredit = trim($data[1][0]);
		}
		
		//DifficultyRating
		preg_match_all("/\| DifficultyRating=(.*)/", $text, $data);
		if (sizeof($data[1]) == 1) {
			$cta->setDifficultyValue(trim($data[1][0]));
		}
		
		//Distance
		preg_match_all("/\| Distance=(.*) (.*) \((.*)\)/", $text, $data);
		if (sizeof($data[1]) == 1 && sizeof($data[2]) == 1)	{
			$cta->dDistanceValue = trim($data[1][0]);
			$cta->sDistanceUnits = trim($data[2][0]);
		}
		
		//Time Required (2 values)
		preg_match_all("/\| TimeRequired=(.*)-(.*) (.*)/", $text, $data);
		if (sizeof($data[1]) == 1 && sizeof($data[2]) == 1 && sizeof($data[3]) == 1) {
			$cta->dTimeRequiredMinValue = trim($data[1][0]);
			$cta->dTimeRequiredMaxValue = trim($data[2][0]);
			$cta->sTimeRequiredUnits = trim($data[3][0]);
		}
		else { //Time Required (1 value)
			preg_match_all("/\| TimeRequired=(.*) (.*)/", $text, $data);
			if (sizeof($data[1]) == 1 && sizeof($data[2]) == 1) {
				$cta->dTimeRequiredMinValue = trim($data[1][0]);
				$cta->sTimeRequiredUnits = trim($data[2][0]);
			}
		}
		
		//TrailUse
		preg_match_all("/\| TrailUse=(.*)/", $text, $data);
		if (sizeof($data[1]) == 1) {
			$arTrailUseText = explode(" ", trim($data[1][0]));
			$arTrailUse = CleverTrailArticle::getTrailUseArray();
			foreach ($arTrailUse as $use) {						
				if (in_array("{{TrailUse_$use}}", $arTrailUseText))					
					$cta->arTrailUse[] = $use;
			}
		}
		
		//TrailType
		preg_match_all("/\| TrailType=(.*)/", $text, $data);
		if (sizeof($data[1]) == 1) {
			$cta->setTypeValue(trim($data[1][0]));
		}
		
		
		//ElevationGain
		preg_match_all("/\| ElevationGain=(.*) (.*) \((.*)\)/", $text, $data);
		if (sizeof($data[1]) == 1 && sizeof($data[2]) == 1)	{
			$cta->dElevationGainValue = trim($data[1][0]);
			$cta->sElevationGainUnits = trim($data[2][0]);
		}
		
		//HighPoint
		preg_match_all("/\| HighPoint=(.*) (.*) \((.*)\)/", $text, $data);
		if (sizeof($data[1]) == 1 && sizeof($data[2]) == 1)	{
			$cta->dHighPointValue = trim($data[1][0]);
			$cta->sHighPointUnits = trim($data[2][0]);
		}
		
		//LowPoint
		preg_match_all("/\| LowPoint=(.*) (.*) \((.*)\)/", $text, $data);
		if (sizeof($data[1]) == 1 && sizeof($data[2]) == 1) {
			$cta->dLowPointValue = trim($data[1][0]);
			$cta->sLowPointUnits = trim($data[2][0]);
		}
		
		$cta->nBestMonthBegin = 0;
		$cta->nAvailbleMonthEnd = 0;
		//BestMonths (months)
		preg_match_all("/\| BestMonths=(.*) - (.*)/", $text, $data);
		if (sizeof($data[1]) == 1 && sizeof($data[2]) == 1)	{			
			$cta->setBestMonthBegin(trim($data[1][0]));				
			$cta->setBestMonthEnd(trim($data[2][0]));
		}
		
		//BestMonths (year round)
		preg_match_all("/\| BestMonths=(.*)Year Round(.*)/", $text, $data);
		if (sizeof($data[0]) == 1) {			
			$cta->nBestMonthBegin = 1;
			$cta->nBestMonthEnd = 12;
		}
		
		//Nearest City
		preg_match_all("/\| NearestCity=(.*)/", $text, $data);
		if (sizeof($data[1]) == 1) {
			$cta->sNearestCity = trim($data[1][0]);
		}
		
		//google map markers
		preg_match_all("/\{\{#display_points:\n(.*?)\n\|/s", $text, $markerData);
		if (sizeof($markerData[1]) == 1) {
			$markers = explode(";", trim($markerData[1][0]));
			
			foreach($markers as $marker) {
				$markerParts = explode("~", $marker);
				
				$latLng = explode(",", $markerParts[0]);
				
				$cta->arGoogleMapMarkerLats[] = $latLng[0];
				$cta->arGoogleMapMarkerLongs[] = $latLng[1];
				
				//$title = $markerParts[1];			
				$description = trim($markerParts[2]);
				$description = preg_replace('/"/s', '\"', $description);
				$cta->arGoogleMapMarkerDescriptions[] = $description;
				
				$icon = preg_replace("/(File:)(.*?)/", "$2", $markerParts[3]);
				
				if (strstr($icon, "Poi"))
					$cta->arGoogleMapMarkerTypes[] = "Poi";
				elseif (strstr($icon, "Trailend"))
					$cta->arGoogleMapMarkerTypes[] = "Trailend";				
				else
					$cta->arGoogleMapMarkerTypes[] = "Trailhead";

					
				$iconPath = "http://clevertrail.com/images/icons/googlemaps/$icon";
				$cta->arGoogleMapMarkerIcons[] = $iconPath;
			}
		}
			
		//google map center
		preg_match_all("/\{\{#display_points:(.*)\| center=(.*?), (.*?)\n/s", $text, $centerData);
		if (sizeof($centerData[2]) == 1 && sizeof($centerData[3]) == 1) {
			$cta->dGoogleMapCenterLat = trim($centerData[2][0]);
			$cta->dGoogleMapCenterLong = trim($centerData[3][0]);
		}
		
		//google map zoom
		preg_match_all("/\{\{#display_points:(.*)\| zoom=(.*?)\n/s", $text, $zoomData);
		if (sizeof($zoomData[2]) == 1) {
			$cta->nGoogleMapZoom = trim($zoomData[2][0]);
		}
		
		//google map type
		preg_match_all("/\{\{#display_points:(.*)\| type=(.*?)\n/s", $text, $typeData);
		if (sizeof($typeData[2]) == 1) {
			$cta->sGoogleMapType = trim($typeData[2][0]);
		}
		
						
		//Is there a disambiguation notice?
		//Match: {{DisambiguationNote | BaseName=XXX}}
		preg_match_all("/{{DisambiguationNote \| BaseName=(.*?)}}/", $text, $redirect);
		if (sizeof($redirect[1]) == 1) {
			$cta->sDisambiguationBase = $redirect[1][0];
			$cta->bDisambiguationNote = true;
		}
		
		//Overview
		preg_match_all("/== Overview ==(.*)<!--END:OVERVIEW-->/s", $text, $data);
		if (sizeof($data[1]) == 1) {
			$cta->sOverview = CleverTrailArticle::convertWikitextToClevertext(trim($data[1][0]));
			if ($cta->sOverview == $cta->getEmptySectionText())
				$cta->sOverview = "";
		}		
		
		//Directions
		preg_match_all("/== Directions To Trailhead ==(.*)<!--END:DIRECTIONS-->/s", $text, $data);
		if (sizeof($data[1]) == 1) {
			$cta->sDirections = CleverTrailArticle::convertWikitextToClevertext(trim($data[1][0]));
			if ($cta->sDirections == $cta->getEmptySectionText())
				$cta->sDirections = "";
		}	
		
		//Descriptions
		preg_match_all("/== Trail Description ==(.*)<!--END:DESCRIPTION-->/s", $text, $data);
		if (sizeof($data[1]) == 1) {
			$cta->sDescription = CleverTrailArticle::convertWikitextToClevertext(trim($data[1][0]));
			if ($cta->sDescription == $cta->getEmptySectionText())
				$cta->sDescription = "";
		}
		
		//Conditions
		preg_match_all("/== Conditions And Hazards ==(.*)<!--END:CONDITIONS-->/s", $text, $data);
		if (sizeof($data[1]) == 1) {
			$cta->sConditions = CleverTrailArticle::convertWikitextToClevertext(trim($data[1][0]));
			if ($cta->sConditions == $cta->getEmptySectionText())
				$cta->sConditions = "";
		}
		
		//Fees
		preg_match_all("/== Fees, Permits, And Restrictions ==(.*)<!--END:FEES-->/s", $text, $data);
		if (sizeof($data[1]) == 1) {
			$cta->sFees = CleverTrailArticle::convertWikitextToClevertext(trim($data[1][0]));
			if ($cta->sFees == $cta->getEmptySectionText())
				$cta->sFees = "";
		}
		
		//Amenities
		preg_match_all("/== Amenities ==(.*)<!--END:AMENITIES-->/s", $text, $data);
		if (sizeof($data[1]) == 1) {
			$cta->sAmenities = CleverTrailArticle::convertWikitextToClevertext(trim($data[1][0]));
			if ($cta->sAmenities == $cta->getEmptySectionText())
				$cta->sAmenities = "";
		}
		
		//Misc
		preg_match_all("/== Miscellaneous ==(.*)<!--END:MISCELLANEOUS-->/s", $text, $data);
		if (sizeof($data[1]) == 1) {
			$cta->sMisc = CleverTrailArticle::convertWikitextToClevertext(trim($data[1][0]));
			if ($cta->sMisc == $cta->getEmptySectionText())
				$cta->sMisc = "";
		}
		
		//Photo Gallery
		preg_match_all("/\<gallery\>(.*)\<\/gallery\>/s", $text, $data);
		if (sizeof($data[1]) == 1) {
			$photoGallery = trim($data[1][0]);
			
			//match all the Image:imagename.ext images with a regex
			preg_match_all("/Image:(.*)/", $photoGallery, $images);
			$cta->arPhotos = $images[1];
		}
		
		//Categories
		preg_match_all("/\[\[Category:(.*)\]\]/", $text, $data);
		$cta->arCategories = $data[1];
				
		return $cta;			
	}
	
	public static function convertWikitextToClevertext($text){
	
		//replace wikitext "[[File:image_name.jpg|thumb|left|caption]]" with our special tag "[[Photo:photo.jpg|caption]]"
		$text = preg_replace("/(\[\[File:)(.*?)(\|thumb\|left\|)(.*?)(\]\])/", "[[Photo:$2|$4]]", $text);
		$text = preg_replace("/(\[\[File:)(.*?)(\|thumb\|left)(.*?)(\]\])/", "[[Photo:$2]]", $text);
		
		//replace wikitext [[:Category:National Park|National Park]] with our special tag [[Category:National Park]]
		$text = preg_replace("/(\[\[:Category:)(.*?)\|(.*?)(\]\])/", "[[Category:$2]]", $text);
		
		return $text;
	}
}
?>