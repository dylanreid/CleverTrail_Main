<?php

//valid web entry point
define('MEDIAWIKI', true);

//Copyright CleverTrail.com and Dylan Reid 

include_once("../include/globals.php");

$fileName = "";
if (isset($_POST['fileName'])) {
	$fileName = $_POST['fileName'];
}

//imageCode:
//0: success, image found or empty string (Nophotoavailable.jpg)
//1: invalid path, Invalidphotopath.jpg
$imageCode = 0;
$imagePath = getImagePath($fileName, 300);

//is this an invalid photo path (i.e. it doesn't exist)
if ($imagePath != "") {
	$handle = @fopen($imagePath,'r');	
	if(!$handle){
		//if the image couldn't be found, try finding it without the 300 width
		$imagePath = getImagePath($fileName, 0);		
		$handle = @fopen($imagePath,'r');		
		if(!$handle){
			$imageCode = 1;
		}
	}
}


$response = array ( 'imageCode'=>$imageCode,
					'imagePath'=>$imagePath);
 
echo json_encode ( $response ) ;

?>
