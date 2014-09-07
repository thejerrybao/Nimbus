<!DOCTYPE html>
<html>
<head>
	<?php include('../head.php') ?>
<meta charset='utf-8' />
<link href='/~circlek/css/fullcalendar.css' rel='stylesheet' />
<link href='/~circlek/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<link href="/~circlek/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src='lib/moment.min.js'></script>
<script src='lib/jquery-ui.custom.min.js'></script>
<script src='/~circlek/js/fullcalendar.js'></script>
<script src="/~circlek/jquery-ui/jquery-ui.js"></script>

<script>
	//function to calculate window height
function get_calendar_height() {
      return $(window).height() - 30;
}

//attacht resize event to window and set fullcalendar height property
$(document).ready(function() {
$(window).resize(function() {
    $('#calendar').fullCalendar('option', 'height', get_calendar_height());
});
//set fullcalendar height property

	$('#calendar').fullCalendar({
    events: '/~circlek/Calendar/events.json.php',
height: get_calendar_height,
    'default': true,
    eventClick: function ( event, jsEvent, view ) {

    	$("#otherModal").load("CalEventInfo.php?id="+event.id);
        $('#otherModal').modal('show');
        }
 

});   
		
	});
</script>
<style>

	body {
		margin: 0;
		padding: 0;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		font-size: 14px;
	}

	#calendar {
		width: 900px;
		margin: 40px auto;
	}

</style>





</head>
<body>
	
	<div id='calendar' style="background-color:rgba(255,255,255,0.98); border-radius: 25px; width:75%; margin-bottom: 25px; padding: 10px 10px 10px 10px;"></div>
	</div>

	<div class="modal fade" id="otherModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  		
	

<? include("../footer.php") ?>
</body>
</html>
