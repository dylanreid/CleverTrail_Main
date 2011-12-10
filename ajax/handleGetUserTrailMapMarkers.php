<?php

//valid web entry point
define('MEDIAWIKI', true);

//Copyright CleverTrail.com and Dylan Reid 
include_once("../include/globals.php");
include_once("../include/TrailMap_shared.php");

$markers = array();
$user = "";
if (isset($_POST['user']))
	$user = mysql_real_escape_string(strtoupper($_POST['user']));

$whereClause = getTrailMapMarkerBaseWhereClause($_POST);
if ($whereClause != "")
	$whereClause .= " AND ";
else
	$whereClause = " WHERE ";

$whereClause .= " (trails_finished.sUser = '$user') AND (page_namespace = 0) AND trails_finished.bDeleted = 0";

$query = "select distinct strTrail as trailName, trailquickfacts.*, w4grb_avg.avg from trailquickfacts 
		 inner join trails_finished on trails_finished.sTrail = trailquickfacts.strTrail
		 inner join page on REPLACE(page.page_title, '_', ' ') = trailquickfacts.strTrail
		 left join w4grb_avg on w4grb_avg.pid = page.page_id
		 $whereClause group by strTrail";
		 
$markers = getTrailMapMarkers($query);		 

$response = array ( 'markers'=>$markers);
 
echo json_encode ( $response ) ;

?>
