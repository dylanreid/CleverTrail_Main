
var imgGreenCheckmark2 = new Image();
imgGreenCheckmark2.src = "http://clevertrail.com/images/icons/unfinished_trail.png";
var imgGreenCheckmark1 = new Image();
imgGreenCheckmark1.src = "http://clevertrail.com/images/icons/finished_trail_x.png";
var imgGreenCheckmark3 = new Image();
imgGreenCheckmark3.src = "http://clevertrail.com/images/load_large.gif";
var imgGreenCheckmark4 = new Image();
imgGreenCheckmark4.src = "http://clevertrail.com/images/icons/finished_trail_check.png";

var sTrailName = "";
var bTrailFinished = false;

$('#divCompleteTrail').live('click', function() {
	if (wgUserName == '') {
		window.location = wgServer + "/trails/index.php?title=Special:UserLogin&returnto=" + sTrailName;
		return;
	}
		
	$('#divCompleteTrail').hide();
	$('#divCompleteTrailLoading').show();
	
	var postData = "trailName=" + sTrailName;
	var postUrl = wgServer + "/ajax/handleMarkCompleteTrail.php";
			
	$.ajax({
		type: "POST",
		url: postUrl,
		data: postData,
		dataType: "json",
		beforeSend: function(x) {
		  if(x && x.overrideMimeType) {
		   x.overrideMimeType("application/j-son;charset=UTF-8");
		  }
		 },
		success: function(response){
			
			if (response.returnValue == 0){
				$('#divCompleteTrail').css('background', '#864422');
				$('#imgCompleteTrail').attr('src', "http://clevertrail.com/images/icons/finished_trail_check.png");
				bTrailFinished = true;
			} else if (response.returnValue == 1){
				$('#divCompleteTrail').css('background', '#642200');
				$('#imgCompleteTrail').attr('src', "http://clevertrail.com/images/icons/unfinished_trail.png");
				bTrailFinished = false;
			} else if (response.returnValue == 2) {
				window.location = wgServer + "/trails/index.php?title=Special:UserLogin&returnto=" + sTrailName;
			} else if (response.returnValue == 3){
				drawCleverTrailAlert("Error: No trail name given.");
			} else {
				drawCleverTrailAlert(response.returnValue);
			}
			
		},
		complete: function()
		{
			$('#divCompleteTrail').show();
			$('#divCompleteTrailLoading').hide();
		},
		error: function (data, status, e)
		{
			if (data.responseText != "")
				drawCleverTrailAlert(data.responseText);
		}
	});			
});

$('#divCompleteTrail').live('mouseover', function() {
	if (bTrailFinished) {
		$('#divCompleteTrail').css('background', '#864422');
		$('#imgCompleteTrail').attr('src', "http://clevertrail.com/images/icons/finished_trail_x.png");
	} else { 
		$('#divCompleteTrail').css('background', '#864422');
		$('#imgCompleteTrail').attr('src', "http://clevertrail.com/images/icons/finished_trail_check.png");
	}
});

$('#divCompleteTrail').live('mouseout', function() {
	if (bTrailFinished) {
		$('#divCompleteTrail').css('background', '#864422');
		$('#imgCompleteTrail').attr('src', "http://clevertrail.com/images/icons/finished_trail_check.png");
	} else {
		$('#divCompleteTrail').css('background', '#642200');
		$('#imgCompleteTrail').attr('src', "http://clevertrail.com/images/icons/unfinished_trail.png");
	}
		
});