<?php

//valid web entry point
define('MEDIAWIKI', true);

//Copyright CleverTrail.com and Dylan Reid 
include_once("../include/globals.php");
include_once("../include/CleverTrailArticle.php");

$response = array();
$name = "";
if (isset($_GET['name']))
	$name = str_replace(" ", "_", mysql_real_escape_string(strtoupper($_GET['name'])));
	
function createJSONResponseFromCTA($name, $cta){
	$response = array();
	if ($cta){
	
		$referencedPhotos = createReferencedPhotos($cta);
		$galleryPhotos = createGalleryPhotos($cta);
		
		$sImage = getImagePath($cta->sImage, 300);
		$sImageCredit = $cta->sImageCredit;
		$sDifficulty = $cta->getDifficultyWikitext();
		$sDistance = $cta->getDistanceWikitext();
		$sTime = $cta->getTimeRequiredWikitext();
		$sTrailType = $cta->getTrailTypeWikitext();
		$sElevation = $cta->getElevationGainWikitext();
		$sHighpoint = $cta->getHighPointWikitext();
		$sLowpoint = $cta->getLowPointWikitext();		
		$trailUse = $cta->getTrailUseWikitext();
		$sBestMonth = $cta->getBestMonthWikitext();		
		$sNearestCity = $cta->sNearestCity;
		
		$response = array ( 'name'=>$name
				, 'image' => $sImage
				, 'imagecredit' => $sImageCredit
				, 'difficulty' => $sDifficulty
				, 'distance' => $sDistance
				, 'time' => $sTime
				, 'trailuse' => $trailUse
				, 'trailtype' => $sTrailType
				, 'elevation' => $sElevation
				, 'highpoint' => $sHighpoint
				, 'lowpoint' => $sLowpoint
				, 'bestmonth' => $sBestMonth
				, 'nearestcity' => $sNearestCity
				, 'overview' => convertClevertextToHTML($cta->sOverview)
				, 'directions' => convertClevertextToHTML($cta->sDirections)
				, 'description' => convertClevertextToHTML($cta->sDescription)
				, 'conditions' => convertClevertextToHTML($cta->sConditions)
				, 'fees' => convertClevertextToHTML($cta->sFees)
				, 'amenities' => convertClevertextToHTML($cta->sAmenities)
				, 'misc' => convertClevertextToHTML($cta->sMisc)
				, 'referencedphotos' => $referencedPhotos
				, 'galleryphotos' => $galleryPhotos
				);
	}
	
	return $response;
}

function createGalleryPhotos($cta){
	$photos = array();
	for ($i = 0; $i < sizeof($cta->arPhotos); $i++){		
		$photoPath120 = getImagePath($cta->arPhotos[$i], 120);				
		$photoPath = getImagePath($cta->arPhotos[$i]);				
		$photo = array();
		$photo[] = $photoPath;
		$photo[] = $photoPath120;
		$photos[] = $photo;
	}
	
	return $photos;
}

//go through each section and get the referenced photos, then remove the referenced photos from the text itself
function createReferencedPhotos(CleverTrailArticle& $cta) {
	$photos = array();
	
	//Overview
	$sectionText = $cta->sOverview;
	preg_match_all("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", $sectionText, $data);		
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "overview"));
	
	preg_match_all("/(\[\[Photo:)(.*?)(\]\])/", $sectionText, $data);
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "overview"));
	$cta->sOverview = $sectionText;
	
	//Directions
	$sectionText = $cta->sDirections;
	preg_match_all("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", $sectionText, $data);		
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "directions"));
	
	preg_match_all("/(\[\[Photo:)(.*?)(\]\])/", $sectionText, $data);
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "directions"));
	$cta->sDirections = $sectionText;
	
	//Description
	$sectionText = $cta->sDescription;
	preg_match_all("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", $sectionText, $data);		
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "description"));
	
	preg_match_all("/(\[\[Photo:)(.*?)(\]\])/", $sectionText, $data);
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "description"));
	$cta->sDescription = $sectionText;
	
	//Conditions
	$sectionText = $cta->sConditions;
	preg_match_all("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", $sectionText, $data);		
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "conditions"));
	
	preg_match_all("/(\[\[Photo:)(.*?)(\]\])/", $sectionText, $data);
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "conditions"));
	$cta->sConditions = $sectionText;
	
	//Fees
	$sectionText = $cta->sFees;
	preg_match_all("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", $sectionText, $data);		
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "fees"));
	
	preg_match_all("/(\[\[Photo:)(.*?)(\]\])/", $sectionText, $data);
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "fees"));
	$cta->sFees = $sectionText;
	
	//Amenities
	$sectionText = $cta->sAmenities;
	preg_match_all("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", $sectionText, $data);		
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "amenities"));
	
	preg_match_all("/(\[\[Photo:)(.*?)(\]\])/", $sectionText, $data);
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "amenities"));
	$cta->sAmenities = $sectionText;
	
	//Misc
	$sectionText = $cta->sMisc;
	preg_match_all("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", $sectionText, $data);		
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\|)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "misc"));
	
	preg_match_all("/(\[\[Photo:)(.*?)(\]\])/", $sectionText, $data);
	$sectionText = preg_replace("/(\[\[Photo:)(.*?)(\]\])/", "", $sectionText);	
	$photos = array_merge($photos, createReferencedPhotosFromSection($data, "misc"));
	$cta->sMisc = $sectionText;
	
	return $photos;
}

