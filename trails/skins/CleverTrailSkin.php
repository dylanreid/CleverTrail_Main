<?php
/**
 * CleverTrail
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

include_once("../include/globals.php");
include_once("../include/Skin_shared.php");
include_once("../include/User_shared.php");

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @ingroup Skins
 */
class SkinCleverTrailSkin extends SkinTemplate {
	/** Using clevertrail. */
	var $skinname = 'clevertrailskin', $stylename = 'clevertrailskin',
		$template = 'CleverTrailTemplate', $useHeadElement = true;

	function setupSkinUserCss( OutputPage $out ) {
		global $wgHandheldStyle, $wgServer;		
				
		parent::setupSkinUserCss( $out );
	}
}

/**
 * @todo document
 * @ingroup Skins
 */
class CleverTrailTemplate extends QuickTemplate {
	var $skin;
	
	/**
	 * Template filter callback for CleverTrail skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		global $wgRequest, $wgServer, $wgUser, $wgScript, $wgDirectoryOfWiki, $wgOut;
		
		$this->skin = $skin = $this->data['skin'];
		$action = $wgRequest->getText( 'action' );
						
		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

		// Generate additional footer links		
		$footerlinks = $this->data["footerlinks"];		
		// fold footerlinks into a single array using a bit of trickery
		$footerlinks = call_user_func_array('array_merge', array_values($footerlinks));
		// Generate additional footer icons
		$footericons = $this->data["footericons"];
		// Unset any icons which don't have an image
		foreach ( $footericons as $footerIconsKey => &$footerIconsBlock ) {
			foreach ( $footerIconsBlock as $footerIconKey => $footerIcon ) {
				if ( !is_string($footerIcon) && !isset($footerIcon["src"]) ) {
					unset($footerIconsBlock[$footerIconKey]);
				}
			}
		}
		// Redo removal of any empty blocks
		foreach ( $footericons as $footerIconsKey => &$footerIconsBlock ) {
			if ( count($footerIconsBlock) <= 0 ) {
				unset($footericons[$footerIconsKey]);
			}
		}
		
		$this->html( 'headelement' );
		
		//get some useful information
		$server = "http://" . $_SERVER['HTTP_HOST'];
		$userName = '';
		if ($wgUser->isLoggedIn())
			$userName = $wgUser->getName();
		$trailTitle = $wgOut->getTitle();
		$namespace = $trailTitle->getNamespace();
		//add trail maps to categories and user pages
		$addTrailMap = ($namespace == NS_CATEGORY || $namespace == NS_USER);
		$bTrailArticle = ($namespace == NS_MAIN);
		drawCommonHeader($addTrailMap, $bTrailArticle);
		
		//if a user is logged in, find out how many trails he's finished
		$numTrailsFinished = getNumberOfFinishedTrails($wgUser);
		
		//only draw ads on the trail pages and categories
		$bDrawAds = false;
		$nNumAds = 0;
		$bTrailFinished = false;
		if ($trailTitle && ($namespace == NS_MAIN || $namespace == NS_CATEGORY)) {
			/*$articleId = $trailTitle->getArticleId();
			
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select(
				'page',                         			// $table
				array( 'page_len' ),            	  			// $vars (columns of the table)
				"page_id = $articleId",		// $conds
				__METHOD__,                                   			// $fname = 'Database::select',
				array()        			// $options = array()
			);			

			$len = 0;
			foreach ( $res as $a ) {
				$len = $a->page_len;
				break;
			}				
			$nNumAds = floor($len / 5000) + 1;
			*/
			//has this trail been marked as finished by this user?
			if ($namespace == NS_MAIN && $userName != "") {
				$trailTitleSafe = mysql_real_escape_string($trailTitle);
				$userNameSafe = mysql_real_escape_string($userName);
				
				$query = "select * from trails_finished where sUser = '$userNameSafe' AND sTrail = '$trailTitleSafe'";			
				$result = ExecQuery($query);
				$num_rows = mysql_num_rows($result);

				if ($num_rows > 0) {
					$bTrailFinished = true;
				}			
			}
		}		
			
?>



