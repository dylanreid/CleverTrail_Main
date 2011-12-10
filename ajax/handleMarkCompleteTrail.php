<?php

//valid web entry point
define('MEDIAWIKI', true);

//Copyright CleverTrail.com and Dylan Reid 
include_once("../include/globals.php");
 
$trailName = "";
$userName = "";
$returnValue = 0;

if (isset($_POST['trailName']))
	$trailName = mysql_real_escape_string($_POST['trailName']); 

error_reporting(0);
if (isset($_COOKIE[$wgDBname.'UserID']))
	$userName = mysql_real_escape_string($_COOKIE[$wgDBname.'UserName']);	
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

//user not logged in
if ($userName == "")
	$returnValue = 2;

//no trailName
if ($trailName == "")
	$returnValue = 3;
	
if ($returnValue == 0) {
	$query = "select * from trails_finished where sUser = '$userName' AND sTrail = '$trailName'";
	$res = ExecQuery($query);

	if ($line = mysql_fetch_assoc($res)) {
		$query = "delete from trails_finished where sUser = '$userName' AND sTrail = '$trailName'";
		$returnValue = 1;
	} else {
		$query = "insert into trails_finished set sUser = '$userName', sTrail = '$trailName'";
		$returnValue = 0;
	}
	
	ExecQuery($query);
}

echo json_encode ( array ( 'returnValue'=>$returnValue) ) ;
?>
