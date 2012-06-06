<?php
if( !defined( 'MEDIAWIKI' ) ){
        die( "This is not a valid entry point.\n" );
}

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'CleverTrailBase',
	'version' => '1.3',
	'author' => '[http://www.twitter.com/dylankreid Dylan Reid]',
	'description' => 'Extension for the CleverTrail website',
);

include_once("../include/globals.php");
global $wgMainDirectory;
include_once("$wgMainDirectory/include/TrailMap_shared.php");	
include_once("$wgMainDirectory/include/CleverTrailArticle.php");

# Define a setup function
$wgHooks['ParserFirstCallInit'][] = 'cleverTrailParserFunctionSetup';
# Add a hook to initialise the magic words
$wgHooks['LanguageGetMagic'][] = 'cleverTrailParserFunctionMagic';
 
function cleverTrailParserFunctionSetup( &$parser ) {
        # Set a function hook associating the "example" magic word with our function
		$parser->setFunctionHook( 'clevertrail_addthis', 'cleverTrail_AddThis' );
		$parser->setFunctionHook( 'clevertrail_categorypagetrailmap', 'clevertrail_CategoryPageTrailMap' );
		$parser->setFunctionHook( 'clevertrail_categorypagediscussion', 'clevertrail_CategoryPageDiscussion' );
		$parser->setFunctionHook( 'clevertrail_userpageautocontent', 'clevertrail_UserPageAutoContent' );
		$parser->setFunctionHook( 'clevertrail_paypalbutton', 'clevertrail_PaypalButton' );
        return true;
}
 
function cleverTrailParserFunctionMagic( &$magicWords, $langCode ) {
        # Add the magic word
        # The first array element is whether to be case sensitive, in this case (0) it is not case sensitive, 1 would be sensitive
        # All remaining elements are synonyms for our parser function
        $magicWords['clevertrail_addthis'] = array( 0, 'clevertrail_addthis' );
		$magicWords['clevertrail_categorypagetrailmap'] = array( 0, 'clevertrail_categorypagetrailmap' );
		$magicWords['clevertrail_categorypagediscussion'] = array( 0, 'clevertrail_categorypagediscussion' );
		$magicWords['clevertrail_userpageautocontent'] = array( 0, 'clevertrail_userpageautocontent' );
		$magicWords['clevertrail_paypalbutton'] = array( 0, 'clevertrail_paypalbutton' );
        # unless we return true, other parser functions extensions won't get loaded.
        return true;
}
 
