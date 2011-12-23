/* javascript for the toolbar */

var imgLoadCircle = new Image();
imgLoadCircle.src = "http://clevertrail.com/images/load_circle.gif";

var imgAddTrail = new Image();
imgAddTrail.src = "http://clevertrail.com/images/add-trail-button.png";
var imgAddTrailHover = new Image();
imgAddTrailHover.src = "http://clevertrail.com/images/add-trail-button-hover.png";

var imgIconGo = new Image();
imgIconGo.src = "http://clevertrail.com/images/icons/icon-go-brown25.png";

function addTrailIfEnter(e){
	var c = document.all? event.keyCode : e.which;
    if(c == 13) 
	{
		addTrail();
		return false;
	}
    return true;
}

function addTrail() {

	scriptPath = document.getElementById("txtScriptPath").value;	
	trailName = document.getElementById("txtAddTrailInput").value;
	trailName = toTitleCase(trailName);
	redirectPath = scriptPath + "?title=" + trailName + "&action=edit";
	window.location = redirectPath;
}


//the function that will hide the add trail input after 500 ms
var bHideAddTrailInput = false;
function hideAddTrailInput() {
	if (bHideAddTrailInput)
		$('#divAddTrailInput').css("display", "none");
}	

//the function that will hide the drop down list after 500 ms
var bHideUserActionsDropDown = false;
function hideUserActionsDropDown() {
	if (bHideUserActionsDropDown)
		$('#ulUserActionsDropDown').css("display", "none");
}	
	
//begin: jquery effects
//change colors of trail search box when entering/leaving
$('#txtTrailSearchInput').live('focus', function () {
	if ($(this).val() == "search for a trail name or category") {
		$(this).val("");
		$(this).css("color", "#333");
	}
});

//toggle visibility of add trail input
$('#imgAddTrail').live('click', function() {
	$('#divAddTrailInput').toggle();
	$('#txtAddTrailInput').focus();		
});

$('#txtAddTrailInput').live('blur', function () {
	bHideAddTrailInput = true;
	window.setTimeout(hideAddTrailInput, 300);
});

$('#btnAddTrailInput').live('click', function () {
	bHideAddTrailInput = false;
	addTrail();
});	

$('#btnTrailSearchInput').live('click', function () {
	searchForTrailOrCategory();
});	

//toolbar user actions drop down list	
//show or hide when clicking the name or drop down image
$('#imgUserActionsDropDown').live('click', function() {		
	$('#ulUserActionsDropDown').slideDown('slow');
});
$('#aUserActionsUserName').live('click', function() {
	$("#ulUserActionsDropDown").slideDown('slow');
});

//signal to hide  user actions drop down after 500 ms on mouse out of any of the 3 elements
$('#divUserActions').live('mouseout', function() {
	bHideUserActionsDropDown = true;
	window.setTimeout(hideUserActionsDropDown, 500);
});
$('#imgUserActionsDropDown').live('mouseout', function() {
	bHideUserActionsDropDown = true;
	window.setTimeout(hideUserActionsDropDown, 500);
});
$('#aUserActionsUserName').live('mouseout', function() {
	bHideUserActionsDropDown = true;
	window.setTimeout(hideUserActionsDropDown, 500);
});

//signal not to hide  user actions drop down  afterall on mouse over of any of the 3 elements
$('#divUserActions').live('mouseover', function() {
	bHideUserActionsDropDown = false;
});
$('#imgUserActionsDropDown').live('mouseover', function() {
	bHideUserActionsDropDown = false;
});
$('#aUserActionsUserName').live('mouseover', function() {
	bHideUserActionsDropDown = false;
});

//highlight the li elements on mouse over
$('#ulUserActionsDropDown > li').live('mouseover', function() {
	$(this).css('background', '#642200');
});
$('#ulUserActionsDropDown > li').live('mouseout', function() {
	$(this).css('background', '#777');
});

$('#imgAddTrail').live('mouseover', function() {
	$('#imgAddTrail').attr('src', imgAddTrailHover.src);
});
$('#imgAddTrail').live('mouseout', function() {
	$('#imgAddTrail').attr('src', imgAddTrail.src);
});

$(document).ready(function() {
	var server = document.location.hostname;
	handlerLocation = 'http://' + server + '/ajax/handleAutoComplete.php';
		
	$("#txtTrailSearchInput").autocomplete({		
		source: handlerLocation,
		minLength: 2,
		select: function(event, ui) {
			if (ui.item.value != "No Trails Or Categories Found" && ui.item.value != "... More Than 10 Matches Found"){
				$(this).val(ui.item.value);
				document.getElementById("searchform").submit();
			}
		},
		search: function(event, ui) {
			$(this).css('background', '#ffffff url(http://clevertrail.com/images/load_circle.gif) no-repeat right');
		},
		open: function(event, ui) {
			$(this).css('background', '');
		}
	});
});
//end: jquery effects