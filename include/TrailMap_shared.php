<?php
/* Shared functions through the CleverTrail website */
/* Copyright CleverTrial.com and Dylan Reid 2011 */

include_once("CleverTrailArticle.php");

function drawTrailMap($prefix, $pageName = ''){
	global $wgPathOfWiki;
	
$output = '
	<div id="div' . $prefix . 'TrailMap" class="clsTrailMap"></div>
	<div id="div' . $prefix . 'TrailMapLoading" class="clsTrailMapLoading">
		<table width="100%" height="100%"><tr><td align="center" valign="middle">
		Populating Trail Map<br>
		<img src="http://clevertrail.com/images/load_large.gif"></td></tr></table>
	</div>
	<br>
	<div id="div' . $prefix . 'SearchMap" class="clsSearchTrailMap">
		<table border=0 cellspacing=0 cellpadding=0><tr><td valign=top>
			<input type="text" id="txtSearchMap" value="search for a map location or lat/long"
			onkeypress="searchForLocationIfEnter(event);">
			</td><td>
			<input type="button" id="btnSearchMap" value="" title="Search for a map location or latitude/longitude">			
			</td></tr>
		</table>			
	</div>		
	<div id="divFilter' . $prefix . 'TrailMap" class="clsFilterTrailMap">
		<table>
			
			<tr><td>Time</td><td>
			<input id="txtFilterTimeMin" type="text" style="width:92px"> to 
			<input id="txtFilterTimeMax" type="text" style="width:92px"> ';
			$output .= '<select id="selFilterTimeUnits" name="selFilterTimeUnits">';
			$output .= CleverTrailArticle::drawUnitsOptions('time', 'hours');
			$output .= '</select>		
			</td></tr>
			
			<tr><td>Distance</td><td>
			<input id="txtFilterDistanceMin" type="text" style="width:92px"> to
			<input id="txtFilterDistanceMax" type="text" style="width:92px"> ';
			$output .= '<select id="selFilterDistanceUnits" name="selFilterDistanceUnits">';
			$output .= CleverTrailArticle::drawUnitsOptions('miles', 'miles');
			$output .= '</select>';
			$output .= '
			</td></tr>
			
			<tr><td>Difficulty</td><td>
			<select style="width: 145px" id="selFilterDifficultyMin" name="selFilterDifficultyMin">';
			$output .= CleverTrailArticle::drawTrailDifficultyOptions();
			$output .= '</select> to 
			<select style="width: 145px" id="selFilterDifficultyMax" name="selFilterDifficultyMax">';
			$output .= CleverTrailArticle::drawTrailDifficultyOptions();
			$output .= '</select>
			</td></tr>
			
			<tr><td>Trail Type</td><td>
			<select style="width:145px" id="selFilterTrailType" name="selFilterTrailType">';
			$output .= CleverTrailArticle::drawTrailTypeOptions();
			$output .= '</select>
			</td></tr>			
						
			<tr><td>Trail Use</td><td>';		

			$arTrailUse = array("Hike", "Bicycle", "Climb", "Handicap", "Horse", "Camp", "Swim", "Dog", "Fish", "Family");
			$nItems = 0;
			foreach ($arTrailUse as $use) {				
				$output .= '<input type="checkbox" name="chkFilterTrailUse[]" id="chkFilterTrailUse[]" value="' . $use . '">';								
				$output .= '<img class="clsTrailUseImage" src="http://clevertrail.com/images/icons/trailuse_' . strtolower($use) . '.png" width=20 title="' . getTrailUseAltText($use) . '" alt="' . getTrailUseAltText($use) . '">'; 
				$nItems++;
				if ($nItems % 5 == 0)
					$output .= '<br>';
				else
					$output .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			
			$output .= '</td></tr>
			<tr><td colspan=2>
				<table width=100%><td align=left>
					<input type="checkbox" name="chkFilterIncludeNoData" id="chkFilterIncludeNoData" checked> 
					Include Trails With Incomplete Data 
					<a style="color: white" target="_blank" href="' . $wgPathOfWiki . '/CleverTrail:Finding_Trails">[?]</a>
				</td><td align=right>
					<div id="divFilterClearInput">
					Reset
					</div>					
				</td></tr></table>
			</td></tr>
		</table>
		
	</div>
	<br>
	<div id="divFilter' . $prefix . 'TrailMapExpand" class="clsFilterTrailMapExpand">
	Show Trail Map Filter <img src="http://clevertrail.com/images/icons/icon-down-brown25.png">
	</div>	
	<script>window.onload= new function() { prefix = "' . $prefix . '"; pageName = "' . $pageName . '"; createTrailMap(); createTrailMapMarkers(); } </script>
	';
	
	return $output;
}

function getTrailUseAltText($use){
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

function createTrailUses($bHike,$bBicycle,$bHandicap,$bSwim,$bClimb,$bHorse,$bDog,$bCamp,$bFamily,$bFish){
	global $wgServer;
	$uses = '';
	if ($bHike ==1)
		$uses .= '<img src="http://clevertrail.com/images/icons/trailuse_hike.png" title="Hiking Trail"> ';
	if ($bBicycle ==1)
		$uses .= '<img src="http://clevertrail.com/images/icons/trailuse_bicycle.png" title="Biking Trail"> ';
	if ($bHandicap ==1)
		$uses .= '<img src="http://clevertrail.com/images/icons/trailuse_handicap.png" title="Handicap Access"> ';
	if ($bSwim ==1)
		$uses .= '<img src="http://clevertrail.com/images/icons/trailuse_swim.png" title="Swimming Available"> ';
	if ($bClimb ==1)
		$uses .= '<img src="http://clevertrail.com/images/icons/trailuse_climb.png" title="Mountain Climbing"> ';
	if ($bHorse ==1)
		$uses .= '<img src="http://clevertrail.com/images/icons/trailuse_horse.png" title="Horses Allowed"> ';
	if ($bDog ==1)
		$uses .= '<img src="http://clevertrail.com/images/icons/trailuse_dog.png" title="Dogs Allowed"> ';
	if ($bCamp ==1)
		$uses .= '<img src="http://clevertrail.com/images/icons/trailuse_camp.png" title="Camping Available"> ';
	if ($bFamily ==1)
		$uses .= '<img src="http://clevertrail.com/images/icons/trailuse_family.png" title="Family Friendly"> ';
	if ($bFish ==1)
		$uses .= '<img src="http://clevertrail.com/images/icons/trailuse_fish.png" title="Fishing Available"> ';
	
	return $uses;
}

function getTrailMapMarkerBaseWhereClause($postedVars){
$whereClause = 'WHERE TRUE';

$bIncludeNoData = true;
if (isset($postedVars['includeNoData'])) {
	if ($postedVars['includeNoData'] == "false")
		$bIncludeNoData = false;
}

$distanceMin = 0;
$distanceMax = 0;
if (isset($postedVars['distanceMin'])) 
	$distanceMin = $postedVars['distanceMin'];
	
if (isset($postedVars['distanceMax'])) 
	$distanceMax = $postedVars['distanceMax'];	

if ($distanceMin != 0) {
	if ($distanceMax != 0) {
		//anywhere in the range
		$whereClause .= " AND (($distanceMin <= dDistance) AND ($distanceMax >= dDistance) ";
	} else {
		//just min needs to be higher
		$whereClause .= " AND (($distanceMin <= dDistance)";
	}
	if ($bIncludeNoData)
			$whereClause .= " OR (dDistance = ''))";
		else
			$whereClause .= " AND (dDistance != ''))";	
} elseif ($distanceMax != 0) {
	//just max needs to be lower
	$whereClause .= " AND (($distanceMax >= dDistance)";
	if ($bIncludeNoData)
		$whereClause .= " OR (dDistance = ''))";
	else
		$whereClause .= " AND (dDistance != ''))";
}


$timeMin = 0;
$timeMax = 0;
if (isset($postedVars['timeMin'])) 
	$timeMin = $postedVars['timeMin'];
	
if (isset($postedVars['timeMax'])) 
	$timeMax = $postedVars['timeMax'];	

if ($timeMin != 0) {
	if ($timeMax != 0) {
		//anywhere in the range
		$whereClause .= " AND (($timeMin <= dTimeMax) AND ($timeMax >= dTimeMin) ";
		if ($bIncludeNoData)
			$whereClause .= " OR (dTimeMin = '') OR (dTimeMax = ''))";
		else
			$whereClause .= " AND (dTimeMin != '') AND (dTimeMax != ''))";
	} else {
		//just min needs to be higher
		$whereClause .= " AND (($timeMin <= dTimeMax)";
		if ($bIncludeNoData)
			$whereClause .= " OR (dTimeMin = ''))";
		else
			$whereClause .= " AND (dTimeMin != ''))";
	}
	
} elseif ($timeMax != 0) {
	//just max needs to be lower
	$whereClause .= " AND (($timeMax >= dTimeMin)";
	if ($bIncludeNoData)
		$whereClause .= " OR (dTimeMax = ''))";
	else
		$whereClause .= " AND (dTimeMax != ''))";
}

	
$difficultyMin = 0;
$difficultyMax = 0;
if (isset($postedVars['difficultyMin'])) 
	$difficultyMin = $postedVars['difficultyMin'];
	
if (isset($postedVars['difficultyMax'])) 
	$difficultyMax = $postedVars['difficultyMax'];	

if ($difficultyMin != 0) {
	if ($difficultyMax != 0) {
		//anywhere in the range
		$whereClause .= " AND (($difficultyMin <= nDifficulty) AND ($difficultyMax >= nDifficulty) ";
	} else {
		//just min needs to be higher
		$whereClause .= " AND (($difficultyMin <= nDifficulty)";
	}
	if ($bIncludeNoData)
			$whereClause .= " OR (nDifficulty = ''))";
		else
			$whereClause .= " AND (nDifficulty != ''))";	
} elseif ($difficultyMax != 0) {
	//just max needs to be lower
	$whereClause .= " AND (($difficultyMax >= nDifficulty)";
	if ($bIncludeNoData)
		$whereClause .= " OR (nDifficulty = ''))";
	else
		$whereClause .= " AND (nDifficulty != ''))";
}

if (isset($postedVars['trailType'])) {
	$trailType = $postedVars['trailType'];
	if ($trailType != "" && $trailType != 0) {
		$arTrailType = CleverTrailArticle::getTrailTypeArray();
		if ($trailType < sizeof($arTrailType)){			
			$trailTypeName = $arTrailType[$trailType];
			
			$whereClause .= " AND (('$trailTypeName' = sTrailType)";
			if ($bIncludeNoData)
				$whereClause .= " OR (sTrailType = ''))";
			else
				$whereClause .= " AND (sTrailType != ''))";
		}
	}
}

$trailUse = array();
if (isset($postedVars['trailuse']))
	$trailUse = explode(',', $postedVars['trailuse']);
	
if (sizeof($trailUse) > 1) {
	$whereClause .= ' AND ( ';
	$bFirst = true;
	foreach($trailUse as $use) {
		if ($use == "")
			continue;
			
		if ($bFirst)
			$bFirst = false;
		else
			$whereClause .= ' and ';
		$whereClause .= 'b'.$use.' = 1';
	}
	$whereClause .= ' )';
}

return $whereClause;
}

function getTrailMapMarkers($query){
	global $wgPathOfWiki;
	
	$res = ExecQuery($query);
	
	$markers = array();

	while ($line = mysql_fetch_assoc($res)) {

		$lat = $line['numLat'];
		$long = $line['numLong'];
		$title = $line['strTrail'];
		$timerequired = $line['sTimeRequired'];
		$difficulty = CleverTrailArticle::getTrailDifficultyString($line['nDifficulty']);
		$distance = trim($line['sDistance']);
		$trailType = trim($line['sTrailType']);
		$avg = $line['avg'];
		$sImage = $line['sImage'];
		
		$modifiedTitle = $title;
		if ($difficulty && $timerequired) {
			$modifiedTitle .= " ($difficulty, $timerequired)";
		} elseif ($difficulty) {
			$modifiedTitle .= " ($difficulty)";
		} elseif ($timerequired) {
			$modifiedTitle .= " ($timerequired)";
		}
		
		$trailUses = createTrailUses($line['bHike'],$line['bBicycle'],$line['bHandicap'],$line['bSwim'],$line['bClimb'],$line['bHorse'],$line['bDog'],$line['bCamp'],$line['bFamily'],$line['bFish']);
		
		$styleString = "font-family: trebuchet ms; border: 1px solid black; margin-top: 8px; background: #E9F0EC; padding: 5px;";
		
		$contentString = "";
		
		$contentString .= '<a style="text-decoration: underline" href="' . $wgPathOfWiki . '/' . $title . '"><b>' . $title . '</b></a> ';
		$contentString .= '&nbsp;<a target="_blank" href="' . $wgPathOfWiki . "/" . $title . '"><img title="Open In New Tab" src="http://clevertrail.com/images/icons/external.png"></a><br>';

		$contentString .= '<table width=100%><tr><td valign=top>';
		
		if ($difficulty)
			$contentString .= "<b>Difficulty:</b> $difficulty<br>";
		if ($distance)
			$contentString .= "<b>Distance:</b> $distance<br>";
		if ($timerequired)
			$contentString .= "<b>Time Required:</b> $timerequired<br>";
		if ($trailType)
			$contentString .= "<b>Trail Type:</b> $trailType<br>";
		if ($avg)
			$contentString .= "<b>Average User Rating:</b> $avg%<br>";	
		if ($trailUses)
			$contentString .= "<b>Trail Uses:</b> $trailUses<br>";
		
		$contentString .= '</td><td style="text-align:center"><center>';
		$imagePath = getImagePath($sImage, 120);
		$contentString .= '<a style="text-decoration: underline" href="' . $wgPathOfWiki . '/' . $title . '"><img src="' . $imagePath . '"></a>';
		$contentString .= '</center></td></tr>';
		$contentString .= '</table>';
		$markers[] = array($lat, $long, $modifiedTitle, $contentString, $styleString);
	}
	
	return $markers;
}