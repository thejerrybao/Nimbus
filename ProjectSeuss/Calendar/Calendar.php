<!DOCTYPE html>
<html>
<head>
	<?php include('../head.php') ?>
<meta charset='utf-8' />
<link href='../css/fullcalendar.css' rel='stylesheet' />
<link href='../css/fullcalendar.print.css' rel='stylesheet' media='print' />
<link href="../jquery-ui/jquery-ui.css" rel="stylesheet">
<script src='lib/moment.min.js'></script>
<script src='lib/jquery-ui.custom.min.js'></script>
<script src='../js/fullcalendar.js'></script>
<script src="/ProjectSeuss/jquery-ui/jquery-ui.js"></script>

<script>
	
    $(document).ready(function() {
		
		$('#calendar').fullCalendar({
    events: 'json/events.json.php',
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
	<div class="container" style="background-color:rgba(255,255,255,0.98); border-radius: 25px; width:75%">
	<div id='calendar' ></div>
	</div>

	<div class="modal fade" id="otherModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  		
	</div>



</body>
</html>