//add the AddThis widget
function cleverTrail_AddThis( $parser, $pageTitle = '') {
	global $wgServer, $wgDirectoryOfWiki;
	
	if($pageTitle == '') {
		$pageTitle = $parser->getTitle();
	}
	
	$output = '<!-- AddThis Button BEGIN -->
<script type="text/javascript">
var addthis_config ={ services_exclude:\'print\' }
var addthis_share ={ templates: { twitter: \'Check out this #trail! {{url}} (via @CleverTrail)\' } }
</script>
<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_preferred_4"></a>
<a class="addthis_button_compact"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4eb99cc4577ebac7"></script>
<!-- AddThis Button END -->';

	return array($output, 'noparse' => true, 'isHTML' => true);
}

//add a category trail map
function cleverTrail_CategoryPageTrailMap( $parser, $categoryName) {
	$output = drawTrailMap("Category", $categoryName); //in TrailMap_shared.php
	
	return array($output, 'noparse' => true, 'isHTML' => true);
}

//add a category discussion
function cleverTrail_CategoryPageDiscussion( $parser) {
	$output = "<discussion> 
show_all_order=1
preview=0
quoting=0
</discussion>
	
	";
	return array( $output, 'noparse' => false );
}

//adds some automatic content to the user page
function cleverTrail_UserPageAutoContent( $parser, $userName) {

	$output = drawTrailMap("User", $userName); //in TrailMap_shared.php

	return array($output, 'noparse' => true, 'isHTML' => true);
}

//add a paypal button for donations
function cleverTrail_PaypalButton( $parser) {

	$output = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="SQEXNL5V99KDQ">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" style="border-width:0px" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" border="0">
</form>';			

	return array($output, 'noparse' => true, 'isHTML' => true);
}

//after user adds account, add the user content to their page
$wgHooks['AddNewAccount'][] = 'cleverTrailBaseAddNewAccount';
function cleverTrailBaseAddNewAccount( $user, $byEmail ) {

	$trailTitle = Title::newFromText("User:$user");
	if ($user && $trailTitle) {
		$nTrailId = $trailTitle->getArticleId(); 
		
		$context = new RequestContext;
		$context->setTitle( $trailTitle );
		$trailArticle = Article::newFromTitle($trailTitle, $context);
		
		if ($trailArticle) {
			$cleverTrailBot = User::newFromName("CleverTrailBot");
			$summary = "Creating default user page content for $user";
			$flags = EDIT_NEW & EDIT_FORCE_BOT;
			$newtext = "{{#cleverTrail_UserPageAutoContent:$user}}";
			
			$status = NULL;
			$status = $trailArticle->doEdit($newtext, $summary, $flags, false, $cleverTrailBot);
			if ($status && !$status->isOK()) {
				die($status->getWikitext());
			}
		} else {
			die("Trailarticle, $trailTitle, not created");
		}
	}
	
	return true;
}

//add additional header information (css and script files)
//add meta data to a page after it has been rendered
$wgHooks['BeforePageDisplay'][] = 'cleverTrailBaseBeforePageDisplay';
function cleverTrailBaseBeforePageDisplay(&$out, &$sk) {
	$trailTitle = $out->getTitle();
	$namespace = $trailTitle->getNamespace();
	$server = "http://" . $_SERVER['HTTP_HOST'];
	
	//for some reason we have to add this here...
	$out->addScriptFile("https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js");
	
	if (($namespace == NS_MAIN || $namespace == NS_CATEGORY || $namespace == NS_USER) && $out && $trailTitle){
		if ($trailTitle->isRedirect()) {
			$out->addMeta('ROBOTS', 'NOINDEX');	
		} else {
			$trailTitle = Title::newFromText(ucwords($trailTitle));
			$nTrailId = $trailTitle->getArticleId(); 
			$trailArticle = Article::newFromId($nTrailId);	
			if ($trailArticle) {
				$text = $trailArticle->getRawText();
				
				$sDescription = '';
				$imagePath = '/images/defaultlogo.png';
				$metaKeywords = "";
				$sTitle = $trailTitle;
				
				//trails
				if ($namespace == NS_MAIN) {
					//Overview
					preg_match_all("/== Overview ==(.*)<!--END:OVERVIEW-->/s", $text, $data);
					if (sizeof($data[1]) == 1) {
						$sDescription = trim($data[1][0]);
						
						//parse category and article links in an effort to be prettier
						$sDescription = preg_replace("/(\[\[:Category:)(.*?)\|(.*?)(\]\])/i", "$2", $sDescription);
						$sDescription = preg_replace("/(\[\[:Category:)(.*?)(\]\])/i", "$2", $sDescription);
						$sDescription = preg_replace("/(\[\[)(.*?)(\]\])/", "$2", $sDescription);
					}
					
					$emptyText = '<span class="clsEmptySectionText">This section is empty, help contribute by clicking the [edit] link!</span>';
					if ($sDescription == '' || $sDescription == $emptyText) {
						$sDescription = 'Directions, description, trail information and more for ' . $trailTitle . '.';
					}
				
					//Meta Tag Keywords					
					//TrailUse
					preg_match_all("/\| TrailUse=(.*)/", $text, $data);
					if (sizeof($data[1]) == 1) {
						$arTrailUseText = explode(" ", trim($data[1][0]));
						$arTrailUseBase = array("Hike", "Bicycle", "Climb", "Handicap", "Horse", "Camp", "Swim", "Dog", "Fish", "Family");
						foreach ($arTrailUseBase as $use) {						
							if (in_array("{{TrailUse_$use}}", $arTrailUseText))					
								$metaKeywords .= getKeywordsFromTrailUse($use) . ", ";
						}
					}
					
					//Categories			
					preg_match_all("/\[\[Category:(.*)\]\]/", $text, $data);
					foreach ($data[1] as $category) {						
						$metaKeywords .= $category . ", ";
					}
						
					
					//image name
					$imageName = "";					
					preg_match_all("/\| ImageName=(.*)/", $text, $data);
					if (sizeof($data[1]) == 1) {
						$imageName = trim($data[1][0]);
					}			
					if ($imageName != "" && $imageName != "Nophotoavailable.jpg") {
						$image = wfLocalFile($imageName);
						$imagePath = "";					
						if ($image){
							$imagePath = $image->createThumb(120);
							//this is a little hacky here: the mobile site returns a full url path including clevertrail.com
							//so we will strip that part out if it's included
							$imagePath = preg_replace("/(http:\/\/clevertrail\.com)(.*?)/i", "$2", $imagePath);
						}
					}
						
				}
				
				//categories
				if ($namespace == NS_CATEGORY){					
					$sDescription = 'Hiking, Biking, and Climbing trails grouped under the ' . str_replace('Category:', '', $trailTitle) . ' category.';
					$sTitle = preg_replace("/(Category:)(.*?)/", "$2", $trailTitle);
				}
				
				//user page
				if ($namespace == NS_USER){
					$sDescription = $trailTitle->getBaseText() . "'s User Page that includes trail maps and additional information about him/her.";
					$sTitle = preg_replace("/(User:)(.*?)/", "$2", $trailTitle);
					//commenting out: anyway to pic a good photo? right now it picks a trailuse photo
					//if (preg_match("/\[\[File:(.*)/", $text) > 0)
					//	$imagePath = false;
				}
				
				$out->addMetaProperty('og:title', $sTitle . ' on CleverTrail');
				$out->addMetaProperty('og:type', 'article');
				$out->addMetaProperty('og:url', 'http://clevertrail.com/'.str_replace(' ', '_', $trailTitle));
				if ($imagePath)
					$out->addMetaProperty('og:image', 'http://clevertrail.com'.$imagePath);
				$out->addMetaProperty('og:site_name', 'CleverTrail');
				$out->addMetaProperty('fb:admins', '1217047');			
				$out->addMetaProperty('og:locale', 'en_US');		
				$out->addMetaProperty('og:description', $sDescription);
				$out->addMeta('DESCRIPTION', $sDescription);		
				$out->addMeta('KEYWORDS', $metaKeywords);	
			}
		}
	}
	
	return true;
}

function getKeywordsFromTrailUse($use){
	switch ($use){
		case "Hike": return "Hike, Hiking Trail";
		case "Bicycle": return "Bicycle, Biking Trail";
		case "Climb": return "Mountain Climbing";
		case "Handicap": return "Handicap Access, Wheelchair Access";
		case "Horse": return "Horses, Horse Riding";
		case "Camp": return "Camping";
		case "Swim": return "Swimming, Lakes";
		case "Dog": return "Dogs, Pets";
		case "Fish": return "Fishing";
		case "Family": return "Family Friendly, Kid Friendly";
	}
	return "";
}


//add categories to exact matches in search
$wgHooks['SearchGetNearMatch'][] = 'cleverTrailBaseSearchGetNearMatch';
function cleverTrailBaseSearchGetNearMatch( $term, &$title ) {
	$title = Title::newFromText( "Category:" . ucwords($term) );
	if ( $title && $title->exists() ) {
		return false;
	}
	return true;
}

//add a map of trails to categories
$wgHooks['ArticleAfterFetchContent'][]  = 'cleverTrailBaseArticleAfterFetchContent';  
function cleverTrailBaseArticleAfterFetchContent( &$article, &$content) {
	global $wgTitle;
	
	//is this a category page?
	if ($wgTitle->getNamespace() == NS_CATEGORY && $wgTitle->getDBkey() != "") {
	
		$content = preg_replace("/(\{\{#cleverTrail_CategoryPageTrailMap:.*?)\n/i", "", $content);
		//notice the 2 extra \n for the extra line breaks when we actually assign $content below
		$content = preg_replace("/(\{\{#cleverTrail_CategoryPageDiscussion:\}\})\n/i", "", $content);

		$content = "{{#cleverTrail_CategoryPageTrailMap:" . $wgTitle->getDBkey() . "}} 
{{#cleverTrail_CategoryPageDiscussion:}}
" . $content;

/*
	if (preg_match("/\{\{#cleverTrail_CategoryPageDiscussion:/", $content) == 0) {
		$content = "{{#cleverTrail_CategoryPageDiscussion:" . $wgTitle->getDBkey() . "}}
" . $content;	
		}	

	if (preg_match("/\{\{#cleverTrail_CategoryPageTrailMap:/", $content) == 0) {
		$content = "{{#cleverTrail_CategoryPageTrailMap:" . $wgTitle->getDBkey() . "}}
" . $content;	*/
//		}

		
	}
	
	//is this a user page?
	if ($wgTitle->getNamespace() == NS_USER && $wgTitle->getDBkey() != "") {
	
		if (preg_match("/\{\{#cleverTrail_UserPageAutoContent:/", $content) == 0) {
			$content = "{{#cleverTrail_UserPageAutoContent:" . $wgTitle->getDBkey() . "}}
" . $content;	
		}
	}
	
	return true;
}


//capitalize the first letter of each word to streamline searching for and creating articles
//do not allow the user to go to the "Main_Page" - instead, redirect to home page
//redirect "ct.com/mobile" to "m.ct"
$wgHooks['ArticleFromTitle'][]  = 'cleverTrailBaseArticleFromTitle';  
function cleverTrailBaseArticleFromTitle(&$title, &$article ) {
	global $wgServer, $wgDirectoryOfWiki;	
	
	//redirect if the title isn't capitalized
	if ($title->getNamespace() == NS_MAIN && $title != ucwords($title)){
		$title = Title::newFromText(ucwords($title));
		$titleWithUnderscores = $title->getDBkey();
		header("Location: $wgDirectoryOfWiki/" . $titleWithUnderscores);		
		exit;
	}
	
	//if this is the main page, redirect to home page
	$titleName = $title->getBaseText();
	if (strtolower($titleName) == "main page"){			
		header("Location: $wgServer");		
		exit;
	}
	
	//if the address is ct.com/mobile, go to the mobile site
	if (strtolower($titleName) == "mobile"){			
		header("Location: http://m.clevertrail.com");		
		exit;
	}
	
	$title->invalidateCache();
	
	return true;
}

//after saving article : update the markers
$wgHooks['ArticleSaveComplete'][] = 'cleverTrailBaseArticleSaveComplete';
function cleverTrailBaseArticleSaveComplete( &$article, &$user, $text, $summary,
										 $minoredit, $watchthis, $sectionanchor, &$flags, $revision, &$status, $baseRevId, &$redirect=NULL ) {
	
	$title = $article->getTitle();
	//if this is not in the main namespace, don't do anything extra here
	if ($title->getNamespace() != NS_MAIN)
		return true;
	
	updateGoogleMapsMarkersInDatabase($title, $text);
	
	return true;
}

//after reverting an article: update the markers
$wgHooks['ArticleRevisionUndeleted'][] = 'cleverTrailBaseArticleRevisionUndeleted';
function cleverTrailBaseArticleRevisionUndeleted( $title, $revision, $oldPageID ) {
	
	//if this is not in the main namespace, don't do anything extra here
	$title = Title::newFromText(ucwords($title));
	if ($title->getNamespace() != NS_MAIN)
		return true;
	
	//"" indicates we are undeleting it
	updateFinishedTrailsTable("", $title);
	
	updateGoogleMapsMarkersInDatabase($title, $revision->getText());
	
	return true;

}

//after deleting an article: update the markers
$wgHooks['ArticleDeleteComplete'][] = 'cleverTrailBaseArticleDeleteComplete';
function cleverTrailBaseArticleDeleteComplete( &$article, User &$user, $reason, $id ) {
	
	$title = $article->getTitle();
	//if this is not in the main namespace, don't do anything extra here
	if ($title->getNamespace() != NS_MAIN)
		return true;
	
	//"" indicates we are deleting it
	updateFinishedTrailsTable($title, "");
	
	updateGoogleMapsMarkersInDatabase($title, "");
	
	return true;
}


//update the google maps markers, create thumbs for the new name if it's a file
$wgHooks['TitleMoveComplete'][] = 'cleverTrailBaseTitleMoveComplete';
function cleverTrailBaseTitleMoveComplete( Title &$title, Title &$newtitle, User &$user, $oldid, $newid ) {
	
	if ($title->getNamespace() == NS_MAIN) {
		$title = Title::newFromText(ucwords($title));		
		updateGoogleMapsMarkersInDatabase($title, "");		
				
		$nTrailId = $newtitle->getArticleId(); 
		$trailArticle = Article::newFromId($nTrailId);	
		$text = $trailArticle->getRawText();
		
		updateFinishedTrailsTable($title, $newtitle);
		
		updateGoogleMapsMarkersInDatabase($newtitle, $text);
	}
	
	//if we're moving a file to a new name, make the new thumbs
	if ($title->getNamespace() == NS_FILE) {
		$image = wfLocalFile( $newtitle );
		if ($image) {
			$image->createThumb(120);
			$image->createThumb(300);
		}
	}
	
	return true;	
}


//this function updates the table trails_finished for the new title
function updateFinishedTrailsTable($oldtitle, $newtitle){
	$query = "";
	$oldNameSafe = mysql_real_escape_string($oldtitle);
	$newNameSafe = mysql_real_escape_string($newtitle);
	
	//indicates we are undeleting a trail
	if ($oldtitle == ""){
		if ($newtitle == "") return;
		$query = "update trails_finished set bDeleted = 0 where sTrail = '$newNameSafe'";
	}
	//indicates we are deleting a trail
	else if ($newtitle == ""){		
		if ($oldtitle == "") return;
		$query = "update trails_finished set bDeleted = 1 where sTrail = '$oldNameSafe'";
	
	}
	//indicates we are moving a trail name
	else {
		$query = "update trails_finished set sTrail = '$newNameSafe' where sTrail = '$oldNameSafe'";
	}
	
	ExecQuery($query);
}

//this function saves the google map marker data to the database
function updateGoogleMapsMarkersInDatabase($trailTitle, $text) {		
	$arThisTrailUse = array();
	$arLats = array();
	$arLongs = array();
	$sDistance = "";
	$dDistance = "";
	$difficulty = "";
	$timeMin = "";
	$timeMax = "";
	$timerequired = "";
	$sImage = "";
	$trailType = "";
	$sNearestCity = "";
	
	//image name
	$sImage == "Nophotoavailable.jpg";
	preg_match_all("/\| ImageName=(.*)/", $text, $data);
	if (sizeof($data[1]) == 1) {
		$sImage = trim($data[1][0]);
	}
	
	//nearest city
	preg_match_all("/\| NearestCity=(.*)/", $text, $data);
	if (sizeof($data[1]) == 1) {
		$sNearestCity = trim($data[1][0]);
	}
	
	//find the trail use 
	preg_match_all("/\| TrailUse=(.*)/", $text, $data);
	if (sizeof($data[1]) == 1) {
		$arTrailUseText = explode(" ", trim($data[1][0]));
		$arBaseTrailUse = array("Hike", "Bicycle", "Climb", "Handicap", "Horse", "Camp", "Swim", "Dog", "Fish", "Family");
		foreach ($arBaseTrailUse as $use) {						
			if (in_array("{{TrailUse_$use}}", $arTrailUseText))					
				$arThisTrailUse[] = $use;
		}
	}
	
	//TrailType
	preg_match_all("/\| TrailType=(.*)/", $text, $data);
	if (sizeof($data[1]) == 1) {
		$trailType = trim($data[1][0]);
	}
	
	//DifficultyRating
	preg_match_all("/\| DifficultyRating=(.*)/", $text, $data);
	if (sizeof($data[1]) == 1) {
		$difficulty = CleverTrailArticle::getTrailDifficultyValue(trim($data[1][0]));
	}
	
	//Distance
	preg_match_all("/\| Distance=(.*) (.*) \((.*)\)/", $text, $data);
	if (sizeof($data[1]) == 1 && sizeof($data[2]) == 1)	{
		$sDistance = trim($data[1][0]) . " " . trim($data[2][0]);
		$dDistance = trim($data[1][0]);
		if (trim($data[2][0]) == "kilometers")
			$dDistance *= 0.6;
	}
	
	//Time Required (2 values)
	preg_match_all("/\| TimeRequired=(.*)-(.*) (.*)/", $text, $data);
	if (sizeof($data[1]) == 1 && sizeof($data[2]) == 1 && sizeof($data[3]) == 1) {
		$timeMin = trim($data[1][0]);
		$timeMax = trim($data[2][0]);
		if (trim($data[3][0]) == "hours"){
			$timeMin *= 60;
			$timeMax *= 60;
		}
		if (trim($data[3][0]) == "days"){
			$timeMin *= 60 * 24;
			$timeMax *= 60 * 24;
		}
		$timerequired = trim($data[1][0]) . "-" . trim($data[2][0]) . " " . trim($data[3][0]);
	}
	elseif (sizeof($data[1]) == 1 && sizeof($data[2]) == 1) { //Time Required (1 value)
		preg_match_all("/\| TimeRequired=(.*) (.*)/", $text, $data);
		$timeMin = trim($data[1][0]);
		$timeMax = trim($data[1][0]);
		if (trim($data[2][0]) == "hours"){
			$timeMin *= 60;
			$timeMax *= 60;
		}
		if (trim($data[2][0]) == "days"){
			$timeMin *= 60 * 24;
			$timeMax *= 60 * 24;
		}
		$timerequired = trim($data[1][0]) . " " . trim($data[2][0]);
	}
	
	//Categories
	preg_match_all("/\[\[Category:(.*)\]\]/", $text, $data);
	$arCategories = $data[1];
	
	//google map trailhead marker
	preg_match_all("/\{\{#display_points:\n(.*?)\n\|/s", $text, $markerData);
	if (sizeof($markerData[1]) == 1) {
		$markers = explode(";", trim($markerData[1][0]));

		foreach($markers as $marker) {
			$markerParts = explode("~", $marker);
			
			$icon = "";
			if (sizeof($markerParts) > 3)
				$icon = preg_replace("/(File:)(.*?)/", "$2", $markerParts[3]);
			
			if (strstr($icon, "Trailhead")) {
			
				$latLng = explode(",", $markerParts[0]);
				
				$arLats[] = $latLng[0];
				$arLongs[] = $latLng[1];
			}
		}
	}
	
	//first we have to delete any entries for this trail since the markers could have moved
	$dbw = wfGetDB( DB_MASTER );
	$dbw->delete( 'trailquickfacts',
		array( /* WHERE */
			'strTrail' => $trailTitle
		), __METHOD__
	);
	
	//go through all the lat longs and insert the entries
	for ($i=0; $i < sizeof($arLats) && $i < sizeof($arLongs); $i++) {
	
		//array of values
		$dbArray = array();
		$dbArray['numLat'] = $arLats[$i];
		$dbArray['numLong'] = $arLongs[$i];
		$dbArray['dDistance'] = $dDistance;
		$dbArray['sDistance'] = $sDistance;
		$dbArray['nDifficulty'] = $difficulty;
		$dbArray['dTimeMin'] = $timeMin;
		$dbArray['dTimeMax'] = $timeMax;
		$dbArray['sTrailType'] = $trailType;
		$dbArray['sTimeRequired'] = $timerequired;
		$dbArray['sCategories'] = strtoupper(implode(" ", $arCategories));
		$dbArray['sImage'] = $sImage;
		$dbArray['sNearestCity'] = $sNearestCity;
		
		$arBaseTrailUse = array("Hike", "Bicycle", "Climb", "Handicap", "Horse", "Camp", "Swim", "Dog", "Fish", "Family");
		//trail use
		foreach ($arBaseTrailUse as $use)
		{
			$dbArray['b'.$use] = in_array($use, $arThisTrailUse) ? 1 : 0;			
		}

		//do the insert
		$dbw = wfGetDB( DB_MASTER );
		$dbArray['strTrail'] = $trailTitle;
		$dbw->insert( 'trailquickfacts', 
			$dbArray
			, __METHOD__
		);		
	}
}

?>