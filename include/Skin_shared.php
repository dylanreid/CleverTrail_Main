<?php
/* Shared functions through the CleverTrail website */
/* Copyright CleverTrial.com and Dylan Reid 2011 */

//redirect older versions of ie
$useragent=$_SERVER['HTTP_USER_AGENT'];
if (preg_match('/\bmsie [1-5]/i', $useragent) && !preg_match('/\bopera/i', $useragent))
{
	header('location: http://' . $_SERVER['HTTP_HOST'] . '/oldbrowser.php');
}

//redirect mobile browsers to mobile site
if(preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
	$domain = $_SERVER['HTTP_HOST'];
	$url = "http://" . $domain . $_SERVER['REQUEST_URI'];
	$parsed = parse_url($url);
	header('Location: http://m.clevertrail.com' . $parsed['path'] . $parsed['query'] . $parsed['fragment']);
}

//common header stuff: style sheets and a javascript warning
function drawCommonHeader($addTrailMap, $bTrailArticle) {
	global $wgMainServer;
?>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js" language="javascript"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>  
<script type="text/javascript" src="<?php echo $wgMainServer ?>/javascript/globals.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo $wgMainServer ?>/javascript/main.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo $wgMainServer ?>/javascript/toolbar.js" language="javascript"></script>
<link type='text/css' href='<?php echo $wgMainServer ?>/css/main.css' rel='stylesheet' media='screen' />
<link type='text/css' href='<?php echo $wgMainServer ?>/css/toolbar.css' rel='stylesheet' media='screen' />

<?php if ($bTrailArticle) { ?>
<script type="text/javascript" src="<?php echo $wgMainServer ?>/javascript/trailarticle.js" language="javascript"></script>
<?php } ?>

<?php if ($addTrailMap) { ?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.5&sensor=false"></script>
<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo $wgMainServer ?>/javascript/infobox.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo $wgMainServer ?>/javascript/trailmap.js" language="javascript"></script>
<link type='text/css' href='<?php echo $wgMainServer ?>/css/trailmap.css' rel='stylesheet' media='screen' />
<?php } ?>

<!--[if lt IE 5.5000]><link rel="stylesheet" href="<?php echo $wgMainServer?>/css/IE50Fixes.css" media="screen" /><![endif]-->
<!--[if IE 5.5000]><link rel="stylesheet" href="<?php echo $wgMainServer?>/css/IE55Fixes.css" media="screen" /><![endif]-->
<!--[if IE 6]><link rel="stylesheet" href="<?php echo $wgMainServer?>/css/IE60Fixes.css" media="screen" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" href="<?php echo $wgMainServer?>/css/IE70Fixes.css" media="screen" /><![endif]-->
<!--[if IE 8]><link rel="stylesheet" href="<?php echo $wgMainServer?>/css/IE80Fixes.css" media="screen" /><![endif]-->	

<script type="text/javascript" charset="utf-8">
var is_ssl = ("https:" == document.location.protocol);
var asset_host = is_ssl ? "https://s3.amazonaws.com/getsatisfaction.com/" : "http://s3.amazonaws.com/getsatisfaction.com/";
document.write(unescape("%3Cscript src='" + asset_host + "javascripts/feedback-v2.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript" charset="utf-8">
var feedback_widget_options = {};

feedback_widget_options.display = "overlay";  
feedback_widget_options.company = "clevertrail";
feedback_widget_options.placement = "right";
feedback_widget_options.color = "#642200";
feedback_widget_options.style = "problem";

var feedback_widget = new GSFN.feedback_widget(feedback_widget_options);
</script>
<?php
}

function drawCommonDivs() {
?>
	<div id="divCleverTrailAlert"></div>
<?php
}

function drawToolBar($userName, $personalUrls, $searchTerm, $numTrailsFinished) {
	global $wgMainServer, $wgScriptOfWiki;
?>
	<div id="divToolbar">
		<input type="hidden" id="txtScriptPath" name="txtScriptPath" value="<?php echo $wgScriptOfWiki; ?>">
		
		<div id="divCleverTrailLogo">
			<a href="<?php echo $wgMainServer; ?>">
			<img id="imgCleverTrailLogo" src="http://clevertrail.com/images/logo-40px-white-on-grey.png" height="40px"></a>
		</div>
		<div id="divTrailSearch">		
			<form action="<?php echo $wgScriptOfWiki ?>" id="searchform">
				<input style="height: 0px" type='hidden' name="title" value="Special:Search"/>	

				<table border=0 cellspacing=0 cellpadding=0><tr><td valign=top>
				<input id="txtTrailSearchInput" autocomplete="off" title="Search By Trail Name Or Category" 
				type="text" name="search" value="<?php
				echo ( $searchTerm != '' ) ? $searchTerm : 'search for a trail name or category';
				?>"/>
				</td><td>
				<input type='submit' name="go" id="btnTrailSearchInput" value="" title="Search By Trail Name">			
				</td></tr></table>
				
			</form>			
		</div>
		
		<img id="imgAddTrail" src="http://clevertrail.com/images/add-trail-button.png"> 
			
		<div id="divAddTrailInput">		
			<table border=0 cellspacing=0 cellpadding=0><tr><td valign=top>
			<input id="txtAddTrailInput" type="input" name="txtAddTrailInput" onkeypress="addTrailIfEnter(event);">		
			</td><td>
			<input id="btnAddTrailInput" type="button" name="btnAddTrailInput">
			</td></tr></table>
		</div>
		
		<div id="divUserActions">						
			<?php
			//if the user name is set, let's create a fancy drop down menu
			if ($userName != '') { ?>	
				<?php echo drawHikerTitle($numTrailsFinished, 24, false, false, false); ?>&nbsp;
				<a id="aUserActionsUserName"><?php echo $userName ?></a>
				<img id="imgUserActionsDropDown" src="http://clevertrail.com/images/icons/ico-dropdown25.png">
				<br>
				<ul id="ulUserActionsDropDown">
				<?php foreach($personalUrls as $key => $item) {
					 ?>
						<li id="<?php echo"pt-$key" ?>" onclick="location.href=('<?php echo htmlspecialchars($item['href']) ?>')">
						<?php 
							if ($key == "userpage")
								echo "my page";
							else
								echo htmlspecialchars($item['text']) ;
						?></li>
				<?php }	?>
				</ul>
			
			<?php }
				else { ?>
					<ul id="ulUserActionsAcross">
					<?php foreach($personalUrls as $key => $item) { 
						//skip the user page for an ip address - we want to encourage registration
						if ($key == "anonuserpage" || $key == "anontalk") continue;
					?>
							<li id="<?php echo"pt-$key" ?>">
							<a href="<?php echo htmlspecialchars($item['href']) ?>">
							<?php echo htmlspecialchars($item['text']) ?></a></li>
					<?php }	?>
					</ul>
				<?php 
				} ?>
		</div>
	</div>
<?php
}

//dylan: this should have a variable called $links that has all of the links and their data that is traversed with a foreach
//like $links['nav_urls']['print']['href'], etc (which actually does exist in CleverTrailSkin class
function drawSideColumn($printURL, $nNumAds) { 
	global $wgPathOfWiki, $wgMainServer;
	//(Temporarily?) Disabled Ads - if you enable them, change wording in CleverTrail:Donate
	$nNumAds = 0;
?>

	<div id="divSideColumn">		
	<div id="divNavigator" class="pBody">
		<ul>
			<li id="n-home"><a href="<?php echo $wgMainServer ?>" title="Map of all the trails">
			Map Of Trails</a></li>				
			<li id="n-recentchanges"><a href="<?php echo $wgPathOfWiki ?>/Special:RecentChanges" title="The list of recent changes">
			Recent Changes</a></li>
			<li id="n-randompage"><a href="<?php echo $wgPathOfWiki ?>/Special:Random" title="Load a random trail">
			Random Trail</a></li>
			<li id="n-upload"><a href="<?php echo $wgPathOfWiki ?>/Special:MultipleUpload" title="Upload photos">
			Upload Photos</a></li>
			<li id="n-specialpages"><a href="<?php echo $wgPathOfWiki ?>/Special:SpecialPages" title="Special pages">
			Special Pages</a></li>		
			<?php if ($printURL != '') { ?>
				<li id="n-print"><a href="<?php echo htmlspecialchars($printURL)
				?>" rel="alternate" title="A printable version of this page">
				Printable Page</a></li>
			<?php } ?>
			
			<li id="n-help"><a href="<?php echo $wgPathOfWiki ?>/CleverTrail:Help" title="Help with CleverTrail">
			Help</a></li>
			
			<li id="n-contribute"><a href="<?php echo $wgPathOfWiki ?>/CleverTrail:Contribute" title="How to help out!">
			<b>Contribute</b></a></li>
		</ul>
	</div>
	<?php
	if ($nNumAds > 0) { 
	?>
	<br>
	<div id="divGoogleAdsense">
<?php
	for ($i=0; $i<$nNumAds; $i++){
		drawAdsenseCode();
	}
?>
	</div>
	<?php } ?>
</div>	

<?php
}

function drawAdsenseCode() { ?>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-0863007979541184";
/* AdsenseWideSkyscraper */
google_ad_slot = "4564460493";
google_ad_width = 160;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php }

function drawAdditionalFooterLinks() {
	global $wgPathOfWiki;
?>
	<li><a href="<?php echo $wgPathOfWiki; ?>/CleverTrail:About" title="About CleverTrail">about</a></li>
	<li><a href="<?php echo $wgPathOfWiki; ?>/CleverTrail:Contact" title="Ways To Contact CleverTrail">contact</a></li>
	<li><a href="<?php echo $wgPathOfWiki; ?>/CleverTrail:Privacy" title="Privacy Policy">privacy</a></li>
	<li><a href="<?php echo $wgPathOfWiki; ?>/CleverTrail:Terms_Of_Use" title="Terms Of Use">terms of use</a></li>
	<li><a href="<?php echo $wgPathOfWiki; ?>/CleverTrail:Help" title="Help With CleverTrail">help</a></li>
	<li><a href="<?php echo $wgPathOfWiki; ?>/CleverTrail:Contribute" title="How to help Out!">contribute</a></li>
<?php			
}

function drawSocialNetworkingLinks() {
?>
	<!-- Facebook -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) {return;}
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=87563175281";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	
	<!-- Twitter -->
	<script src="http://widgets.twimg.com/j/2/widget.js"></script>
	
	<!-- G+-->
	<link href="https://plus.google.com/102374266996054484073/" rel="publisher" /><script type="text/javascript">
	(function() 
	{var po = document.createElement("script");
	po.type = "text/javascript"; po.async = true;po.src = "https://apis.google.com/js/plusone.js";
	var s = document.getElementsByTagName("script")[0];
	s.parentNode.insertBefore(po, s);
	})();</script>

	<center>
	<table border=0 style="padding-top: 5px; width=100%">
	<tr>
	<td align=right>		

<!-- Facebook -->
<div class="fb-like-box" data-href="http://www.facebook.com/pages/CleverTrail/171908426231720" data-width="350" data-height="250" data-show-faces="false" data-stream="true" data-header="false"></div>
	
	</td><td align=middle >
	&nbsp;&nbsp;
	
<!-- Twitter -->
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 2,
  interval: 30000,
  width: 225,
  height: 300,
  theme: {
    shell: {
      background: '#e9f0ec',
      color: '#222222'
    },
    tweets: {
      background: '#ffffff',
      color: '#222222',
      links: '#0735eb'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: false,
    behavior: 'all'
  }
}).render().setUser('CleverTrail').start();
</script>	
	
	&nbsp;&nbsp;
	</td><td align=left>
	
<!-- G+ -->
<g:plus href="https://plus.google.com/102374266996054484073/" size="badge"></g:plus>

	</td>
	</tr>
	</table>
	</center>
<?php
}
?>
