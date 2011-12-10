<?php

//valid web entry point
define('MEDIAWIKI', true);

//Copyright CleverTrail.com and Dylan Reid 
include_once("../include/globals.php");
include_once("../include/TrailMap_shared.php");

$markers = array();
$whereClause = getTrailMapMarkerBaseWhereClause($_POST);

if ($whereClause == '')
	$whereClause .= ' where page.page_namespace = 0';
else
	$whereClause .= ' and page.page_namespace = 0';
	
$query = "select trailquickfacts.*, w4grb_avg.avg from trailquickfacts 
		 inner join page on REPLACE(page.page_title, '_', ' ') = trailquickfacts.strTrail
		 left join w4grb_avg on w4grb_avg.pid = page.page_id
		 $whereClause ";
	
$markers = getTrailMapMarkers($query);		 

$response = array ( 'markers'=>$markers);
 
echo json_encode ( $response ) ;

?>
