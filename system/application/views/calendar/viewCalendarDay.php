<link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript" src="<?php echo JS_PATH ?>bootstrap-multiselect.js"></script>
<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
		<div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Volver</div>
			</button>
		</div>
		<div class="btn-group" role="group" aria-label="...">		
			<button id="copiar_fecha" data-toggle="modal" data-target="#copyModal" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Copiar fecha</div>
			</button>	
			<button id="borrar_fecha" data-toggle="modal" data-target="#myModal" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Borrar fecha</div>
			</button>
		</div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<form action="<?php echo FRONT_CONTROLLER ."calendar/viewCalendarDay/".$parameters['parameters'] ?>" method="post">
		<fieldset>
			<div class="row">
				<div class="col-xs-6">
					<select  class="form-control" id="display" name="display[]" multiple="multiple">
						<?php if(isset($select_content['displays'])): ?>
							<?php if($select_content['displays']<>""): ?>
								<?php foreach ($select_content['displays']  as $display):?>
									<option value=<?php echo $display['id'] ?>><?php echo $display['zoneName'] . " / " .  $display['storeName'] . " / " .  $display['name'] ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>
				</div> 
				<div class="col-xs-6">
					<select class="form-control" id="template" name="template">
						<option selected value="">Seleccione Agenda...</option>
						<?php if(isset($select_content['templates'])): ?>
							<?php if($select_content['templates']<>""): ?>
								<?php foreach ($select_content['templates']  as $template):?>
									<option value=<?php echo $template['id'] ?>><?php echo $template['name'] ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>
				</div>
			</div> 
			<div class="row top-buffer"> 
				<div class="col-xs-12">
					<input id="submit" class="form-control btn btn-info" type="submit" name="submit" value="Programar" />
				</div> 
			</div>
		</fieldset>
	</form>
	<?php if($data['contents']<>""): ?>
		<ul id="table_content" class="list-group top-buffer">
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-5">Display</div>
					<div class="col-xs-4">Agenda</div>
					<div class="col-xs-3">
						<div class="pull-right">Acciones</div>
					</div>
				</div> 
			</li>
			<?php foreach ($data['contents']  as $content):?>
				<li class="list-group-item">
					<div class="row">
						<div class="col-xs-5">
							<select name="selectDisplay" class="selectDisplay form-control" idtemplate=<?php echo $content['idScheduleTemplate'] ?>  data-id=<?php echo $content['id'] ?>>
									<?php foreach ($select_content['displays']  as $display):?>
										<?php if($display['id'] <> $content['idDisplay']): ?>
											<option value=<?php echo $display['id'] ?>><?php echo $display['zoneName'] . " / " .  $display['storeName'] . " / " .  $display['name'] ?></option>
										<?php else: ?>
											<option value=<?php echo $display['id'] ?> selected><?php echo $display['zoneName'] . " / " .  $display['storeName'] . " / " .  $display['name'] ?></option>
										<?php endif; ?>
									<?php endforeach; ?>
							</select>
						</div>
						<div class="col-xs-4">
							<select name="selectTemplate" class="selectTemplate form-control" iddisplay=<?php echo $content['idDisplay'] ?> data-id=<?php echo $content['id'] ?>>
									<?php foreach ($select_content['templates']  as $template):?>
										<?php if($template['id'] <> $content['idScheduleTemplate']): ?>
											<option value=<?php echo $template['id'] ?>><?php echo $template['name'] ?></option>
										<?php else: ?>
											<option value=<?php echo $template['id'] ?> selected><?php echo $template['name'] ?></option>
										<?php endif; ?>
									<?php endforeach; ?>
							</select>
						</div>
						<div class="col-xs-3">
							<div class="pull-right">
								<a href="#">
									<span class="delete" data-toggle="modal" data-target="#myModal" templateName="<?php echo $content['nameScheduleTemplate'] ?>" displayName="<?php echo $content['nameDisplay'] ?>" delId="<?php echo $content['id'] ?>">
									<span class="glyphicon glyphicon-remove"></span></span>
								</a>
							</div>
						</div>
					</div>
				</li>
			<?php endforeach ?>
		</ul>
	<?php endif; ?>
