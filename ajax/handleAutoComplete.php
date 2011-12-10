<?php

//valid web entry point
define('MEDIAWIKI', true);

//Copyright CleverTrail.com and Dylan Reid 
include_once("../include/globals.php");
 
    $term = $_GET['term']; 
	
$query = "SELECT page_title, page_namespace
FROM page WHERE UPPER(CONVERT(BINARY page_title USING latin1)) 
LIKE  '%" . strtoupper(mysql_real_escape_string(str_replace(' ', '_', $term))) . "%'
AND (
page_namespace =0
OR page_namespace =14
OR page_namespace =100
)
AND page_is_redirect = 0
ORDER BY page_namespace DESC , page_counter DESC LIMIT 11";

$res = ExecQuery($query);
$return = array();
while ($line = mysql_fetch_assoc($res)) {
	$newTitle = str_replace('_', ' ', $line['page_title']);
	if ($line['page_namespace'] == 100)
		$return[] = "Disambiguation:" . $newTitle;
	else
		$return[] = $newTitle;
}

if (sizeof($return) == 0)
	$return[] = 'No Trails Or Categories Found';
	
if (sizeof($return) > 10)
	$return[10] = '... More Than 10 Matches Found';
	
  
echo json_encode(array_values(array_unique($return)));  

?>
