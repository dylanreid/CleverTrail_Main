<?php
//Copyright 2011, Dylan Reid and Clevertrail.com

class SpecialMassTrailEdit extends SpecialPage {

	function __construct() {
			parent::__construct( 'MassTrailEdit' , '', false, false, 'default', false);
			wfLoadExtensionMessages('CleverTrail');
	}

	public function isRestricted() {
		return true;
	}


	/**
	 * Manage forms to be shown according to posted data.
	 * Depending on the submit button used, call a form or a save function.
	 *
	 * @param $par Mixed: string if any subpage provided, else null
	 */
	public function execute( $par ) {
		global $wgUser;
		if (!$wgUser->isAllowed('editinterface')){
			header("location: http://www.clevertrail.com");
			return;
		}
			
		// If the visitor doesn't have permissions to assign or remove
		// any groups, it's a bit silly to give them the user search prompt.
		
		$this->doMassEdit();
	}
	
	protected function doMassEdit() {
		global $wgOut, $wgPathOfWiki;
		
		$time = microtime(); 
		$time = explode(" ", $time); 
		$time = $time[1] + $time[0]; 
		$start = $time; 
		
		$this->setHeaders();
		$wgOut->setPagetitle("Doing mass trail edit...");
	
		//get all the trails in mainspace that aren't redirects
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			'page',                         			// $table
			array( 'page_title' ),            	  			// $vars (columns of the table)
			'page.page_namespace = 0 AND page.page_is_redirect  = 0',		// $conds
			__METHOD__,                                   			// $fname = 'Database::select',
			array()        			// $options = array()
		);	
		
		$executionTime = ini_get('max_execution_time');
		set_time_limit(0);
		$numTrailsEdited = 0;
		foreach ( $res as $a ) {
			
			//get the article
			$trailTitle = Title::newFromText($a->page_title);				
			if (!$trailTitle)
				continue;
				
			$nTrailId = $trailTitle->getArticleId(); 
			$trailArticle = Article::newFromId($nTrailId);	
			if (!$trailArticle)
				continue;
				
			$text = $trailArticle->getRawText();
			
			//$newtext = preg_replace("/(\{\{#cleverTrail_CategoryTrailMap:)/i", "{{#cleverTrail_CategoryPageAutoContent:", $text);
			
			//make edits
			$newtext = preg_replace("/\<!---\>/", "", $text);	
			if ($newtext == $text)
				$newtext .= "\n<!--->";			
			
			$result = "OK";
			$summary = "Adding more options for filtering trail maps";
			$flags = EDIT_UPDATE & EDIT_FORCE_BOT;
			$user = User::newFromName("CleverTrailBot");
			$status = NULL;
			
			//save the trail through the normal mechanism, mark as saved by CleverTrailBot
						
			//$status = $trailArticle->doEdit($newtext, $summary, $flags, false, $user);
			
			if ($status && !$status->isOK()) {
				$result = str_replace("\n", "<BR>", $status->getWikitext());
			}
			$trailLink = '<a href="' . $wgPathOfWiki .'/' . $a->page_title . '">' . $a->page_title . '</a>';
			$wgOut->addHTML($trailLink . ": $result<BR>");
			$numTrailsEdited++;
			//if ($numTrailsEdited > 1)
				//break;
		}
		set_time_limit($executionTime);
		
		$time = microtime(); 
		$time = explode(" ", $time); 
		$time = $time[1] + $time[0]; 
		$finish = $time; 
		$totaltime = round(($finish - $start), 5); 
		$wgOut->addHTML("<br><b>This page took $totaltime seconds to load and edited $numTrailsEdited trails.</div>"); 
		
	}
	
}


/*

			
			//$newtext = preg_replace("/\<!--\>/", "", $text);	
			//if ($newtext == $text)
			//	$newtext .= "\n<!-->";
			
			//google map markers
			preg_match_all("/\<!--END:MISCELLANEOUS--\>\n== Map ==\n\{\{#display_points:\n(.*?)\n\|/s", $text, $markerData);
			$lat = "";
			$lng = "";
			if (sizeof($markerData[1]) == 1) {
				$markers = explode("\n", trim($markerData[1][0]));
				
				foreach($markers as $marker) {
					$markerParts = explode("~", $marker);
					
					$latLng = explode(",", $markerParts[0]);
					
					$lat = trim($latLng[0]);
					$lng = trim($latLng[1]);
				}
			}
			
			if ($lat == "" || $lng == "")
			{
				$wgOut->addHTML($a->page_title . ": no lat or long found<BR>");
				$numTrailsEdited++;
				continue;			
			}
			
			//google map center
			$centerlat = "";
			$centerlng = "";
			preg_match_all("/\{\{#display_points:(.*)\| center=(.*?), (.*?)\n/s", $text, $centerData);
			if (sizeof($centerData[2]) == 1 && sizeof($centerData[3]) == 1) {
				$centerlat = trim($centerData[2][0]);
				$centerlng = trim($centerData[3][0]);
			}
			
			$zoom = "";
			//google map zoom
			preg_match_all("/\{\{#display_points:(.*)\| zoom=(.*?)\n/s", $text, $zoomData);
			if (sizeof($zoomData[2]) == 1) {
				$zoom = trim($zoomData[2][0]);
			}
			
			$maptype = "";
			//google map type
			preg_match_all("/\{\{#display_points:(.*)\| type=(.*?)\n/s", $text, $typeData);
			if (sizeof($typeData[2]) == 1) {
				$maptype = trim($typeData[2][0]);
			}
			
			//$wgOut->addHtml("lat: $lat, lng: $lng, centerlat: $centerlat, centerlng: $centerlng, zoom: $zoom, type: $maptype");
			
			//$wgOut->addHtml("<br><br>before: " . htmlspecialchars($text) . "<BR><BR>");
			$text = preg_replace("/== Map(.*?)\}\}/s", "", $text);
			//$wgOut->addHtml("after: " . htmlspecialchars($text));
			
$output = '';
$output .= '== Map ==
<table id="tblTrailMap"><tr><td>
{{#display_points:
';

$markersHTML = '<b>Trailhead</b> [[File:Trailhead1.png|]] (' . round($lat, 4) . ', ' . round($lng, 4) . ')<BR>';

$output .= $lat . ',' . $lng . '~~ ~File:Trailhead1.png
';

$output .= '| center=' . $centerlat . ', ' . $centerlng . '
| zoom=' . $zoom . '
| type=' . $maptype . '
| types=roadmap, satellite, terrain
| controls=zoom, type
| zoomstyle=large
| typestyle=dropdown
| height=400
}}
</td></tr>
<tr><td style="color:#fff">
';

$output .= $markersHTML;
$output .= '</td></tr></table>';

//	$wgOut->addHtml("<BR><BR>map code: $output");

$output = '</gallery>
' . $output;

$text = preg_replace("/\<\/gallery\>/s", $output, $text);

//$wgOut->addHtml("<BR><BR>finale code: " . htmlspecialchars($text));

*/