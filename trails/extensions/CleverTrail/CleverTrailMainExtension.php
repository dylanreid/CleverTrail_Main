<?php
if( !defined( 'MEDIAWIKI' ) ){
        die( "This is not a valid entry point.\n" );
}

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'CleverTrailMain',
	'version' => '1.3',
	'author' => '[http://www.twitter.com/dylankreid Dylan Reid]',
	'description' => 'Extension for the CleverTrail website',
);

$dir = dirname(__FILE__) . '/';
 
//Register the special page Special:EditTrail
$wgAutoloadClasses['SpecialEditTrail'] = $dir . 'SpecialEditTrail.php'; # Location of the SpecialCleverTrail class (Tell MediaWiki to load this file)
$wgExtensionMessagesFiles['CleverTrail'] = $dir . 'CleverTrail.i18n.php'; # Location of a messages file (Tell MediaWiki to load this file)
$wgSpecialPages['EditTrail'] = 'SpecialEditTrail'; # Tell MediaWiki about the new special page and its class name

//Register the special page Special:MassTrailEdit
$wgAutoloadClasses['SpecialMassTrailEdit'] = $dir . 'SpecialMassTrailEdit.php'; # Location of the SpecialCleverTrail class (Tell MediaWiki to load this file)
$wgExtensionMessagesFiles['CleverTrail'] = $dir . 'CleverTrail.i18n.php'; # Location of a messages file (Tell MediaWiki to load this file)
$wgSpecialPages['MassTrailEdit'] = 'SpecialMassTrailEdit'; # Tell MediaWiki about the new special page and its class name



//The following overrides the action=edit action from article pages 
//usually a user would not come here but there were still ways to access the action=edit option
//even as simple as putting it into the URL, so this hook is necessary
$wgHooks['AlternateEdit'][] = 'cleverTrailMainAlternateEdit';
function cleverTrailMainAlternateEdit( $editpage ) {
	global $wgServer, $wgScript, $wgTitle, $wgUser;
	
	//if a user is in the undo editing section, allow that as well
	$undoEditing = false;
	if (isset($_GET["undo"]))
		$undoEditing = true;
	
	//allow adminstrators to edit as normal or if not the main namespace
	$ctCurrentNamespace = $wgTitle->getNamespace();
	if ($wgUser->isAllowed('editinterface') || $ctCurrentNamespace != NS_MAIN || $undoEditing) {
		return true;
	}
	else {
		$redirect = "$wgServer$wgScript/Special:EditTrail/$wgTitle";
		header("Location: $redirect");
	}
	return false;
}

 
//The following adds "Trail" and "Edit Trail" to the Content Actions tabs for trail articles
$wgHooks['SkinTemplateNavigation::Universal'][] = 'cleverTrailMainSkinTemplateNavigationUniversal';
function cleverTrailMainSkinTemplateNavigationUniversal(  $skin, array &$content_actions ) {  

		global $wgUser;
		$title = $skin->getTitle();
		$ctCurrentNamespace = $title->getNamespace();
		
		//add the "Edit Trail" tab if we're in the "Main" Namespace (i.e. if this is a trail article)
		if ($ctCurrentNamespace == NS_MAIN && array_key_exists('views', $content_actions) && array_key_exists('namespaces', $content_actions))
		{				
			$editArticleText = "Edit Trail";
			//the wording should be different if this trail exists already or is new
			if (!$title->exists())
			{
				$editArticleText = "Create Trail";
			}
			
			$edit_tab = NULL;
			if (array_key_exists('edit', $content_actions['views']))
				$edit_tab = $content_actions['views']['edit'];
			
			//if "edit" tab exists, update it with "edit trail" info
			if ($edit_tab != NULL)
			{
				//for administrators add the old edit tab back in
				if ($wgUser->isAllowed('editinterface'))
				{
					$old_edit_tab = array(
										'class' => $edit_tab['class'],
										'text' => $edit_tab['text'],
										'href' => $edit_tab['href']
									);
					
					$content_actions['views'][] = $old_edit_tab;
				}
				
				//direct "edit trail" to the special page
				$edit_tab['text'] = $editArticleText;
				$href = wfScript() . '/' . 'Special:EditTrail' . '/' . $title;
				$edit_tab['href'] = $href;
				$content_actions['views']['edit'] = $edit_tab;
			}			
			
			
			//change normal "page" text to "Trail"
			if (array_key_exists('main', $content_actions['namespaces'])){
				$content_actions['namespaces']['main']['text'] = "Trail";
			}
			
			//change normal "discussion" text to "Trail"
			if (array_key_exists('talk', $content_actions['namespaces'])){
				$content_actions['namespaces']['talk']['text'] = "Article Discussion";
			}			
		}
		
		//always change "Move" to "Rename"
		if (array_key_exists('move', $content_actions['actions'])){
			$content_actions['actions']['move']['text'] = "Rename";
		}	
		
		return true;
}

//override the original link for the "[Edit]" links in the trail pages' sections
//instead, link to the special page's edit trail section
$wgHooks['DoEditSectionLink'][]  = 'cleverTrailMainDoEditSectionLink';  
function cleverTrailMainDoEditSectionLink($skin, $title, $section, $tooltip, $result, $lang ) {
	global $wgScript;
	
	//if this is not in the main namespace, don't do any fancy editing of the section links
	if ($title->getNamespace() != NS_MAIN)
		return true;
	
	$titleWithUnderscores = $title->getDBkey();
	//if this is a link to one of the non-standard sections, don't display it
	$arStandardSections = array(1 => "Overview", 2 => "Directions To Trailhead", 3 => "Trail Description", 4 => "Conditions And Hazards",
								5 => "Fees, Permits, And Restrictions", 6 => "Amenities", 7 => "Miscellaneous", 8 => "Photo Gallery", 9 => "Map");
	
	$posOfSection = array_search($tooltip, $arStandardSections);
	
	if (!$posOfSection) {
		if ($tooltip != "Comments And Review") {
			$result = "&nbsp;";
		} else {
			$result = "";
		}
		
	}
	else {
		//create the link for special page's section editor
		$result = "<span class=\"editsection\">[<a href=\"$wgScript/Special:EditTrail/$titleWithUnderscores?section=$posOfSection\" title=\"$tooltip\">";
		$result .= 	wfMsgExt( 'editsection', array( 'language' => $lang ));
		$result .=  "</a>]</span>";
	}
	
	return true;
}

?>