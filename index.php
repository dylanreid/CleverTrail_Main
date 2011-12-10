<?php
//Copyright CleverTrail.com and Dylan Reid

//valid web entry point
define('MEDIAWIKI', true);

include_once("include/globals.php");
include_once("include/Skin_shared.php");
include_once("include/TrailMap_shared.php");
include_once("include/User_shared.php");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<title>CleverTrail</title>
<meta charset="UTF-8" />
<meta name="description" content="A non-profit and open-source project to help hikers and outdoor enthusiasts find, add, and edit community created articles on the internet's largest trail wiki."/>
<meta property="og:title" content="CleverTrail" /> 	
<meta property="og:type" content="website" /> 
<meta property="og:url" content="http://clevertrail.com" /> 
<meta property="og:image" content="http://clevertrail.com/images/defaultlogo.png" /> 
<meta property="og:site_name" content="CleverTrail" /> 
<meta property="og:locale" content="en_US" /> 
<meta property="fb:admins" content="1217047" /> 
<meta property="og:description" content="A non-profit and open-source project to help hikers and outdoor enthusiasts find, add, and edit community created articles on the internet's largest trail wiki." /> 
<?php
	drawCommonHeader(true, false);
?>
</head>

<body>

<div id="divGlobalWrapper">

	<?php 
	//Draw the ToolBar

	//temporarily turn off error reporting in case user has no cookie with this name
	$userName = '';

	error_reporting(0);
	if (isset($_COOKIE[$wgDBname.'UserID']))
		$userName = $_COOKIE[$wgDBname.'UserName'];	
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

	//create the array of links for a logged in user
	if ($userName != '')
	{
		$links['userpage'] = array(
			'text' => $userName,
			'href' => $wgPathOfWiki . "/User:$userName"
		);
		$links['mytalk'] = array(
			'text' => "my talk",
			'href' => $wgPathOfWiki . "/User_talk:$userName"
		);
		$links['preferences'] = array(
			'text' => "my preferences",
			'href' => $wgPathOfWiki . "/Special:Preferences"
		);
		$links['watchlist'] = array(
			'text' => "my watchlist",
			'href' => $wgPathOfWiki . "/Special:Watchlist"
		);
		$links['mycontris'] = array(
			'text' => "my contributions",
			'href' => $wgPathOfWiki . "/Special:Contributions/$userName"
		);
		$links['logout'] = array(
			'text' => "log out",
			'href' => $wgPathOfWiki . "/Special:UserLogout"
		);
	} else {
		$links['createaccount'] = array(
			'text' => "log in / create account",
			'href' => $wgPathOfWiki . "/Special:UserLogin"
		);
	}

	$searchTerm = "";

	//draw the top toolbar
	drawToolBar($userName, $links, $searchTerm, getNumberOfFinishedTrails($userName)); 
	
	//draw the side bar (navigation)
	drawSideColumn('', 0);
	
	//draw common divs
	drawCommonDivs();
	?>
	
	<div id="divContentColumn">
		<?php 
			echo drawTrailMap("Homepage", ""); //in TrailMap_shared.php
		?>		

		<div id="divFooter">
			<ul id="f-list">
			<?php drawAdditionalFooterLinks(); ?>
			</ul>
			<?php drawSocialNetworkingLinks(); ?>
			<?php drawCopyright(); ?>
			
		</div>
	</div>
</div>

<noscript> 
	<div id="divNoJavascriptWarning" style="position:absolute; width: 100%; height:100%; z-index:9999; background: #fff; color: #000;">
		<br><br>
		CleverTrail.com is designed for browsers with javascript enabled.<br><br>
		Please consider turning your javascript on and then trying CleverTrail again.<br><br>
		Thanks!<br><br>

		<a href="<?php echo $wgMainServer; ?>">CleverTrail.com</a>
		
	</div>
</noscript> 
	
</body>
</html>