</div>
<script>
$(document).ready(function(){
	$('#display').multiselect({numberDisplayed: 1,nonSelectedText: 'Seleccione display...'});
	var params = {};
	var action = "";
	valarray = [];
	if ($(".selectDisplay").length>0) {
		$(".selectDisplay").each(function(){
			val = $(this).val();
			$("#display option[value=" + val + "]").hide();
			valarray.push(val);
		});
		$(".selectDisplay > option").each(function(){
			for (var i=0; i< valarray.length;i++) {
				if ($.inArray($(this).val(),valarray)!=-1)
					$(this).hide();
			}
		});
	}
	$(".delete").click(function(){
		params = {id:$(this).attr("delId")};
		action = "calendar/deleteScheduleCalendar";
		$("#myModalLabel").html('Remover Agenda Programada');
		$("#action_button").html('Remover');
		$("#delete_message").html("Está seguro que desea remover la agenda " + $(this).attr("templateName") + " del display " + $(this).attr("displayName") + " de la fecha seleccionada?");
	});	
	$("#borrar_fecha").click(function(){
		params = {date:"<?php echo $parameters['parameters'] ?>"};
		action = "calendar/deleteScheduleCalendarDate";
		$("#myModalLabel").html('Borrar fecha');
		$("#action_button").html('Borrar');
		$("#delete_message").html("Está seguro que desea borrar la configuración de la fecha seleccionada?");
	});					
	$("#action_button").click(function(){
		$.post('<?php echo FRONT_CONTROLLER ?>'+action,params,function(data){
			window.location.href = '<?php echo FRONT_CONTROLLER ."calendar/viewCalendarDay/".$parameters['parameters'] ?>';
		});
	});
	$(".selectDisplay").change(function(){
		dataId = $(this).attr("data-id");
		idDisplay = $(this).val();
		idTemplate = $(this).attr("idTemplate");
		day = <?php echo $calendar_data['day'] ?>;
		month = <?php echo $calendar_data['month'] ?>;
		$.post('<?php echo FRONT_CONTROLLER ."calendar/editScheduleCalendar" ?>',{id:dataId,idDisplay:idDisplay,idTemplate:idTemplate,day:day,month:month},function(data){$("#display").val("");window.location.href = window.location.href;
		});
	});
	$(".selectTemplate").change(function(){
		dataId = $(this).attr("data-id");
		idDisplay = $(this).attr("idDisplay");
		idTemplate = $(this).val();
		day = <?php echo $calendar_data['day'] ?>;
		month = <?php echo $calendar_data['month'] ?>;
		$.post('<?php echo FRONT_CONTROLLER ."calendar/editScheduleCalendar" ?>',{id:dataId,idDisplay:idDisplay,idTemplate:idTemplate,day:day,month:month},function(data){$("#display").val("");window.location.href = window.location.href;
		});
	});
	$("#volver").click(function(){
		location.href="<?php  echo FRONT_CONTROLLER ."calendar/viewCalendar/" ?>";
	});
	$("#copiar_fecha").click(function(){
		var arrDaysWithTemplate = <?php echo $arrDaysWithTemplate ?>;
		var selectedDate = "<?php echo $parameters['parameters'] ?>";
		var options = '<option value="">Seleccione fecha</option>';
		for (var i=0; i< arrDaysWithTemplate.length;i++) {
			if (arrDaysWithTemplate[i]!=selectedDate)
				options = options+'<option value="'+arrDaysWithTemplate[i]+'">'+arrDaysWithTemplate[i]+'</option>';
		}
		$("#copyModalLabel").html('Copiar fecha');
		$("#copy_button").html('Copiar');
		$("#copy_message").html('Va a eliminar la configuración actual y copiar la de la fecha seleccionada.<br><br><select id="selectDate" name="selectDate" class="selectDate form-control">'+options+'</select>');
	});
	$("#copy_button").click(function(){
		copyDate = $("#selectDate").val();
		var selectedDate = "<?php echo $parameters['parameters'] ?>";
		if (copyDate!=undefined && copyDate!=null && copyDate!="")
			$.post('<?php echo FRONT_CONTROLLER ."calendar/copyScheduleCalendar" ?>',{selectedDate:selectedDate,copyDate:copyDate},function(data){
				window.location.href = '<?php echo FRONT_CONTROLLER ."calendar/viewCalendarDay/".$parameters['parameters'] ?>';
			});
	});
});
</script>