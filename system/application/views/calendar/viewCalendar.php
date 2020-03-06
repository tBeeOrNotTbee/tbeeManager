<link rel="stylesheet" href="<?php echo CSS_PATH.'fullcalendar.css'?>">
<script src="<?php echo JS_PATH.'moment.min.js'?>"></script>
<script src="<?php echo JS_PATH.'fullcalendar.js'?>"></script>
<script src="<?php echo JS_PATH.'fullcalendar-es.js'?>"></script>
<div id="content-main" class="round dropshadow">
	<div id="calendar"></div>
</div>
<style>
#calendar{
	cursor: pointer;
}
</style>
<script>
$(document).ready(function(){
	$('#calendar').fullCalendar({lang:'es',dayClick:function(date,allDay,jsEvent,view){
		if (allDay) {
			var moment = date;
			window.location.replace('<?php  echo FRONT_CONTROLLER ."calendar/viewCalendarDay/" ?>' + moment.format('DD-MM'));
		}		
	},dayRender:function(date,cell){
		arrDaysWithTemplate = <?php echo $arrDaysWithTemplate ?>;
		if ($.inArray(date.format("DD-MM"),arrDaysWithTemplate)!=-1)
			cell.css("background-color", "#C2E0C2");
	}});
});
</script>