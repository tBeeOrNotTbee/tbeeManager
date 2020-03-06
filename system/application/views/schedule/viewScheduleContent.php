<link rel="stylesheet" href="<?php echo CSS_PATH.'bootstrap-datetimepicker.css'?>"/>
<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
		<div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Volver</div>
			</button>
		</div>
		<div class="btn-group" role="group" aria-label="...">		
			<button id="agenda_completa" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Ver agenda completa</div>
			</button>
			<button id="copiar_agenda" data-toggle="modal" data-target="#copyModal" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Copiar agenda</div>
			</button>
			<button id="vaciar_agenda" data-toggle="modal" data-target="#myModal" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Vaciar agenda</div>
			</button>
			<button id="cambiar_bloque" data-toggle="modal" data-target="#replaceModal" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Reemplazar bloque</div>
			</button>
		</div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<form action="<?php echo FRONT_CONTROLLER ."schedule/viewScheduleContent/".$parameters['parameters'] ?>" method="post" >
		<fieldset>
			<div class="row">
				<div class="col-xs-4 col-md-5">
					<select id="selectContent" class="form-control input" name="idScheduleBlock" required>
						<option value="">Seleccione Bloque</option>
						<?php if(isset($data['newcontents'])): ?>
							<?php if($data['newcontents']<>""): ?>
								<?php foreach($data['newcontents'] as $dataCustomer): ?>
									<option value=<?php echo $dataCustomer['id'] ?>><?php echo $dataCustomer['name'] ?></option>
								<?php endforeach ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>
				</div>	
				<div class="col-xs-5 col-md-5">
		            <div class='input-group date' id='datetimepicker'>
		                <input  id="timepicker" name="timepicker" type="text"  class="form-control" required />
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-time"></span>
		                </span>
		            </div>
		        </div>
		        <div class="col-xs-3 col-md-2">                						 
					<input id="submit" class="form-control btn btn-info" type="submit" name="submit" value="Agregar" />
				</div>
		</fieldset>
	</form>
	<?php if($data['contents']<>""): ?>
	<ul class="list-group">
		<li class="list-group-item" style="border:none">
			<div class="row">		
				<div class="col-xs-5">Bloques</div>
				<div class="col-xs-3">Hora Inicio</div>
				<div class="col-xs-4" >
					<div class="pull-right" >
						Acciones
					</div>
				</div>
			</div>
		</li>
		<?php foreach ($data['contents']  as $content):?>
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-5">
					<a href="<?php echo FRONT_CONTROLLER ."schedule/editScheduleBlockItems/".$content['id'] ?>"><?php echo $content['name'] ?></a>
				</div>
				<div class="col-xs-3">
					<?php echo $content['startHour'].":".$content['startMinute'] ?>
				</div>
				<div class="col-xs-4">
					<span class="pull-right">
						<a href="#">
							<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $content['name'] ?>" delId="<?php echo $content['idScheduleBlockTemplate'] ?>"> 
								<span class="glyphicon glyphicon-remove"></span>
							</span>
						</a>
					</span>
				</div>
			</div>		
		</li>
		<?php endforeach ?>
	</ul>	
	<?php else: ?>
		<div class="row">
			<div class="col-xs-12">
				No se obtuvieron resultados
			</div>
		</div>
	<?php endif; ?>
