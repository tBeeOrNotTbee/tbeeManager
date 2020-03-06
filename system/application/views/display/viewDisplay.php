<style>
	.clickable_row:hover{	
		cursor:pointer;
	}
	.table{	
		text-align:center;
		font-size:13px;
	}
</style>
<div id="content-main" class="round dropshadow"> 
	<nav class="bottom-buffer">
		<div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
		</div>
		<div class="btn-group" role="group" aria-label="...">		
			<button id="agenda_semanal" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Agenda Semanal</div>
			</button>
			<button id="acciones_remotas" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Acciones remotas</div>
			</button>			
		</div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<!-- TV Control -->	
	<div style="float:right">
		<span data-toggle="modal" data-target="#myModal" class="controlverify" id="reboot" accion="reboot" style="cursor:pointer;"><img class="controltv" src="<?php echo IMG_PATH."ic_autorenew_black_48dp_1x.png" ?>" /></span>
		<span data-toggle="modal" data-target="#myModal"  class="controlverify" id="shutdown" accion="shutdown" style="cursor:pointer;"><img class="controltv" src="<?php echo IMG_PATH."ic_highlight_off_black_48dp_1x.png" ?>" /></span>
		<span data-toggle="modal" data-target="#myModal"  class="controlverify" id="trash" accion="deleteContents" style="cursor:pointer;"><img class="controltv" src="<?php echo IMG_PATH."ic_delete_black_48dp_1x.png" ?>" /></span>
	</div>
	<div id="Tvcontrol" style="padding-top:0px;clear:both;margin-bottom:10px">	
		<div class="control" id="pause" style="cursor:pointer;text-align:center;"><img class="controltv" src="<?php echo IMG_PATH."ic_pause_black_48dp_1x.png" ?>" /></div>
		<div style="text-align:center">
			<span class="control" id="prev" style="cursor:pointer;width:30%"><img class="controltv" src="<?php echo IMG_PATH."ic_fast_rewind_black_48dp_1x.png" ?>" /></a></span>
			<span class="control" id="play" style="cursor:pointer;width:30%"><img class="controltv" src="<?php echo IMG_PATH."ic_play_arrow_black_48dp_1x.png" ?>" /></a></span>
			<span class="control" id="next" style="cursor:pointer;width:30%"><img class="controltv" src="<?php echo IMG_PATH."ic_fast_forward_black_48dp_1x.png" ?>" /></span></a></div>
		<div class="control" id="stop" style="cursor:pointer;text-align:center"><img class="controltv" src="<?php echo IMG_PATH."ic_stop_black_48dp_1x.png" ?>" /></a></div>
	</div>
	<div style="float:left">	
		<label for="selectTemplate">Agenda</label>
		<select class="selectTemplate form-control" name="selectTemplate" data-id=<?php echo $data['display']['id'] ?> >
			<?php if($data['display']['idTemplateDefault']): ?>
				<option value=<?php echo $data['display']['idTemplateDefault']; ?> ><?php echo $data['display']['templateDefault'] ?></option>
			<?php else: ?>
				<option value="" >Seleccione agenda...</option>						
			<?php endif; ?>
			<?php if(isset($dataTemplate['allScheduleTemplate'])): ?>
				<?php if($dataTemplate['allScheduleTemplate']<>""): ?>
					<?php foreach ($dataTemplate['allScheduleTemplate'] as $templates): ?>
						<?php if( $templates['name'] <> $data['display']['templateDefault']): ?>
							<option value=<?php echo $templates['id'] ?>><?php echo $templates['name'] ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>
		</select>	
	</div>	
	<div style="float:right">	
		<button id="agenda" type="button" class="navbtn btn btn-default">
			<div class="round button left dropshadow">Agenda Actual</div>
		</button>	
	</div>		
	<div class="clearfix"></div>
	<br>
	<div class="nowplaying label label-info" style="font-size:16px;">Now playing: <?php echo  $data['nowPlaying'] ?></div>				
	<?php if($data['contents']<>""): ?>
	<ul class="list-group top-buffer">		
		<li class="list-group-item">
			<div class="row">		
				<div class="col-xs-9">Contenido</div>
				<div class="col-xs-3">Comienzo</div>
			</div>
		</li>
		<?php foreach ($data['contents']  as $content):?>
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-9">
					<a href="<?php  echo FRONT_CONTROLLER ."display/viewBlockContents/" .$content['id']."/" .$idDisplay  ?>" ><?php echo $content['name'] ?></a>
				</div>
				<div class="col-xs-3">
					<?php echo $content['startHour'] .":". $content['startMinute'] ?>						
				</div>
			</div>
		</li>
		<?php endforeach ?>
	<?php else: ?>
		<div class="row top-buffer">	
			<div class="col-xs-12">
				<h4>Falta configurar la agenda</h4>
			</div>
		</div> 
	<?php endif; ?>
	<?php if($data['wall']<>""): ?>
		<br>
		<label>Wall</label>
		<div class="table-responsive">
			<table class="table table-condensed">
				<tr class="active" style="font-weight:bold">
					<td>Uptime</td>					
					<td>Última Conexión</td>							
					<td>IP</td>
					<td>Puerto</td>
					<td>Espacio libre</td>
					<td>Temperatura</td>
					<td>% Memoria</td>
					<td>% CPU</td>
					<td>Versión</td>			
				</tr>
					<?php foreach ($data['wall']  as $wall):?>
					<tr  
					<?php if ($wall['minutes']>60): ?>
						class="danger"
					<?php elseif ($wall['minutes']>30): ?>
						class="warning"
					<?php endif; ?>
					>
						<td>
							<?php echo $wall['uptime'] ?>
						</td>
						<td>
							<?php echo $wall['lastUpdate'] ?>
						</td>
						<td>
							<?php echo $wall['ip'] ?>
						</td>
						<td>
							<?php echo $wall['port']  ?>
						</td>						
						<td>
							<?php echo $wall['freeSpace'] ?>
						</td>
						<td>
							<?php echo $wall['temperature'] ?>
						</td>
						<td>
							<?php echo $wall['memPercentUsed'] ?>
						</td>	
						<td>
							<?php echo $wall['cpuUsage'] ?>
						</td>			
						<td>
							<?php echo $wall['WALLVersion'] ?>
						</td>						
					</tr>
				<?php endforeach ?>
			</table>
		</div>
	<?php endif; ?>