//create an array of [photo url/caption]s from a text section
function createReferencedPhotosFromSection($data, $section){
	$photos = array();

	if (sizeof($data) > 4 && sizeof($data[2]) > 0 && sizeof($data[2]) == sizeof($data[4])){			
		for ($i = 0; $i < sizeof($data[1]); $i++){
			$photoPath120 = getImagePath($data[2][$i], 120);				
			$photoPath = getImagePath($data[2][$i]);				
			$photoCaption = $data[4][$i];
			$photo = array();
			$photo[] = $photoPath;
			$photo[] = $photoPath120;
			$photo[] = $photoCaption;
			$photo[] = $section;
			$photos[] = $photo;
		}
	} elseif (sizeof($data) > 2 && sizeof($data[2]) > 0){			
		for ($i = 0; $i < sizeof($data[1]); $i++){
			$photoPath = getImagePath($data[2][$i], 120);				
			$photoCaption = "";
			$photo = array();
			$photo[] = $photoPath;
			$photo[] = $photoCaption;
			$photo[] = $section;
			$photos[] = $photo;
		}
	}
				
	return $photos;
}

function convertClevertextToHTML($data) {
	//make sure the user doesn't add a new main section ("==") or ("="), replace them with subsections ("===")
	//add a carriage return (which will be stripped out later) for helping with the regex
	$data = "\r" . $data . "\r";				
	
	//$1: a carriage return or linebreak
	//$2: followed by 1 or 2 "="
	//$3: followed by a non "=" and any number of other characters (i.e. the section name)
	//$4: followed by 1 or 2 "="
	//$5: followed by any amount of whitespace
	//$6: followed by a return or linebreak
	$data = preg_replace("/([\n|\r])([=]{2})([^=].*?)([=]{2})([\s]*?)([\n|\r])/", "$1<b><u>$3</b></u>$5$6", $data);		
	$data = preg_replace("/([\n|\r])([=]{1})([^=].*?)([=]{1})([\s]*?)([\n|\r])/", "$1<b><u>$3</b></u>$5$6", $data);	
	
	//special way to break being a line with just 3 or more "="
	$data = preg_replace("/([\n|\r])([=]{3,})([\n|\r])/", "$1$3", $data);	
	
	//replace our special tag [[Category:National Park]] with the wiki syntax: [[:Category:National Park|National Park]]
	$data = preg_replace("/(\[\[Category:)(.*?)(\]\])/", "$2", $data);	
	
	//links [[National Park]] with just the text
	$data = preg_replace("/(\[\[)(.*?)(\]\])/", "$2", $data);	
	
	//strip out '''bold'''
	$data = preg_replace("/(''')(.*?)(''')/", "$2", $data);	
	
	//strip out ''italic''
	$data = preg_replace("/('')(.*?)('')/", "$2", $data);	
	
	//remove any whitespace from the front of newlines		
	$data = preg_replace("/([\n|\r]+)([\s]+)(.+?)/", "$1$3", $data);
	
	//smooth out too many line breaks
	$data = preg_replace("/(\n+)/", "\n", $data);
	
	//make any newline a double newline
	$data = preg_replace("/([\n|\r])/", "$1$1", $data);
	
	//strip out <!--xxx-->
	$data = preg_replace("/\<!--(.*?)--\>/", "", $data);	
	
	//strip out <!---xxx--->
	$data = preg_replace("/\<!---(.*?)---\>/", "", $data);	
	
	//trim
	$data = trim($data);
		
	return $data;
}
	
if ($name != "") {

	//get the trail article
	$query = "select text.old_text from text " .
	"inner join revision on revision.rev_text_id = text.old_id " .
	"inner join page on page.page_latest = revision.rev_id " .
	"WHERE UPPER(CONVERT(BINARY page_title USING latin1)) = '$name' and rev_deleted = 0 and page.page_namespace = 0";
	
	$res = ExecQuery($query);
	if ($line = mysql_fetch_assoc($res)){
		$text = $line['old_text'];
		
		if ($text != "") {
			$cta = CleverTrailArticle::createCleverTrailArticleFromText($text);
			if ($cta && $cta->sArticleType == "Trail"){
				$response = createJSONResponseFromCTA($_GET['name'], $cta);
			}
		}
	}	
}


 
echo json_encode ( $response ) ;

?>
