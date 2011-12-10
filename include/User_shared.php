<?php
/* Shared functions through the CleverTrail website */
/* Copyright CleverTrial.com and Dylan Reid 2011 */

//returns the number of finished trails for the user
function getNumberOfFinishedTrails($userName){
	$numTrailsFinished = 0;
	
	if ($userName){
		$userNameSafe = mysql_real_escape_string($userName);
		$query = "select count(*) as numTrails from trails_finished where sUser = '$userNameSafe' AND bDeleted = 0";																					
		$result = ExecQuery($query);

		if ($line = mysql_fetch_assoc($result)){
			$numTrailsFinished = $line['numTrails'];	
		}
	}
	
	return $numTrailsFinished;
}

//draws the hiking title and info
function drawHikerTitle($numTrails, $nIconSize, $bIncludeHelpLink, $bIncludeText, $bIncludeTrailCountAndQuestionMark) {
	global $wgPathOfWiki;
	$output = "";
	$nImageNumber = 1;
	$sTitle = "No Title";
	$sTitleWithRange = "No Title";
	
	if ($numTrails == 0) {
		$nImageNumber = 1;
		$sTitle = "No Title";
		$sTitleWithRange = "No Title";
	} elseif ($numTrails < 10) {
		$nImageNumber = 2;
		$sTitle = "Wanderer";
		$sTitleWithRange = "Wanderer (1-9 trails)";
	} elseif ($numTrails < 25) {
		$nImageNumber = 3;
		$sTitle = "Trekker";
		$sTitleWithRange = "Trekker (10-24 trails)";
	} elseif ($numTrails < 50) {
		$nImageNumber = 4;
		$sTitle = "Wayfarer";
		$sTitleWithRange = "Wayfarer (25-49 trails)";
	} elseif ($numTrails < 100) {
		$nImageNumber = 5;
		$sTitle = "Adventurer";
		$sTitleWithRange = "Adventurer (50-99 trails)";
	} elseif ($numTrails < 200) {
		$nImageNumber = 6;
		$sTitle = "Explorer";
		$sTitleWithRange = "Explorer (100-199 trails)";
	} else {
		$nImageNumber = 7;
		$sTitle = "Trailblazer";
		$sTitleWithRange = "Trailblazer (200+ trails)";
	}
	
	$output = '<img src="http://clevertrail.com/images/icons/titles/HikerTitle' . $nIconSize . '_' . $nImageNumber . '.png" title="' . $sTitleWithRange . '">';

	if ($bIncludeHelpLink){
		$output = '<a href="' . $wgPathOfWiki . '/CleverTrail:User_Accounts#Hiking_Title">' . $output . '</a>';
	}
	
	if ($bIncludeText){
		$output .= '<span style="font-size:75%"> ' . $sTitle . ' </span>';
	}
	if ($bIncludeTrailCountAndQuestionMark){
		$output .= '<span style="font-size:50%">(' . $numTrails . ' trails completed) 
		<a href="' . $wgPathOfWiki . '/CleverTrail:User_Accounts#Hiking_Title">[?]</a></span>';
	}
	
	return $output;
}