</div>
<script>
	function errorHandler(jqXHR,exception){
		if (jqXHR.status == 404) {
			$('.alert-modal-title').text("Error");
			$('#alert-modal-message').text("Requested page not found. [404]");
			$('#AlertModal').modal();
		} else if (jqXHR.status == 500) {
			$('.alert-modal-title').text("Error");
			$('#alert-modal-message').text("Internal Server Error [500]");
			$('#AlertModal').modal();
		} else if (exception === 'parsererror') {
			$('.alert-modal-title').text("Error");
			$('#alert-modal-message').text("Requested JSON parse failed.");
			$('#AlertModal').modal();
		} else if (exception === 'timeout') {
			$('.alert-modal-title').text("Error");
			$('#alert-modal-message').text("Time out error.");
			$('#AlertModal').modal();
		} else if (exception === 'abort') {
			$('.alert-modal-title').text("Error");
			$('#alert-modal-message').text('Ajax request aborted.');
			$('#AlertModal').modal();
		} 
	}
	function sendCommand(accion){
	$.get("http://<?php echo $connection ?>/" + accion ,function(data){
	}).error(function(jqXHR,exception) {	
		errorHandler(jqXHR.status);				
	});
	}
	$(document).ready(function(){
		$(".selectTemplate").change(function(){
			idDisplay = $(this).attr("data-id");
			idTemplate = $(this).val();
			$.post('<?php echo FRONT_CONTROLLER ."display/editDisplayDefaultTemplate" ?>',{idDisplay:idDisplay,idTemplate:idTemplate},function(data){
				window.location.href = window.location.href;
			});		
		});
		$(".control").click(function(){			
			sendCommand($(this).attr("id"));			
		});
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."display/viewDisplays/".$data['display']['idStore'] ?>";
		});
		$("#agenda").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."display/viewDisplayContent/".$idDisplay ?>";
		});
		$("#agenda_semanal").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."display/viewAgendaSemanal/".$idDisplay ?>";
		});
		$("#acciones_remotas").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."display/viewRemoteActions/".$idDisplay ?>";
		});
		$(".controlverify").click(function(){				
			accion = $(this).attr("accion");
			if (accion=="reboot") 
				txtAccion="Reiniciar";
			else if (accion=="shutdown") 
				txtAccion="Apagar";
			else if (accion=="deleteContents") 
				txtAccion="Eliminar";
			$("#myModalLabel").html(txtAccion + ' display');	
			$("#action_button").html(txtAccion);	
			if (accion!="deleteContents")
				$("#delete_message").html("Está seguro que desea "+ txtAccion.toLowerCase() + " el display?");		
			else
				$("#delete_message").html("Está seguro que desea eliminar el contenido del display?");
		});
		$("#action_button").click(function(){	
			sendCommand(accion);
			location.reload();	
		});
	});
</script>