</div>
<script src="<?php echo JS_PATH?>moment-with-locales.js"></script>
<script src="<?php echo JS_PATH?>bootstrap-datetimepicker.js"></script>
<script>
    $(function () {
        $('#datetimepicker').datetimepicker({format: 'HH:mm'});  
    });
	$(document).ready(function(){
		var params = {};
		var action = "";
		$("#submit").click(function(){
			$("form").submit();
		});
		$(".delete").click(function(){				
			params = {id:$(this).attr("delId")};
			action = "schedule/removeBlockFromSchedule";		
			$("#myModalLabel").html('Eliminar de programación');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea remover de la agenda el bloque " + $(this).attr("delName") + "?");		
		});
		$("#vaciar_agenda").click(function(){
			params = {id:<?php echo $parameters['parameters'] ?>};
			action = "schedule/removeScheduleBlocks";	
			$("#myModalLabel").html('Vaciar agenda');
			$("#action_button").html('Vaciar');
			$("#delete_message").html("Está seguro que desea vaciar la agenda seleccionada?");
		});					
		$("#action_button").click(function(){
			$.post('<?php echo FRONT_CONTROLLER ?>'+action,params,function(data){
				window.location.href = '<?php echo FRONT_CONTROLLER ."schedule/viewScheduleContent/".$parameters['parameters'] ?>';
			});
		});
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."schedule/viewScheduleTemplate/".$parameters['parameters'] ?>";
		});
		$("#agenda_completa").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."schedule/viewFullScheduleContent/".$parameters['parameters'] ?>";
		});		
		$("#programar_bloque").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."schedule/viewCustomerScheduleBlocks/".$parameters['parameters'] ?>";
		});
		$("#copiar_agenda").click(function(){
			var arrSchedules = <?php echo $arrSchedules ?>;
			var selectedSchedule = <?php echo $parameters['parameters'] ?>;
			var options = '<option value=0>Seleccione agenda</option>';
			for (var i=0; i< arrSchedules.length;i++) {
				if (arrSchedules[i].id!=selectedSchedule)
					options = options+'<option value="'+arrSchedules[i].id+'">'+arrSchedules[i].name+'</option>';
			}
			$("#copyModalLabel").html('Copiar agenda');
			$("#copy_button").html('Copiar');
			$("#copy_message").html('Va a eliminar la agenda actual y copiar la agenda seleccionada.<br><br><select id="selectSchedule" name="selectSchedule" class="selectSchedule form-control">'+options+'</select>');
		});
		$("#copy_button").click(function(){
			var copySchedule = $("#selectSchedule").val();
			var selectedSchedule = <?php echo $parameters['parameters'] ?>;
			if (copySchedule!=undefined && copySchedule!=null && copySchedule!=0)
				$.post('<?php echo FRONT_CONTROLLER ."schedule/copySchedule" ?>',{selectedSchedule:selectedSchedule,copySchedule:copySchedule},function(data){
					window.location.href = '<?php echo FRONT_CONTROLLER ."schedule/viewScheduleContent/".$parameters['parameters'] ?>';
				});
		});
		$("#cambiar_bloque").click(function(){
			var arrBlocks = <?php echo $arrBlocks ?>;
			var arrScheduleBlocks = <?php echo $arrScheduleBlocks ?>;
			var selectedSchedule = <?php echo $parameters['parameters'] ?>;
			var optionsBlocks = '<option value=0>Seleccione bloque</option>';
			for (var i=0; i< arrBlocks.length;i++) {
				optionsBlocks = optionsBlocks+'<option value="'+arrBlocks[i].id+'">'+arrBlocks[i].name+'</option>';
			}
			var optionsScheduleBlocks = '<option value=0>Seleccione bloque</option>';
			for (var i=0; i< arrScheduleBlocks.length;i++) {
				optionsScheduleBlocks = optionsScheduleBlocks+'<option value="'+arrScheduleBlocks[i].id+'">'+arrScheduleBlocks[i].name+'</option>';
			}
			$("#replaceModalLabel").html('Reemplazar bloque');
			$("#replace_button").html('Reemplazar');
			$("#replace_message").html('Va a reemplazar todos los bloques de la agenda actual que seleccione:<br><br><select id="selectBlock" name="selectBlock" class="selectBlock form-control">'+optionsBlocks+'</select><br>Por:<br><br><select id="selectScheduleBlocks" name="selectScheduleBlocks" class="selectScheduleBlocks form-control">'+optionsScheduleBlocks+'</select>');
		});
		$("#replace_button").click(function(){
			var idBlock = $("#selectBlock").val();
			var idNewBlock = $("#selectScheduleBlocks").val();
			var selectedSchedule = <?php echo $parameters['parameters'] ?>;
			if (idBlock!=undefined && idBlock!=null && idBlock!=0 && idNewBlock!=undefined && idNewBlock!=null && idNewBlock!=0)
				$.post('<?php echo FRONT_CONTROLLER ."schedule/replaceScheduleBlock" ?>',{selectedSchedule:selectedSchedule,idBlock:idBlock,idNewBlock:idNewBlock},function(data){
					window.location.href = '<?php echo FRONT_CONTROLLER ."schedule/viewScheduleContent/".$parameters['parameters'] ?>';
				});
		});
	});
</script>