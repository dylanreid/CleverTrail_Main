/* javascript in use anywhere in clevertrail.com */

//returns the path of an already uploaded fileName
//mediawiki uses a md5 file saving scheme
function getPathOfFileName(fileName, thumbSize){
	var md5sum = hex_md5(fileName);
	var filePath = '';
	
	if (md5sum.length > 1 && fileName.length > 0)
	{
		firstDir = md5sum.charAt(0);
		secondDir = md5sum.charAt(0) + md5sum.charAt(1);
				
		//normal image
		if (thumbSize == 0)
			filePath = 'http://clevertrail.com/trails/images/' + firstDir + '/' + secondDir + '/' + fileName;		
		else //thumb image
			filePath = 'http://clevertrail.com/trails/images/thumb/' + firstDir + '/' + secondDir + '/' +fileName + '/' + thumbSize + 'px-' + fileName;				
	}
	
	alert (fileName + ", " + filePath);
	return filePath;
}

//make first character of each word into upper case
function toTitleCase(str)
{
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

//display the clever trail alert box
function drawCleverTrailAlert(html) {
	
	html += '<br><br><a href="#" onclick="document.getElementById(\'divCleverTrailAlert\').style.display = \'none\';">Close</a>';
	$('#divCleverTrailAlert').html(html);
	$('#divCleverTrailAlert').css("position","absolute");
	$('#divCleverTrailAlert').css("top", (($(window).height() - $('#divCleverTrailAlert').outerHeight()) / 2) + $(window).scrollTop() + "px");
	$('#divCleverTrailAlert').css("left", (($(window).width() - $('#divCleverTrailAlert').outerWidth()) / 2) + $(window).scrollLeft() + "px");    
	$('#divCleverTrailAlert').show();
		
}

function isNumber(n) {
  return (n == "") || (!isNaN(parseFloat(n)) && isFinite(n));
}