<div id="divGlobalWrapper">
	<?php 
	drawToolBar($userName, $this->data['personal_urls'], $this->data['search'], $numTrailsFinished); 	
	
	drawSideColumn($this->data['nav_urls']['print']['href'], $nNumAds);
	
	drawCommonDivs();
	?>
	
	<div id="divContentColumn">
		<div id="divActions" class="portlet">
			<?php if(!$this->data['loggedin']) { ?>
				  <style>
					li#ca-history { display: none; }
				  </style>
			<?php } ?>

			<h5><?php $this->msg('views') ?></h5>
			<div class="pBody">
				<ul><?php
					foreach($this->data['content_actions'] as $key => $tab) {
						$linkAttribs = array( 'href' => $tab['href'] );

						if( isset( $tab["tooltiponly"] ) && $tab["tooltiponly"] ) {
							$title = Linker::titleAttrib( "ca-$key" );
							if ( $title !== false ) {
								$linkAttribs['title'] = $title;
							}
						} else {
							$linkAttribs += Linker::tooltipAndAccesskeyAttribs( "ca-$key" );
						}
						$linkHtml = Html::element( 'a', $linkAttribs, $tab['text'] );

						/* Surround with a <li> */
						$liAttribs = array( 'id' => Sanitizer::escapeId( "ca-$key" ) );
						if( $tab['class'] ) {
							$liAttribs['class'] = $tab['class'];
						}
						echo '
					' . Html::rawElement( 'li', $liAttribs, $linkHtml );
					} ?>
				</ul>
			</div>
		</div>
		<div id="divContent"<?php $this->html("specialpageattributes") ?>>
			<a id="top"></a>
			<?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php } ?>

			<h1 id="firstHeading" class="firstHeading">
				<table width=100%><tr>
				<td align=left>
					<?php $this->html('title') ?>
				</td>
				<?php if ($namespace == NS_MAIN) { ?>
					<td align=right>
						<div id="divCompleteTrail" title="Mark Trail Complete" style="background:
						<?php if ($bTrailFinished) echo "#864422"; else echo "#642200"; ?>">
							<img id="imgCompleteTrail" src="<?php 
								if ($bTrailFinished)
									echo "http://clevertrail.com/images/icons/finished_trail_check.png";
								else
									echo "http://clevertrail.com/images/icons/unfinished_trail.png";
								?>"> 
							<a id="aCompleteTrail">I've Done This Trail!</a>			
						</div>
						<div id="divCompleteTrailLoading" style="display:none">
							<img src="http://clevertrail.com/images/load_large.gif"> 
						</div>
						<script>
						window.onload= new function() { wgUserName = '<?php if($wgUser->isLoggedIn()) echo $wgUser; ?>'; sTrailName = '<?php echo str_replace("'", "\'", $trailTitle); ?>'; bTrailFinished = '<?php echo $bTrailFinished ?>'; }
						</script>
					</td>
					<td style="width:1px; font-size:60%">[<a href="<?php echo $server; ?>/CleverTrail:User_Accounts" target="_blank">?</a>]</td>
				<?php } elseif ($namespace == NS_USER && $trailTitle) { ?>
						<td align=right>
							<?php 
							//draw the hiking title if this is a user article							
							$userPageName = $trailTitle->getDBkey();
							$numUserPageTrailsFinished = getNumberOfFinishedTrails($userPageName);
							echo drawHikerTitle($numUserPageTrailsFinished, 24, true, true, true); 								
							?>
						</td>
				<?php } ?>
				</tr></table>
			</h1>
			<div id="bodyContent">
				<div id="siteSub"><?php $this->msg('tagline') ?></div>
				<div id="contentSub"<?php $this->html('userlangattributes') ?>><?php $this->html('subtitle') ?></div>
				<?php if($this->data['undelete']) { ?>
					<div id="contentSub2"><?php $this->html('undelete') ?></div>
				<?php } ?>
				<?php if($this->data['newtalk'] ) { ?>
					<div class="usermessage"><?php $this->html('newtalk')  ?></div>
				<?php } ?>
				<?php if($this->data['showjumplinks']) { ?>
					<div id="jump-to-nav"><?php $this->msg('jumpto') ?> <a href="#divSideColumn"><?php $this->msg('jumptonavigation') ?></a>, <a href="#searchInput"><?php $this->msg('jumptosearch') ?></a></div>
				<?php } ?>
				<!-- start content -->
				<?php $this->html('bodytext') ?>
				<?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
				<!-- end content -->
				<?php if($this->data['dataAfterContent']) { $this->html ('dataAfterContent'); } ?>
				<div class="visualClear"></div>
			</div>
		</div>
		
		<div id="divFooter"<?php $this->html('userlangattributes') ?>>
			<?php foreach ( $footericons as $blockName => $footerIcons ) { ?>
				<div id="f-<?php echo htmlspecialchars($blockName); ?>ico">
					<?php foreach ( $footerIcons as $icon ) { ?>
					<?php echo $this->skin->makeFooterIcon( $icon ); ?>
					<?php } ?>
				</div>	
			<?php }

			// Generate additional footer links
			$validFooterLinks = array();
			foreach( $footerlinks as $aLink ) {
				//screen out some of the links
				if ($aLink == 'privacy' || $aLink == 'about' || $aLink == 'disclaimer')
					continue;
				if( isset( $this->data[$aLink] ) && $this->data[$aLink] ) {
					$validFooterLinks[] = $aLink;
				}
			}?>
			<ul id="f-list">
			<?php
				drawAdditionalFooterLinks(); 
				?>
				<BR>
				<?php
				foreach( $validFooterLinks as $aLink ) {
					if ($aLink == 'copyright') { ?> <BR> <?php }
					if( isset( $this->data[$aLink] ) && $this->data[$aLink] ) { ?>					
					<li id="<?php echo $aLink ?>"><?php $this->html($aLink) ?></li>
					<?php }
				} 
				if (sizeof($validFooterLinks) == 0) {
					?> <BR> 
				<?php }	?>
			</ul>
			
			<?php drawSocialNetworkingLinks(); ?>
			<?php drawCopyright(); ?>
		</div>
	</div>
</div>

<?php $this->html('bottomscripts'); /* JS call to runBodyOnloadHook */ ?>
<?php $this->html('reporttime') ?>
<?php if ( $this->data['debug'] ): ?>
<!-- Debug output:
<?php $this->text( 'debug' ); ?>

-->
<?php endif;

		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
		wfRestoreWarnings();
	} // end of execute() method


	

} // end of class


