<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
		<div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
		</div>
		<div class="btn-group" role="group" aria-label="...">		
			<button id="copiar_semana" data-toggle="modal" data-target="#copyModal" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Copiar semana</div>
			</button>
			<button id="vaciar_semana" data-toggle="modal" data-target="#myModal" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Vaciar semana</div>
			</button>
		</div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>	
	<ul class="list-group">		
		<li class="list-group-item">
			<div class="row">		
				<div class="col-xs-3">
					<strong>Lunes</strong>
				</div>
				<div class="col-xs-9">
					<select class="selectTemplate form-control" name="selectTemplateLunes" data-id=<?php echo MONDAY ?> >
						<option value="-1" selected>Sin asignar</option>						
						<?php if(isset($dataTemplate['allScheduleTemplate'])): ?>
							<?php if($dataTemplate['allScheduleTemplate']<>""): ?>
								<?php foreach ($dataTemplate['allScheduleTemplate'] as $templates): ?>
									<option <?php if($templates['id'] == $agendaSemanal[MONDAY]['idScheduleTemplate']){ echo " selected "; } ?>  value=<?php echo $templates['id'] ?>><?php echo $templates['name'] ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>
				</div>
			</div>
			<div class="row top-buffer">
				<div class="col-xs-3">
					<strong>Martes</strong>
				</div>
				<div class="col-xs-9">
					<select class="selectTemplate form-control" name="selectTemplateMartes" data-id=<?php echo TUESDAY ?> >
						<option value="-1" selected>Sin asignar</option>						
						<?php if(isset($dataTemplate['allScheduleTemplate'])): ?>
							<?php if($dataTemplate['allScheduleTemplate']<>""): ?>
								<?php foreach ($dataTemplate['allScheduleTemplate'] as $templates): ?>
									<option <?php if($templates['id'] == $agendaSemanal[TUESDAY]['idScheduleTemplate']){ echo " selected "; } ?>  value=<?php echo $templates['id'] ?>><?php echo $templates['name'] ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>	
				</div>
			</div>
			<div class="row top-buffer">
				<div class="col-xs-3">
					<strong>Miércoles</strong>
				</div>
				<div class="col-xs-9">
					<select class="selectTemplate form-control" name="selectTemplateMiercoles" data-id=<?php echo WEDNESDAY ?> >
						<option value="-1" selected>Sin asignar</option>						
						<?php if(isset($dataTemplate['allScheduleTemplate'])): ?>
							<?php if($dataTemplate['allScheduleTemplate']<>""): ?>
								<?php foreach ($dataTemplate['allScheduleTemplate'] as $templates): ?>
									<option <?php if($templates['id'] == $agendaSemanal[WEDNESDAY]['idScheduleTemplate']){ echo " selected "; } ?>  value=<?php echo $templates['id'] ?>><?php echo $templates['name'] ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>	
				</div>
			</div>
			<div class="row top-buffer">
				<div class="col-xs-3">
					<strong>Jueves</strong>
				</div>
				<div class="col-xs-9">
					<select class="selectTemplate form-control" name="selectTemplateJueves" data-id=<?php echo THURSDAY ?> >
						<option value="-1" selected>Sin asignar</option>						
						<?php if(isset($dataTemplate['allScheduleTemplate'])): ?>
							<?php if($dataTemplate['allScheduleTemplate']<>""): ?>
								<?php foreach ($dataTemplate['allScheduleTemplate'] as $templates): ?>
									<option <?php if($templates['id'] == $agendaSemanal[THURSDAY]['idScheduleTemplate']){ echo " selected "; } ?>  value=<?php echo $templates['id'] ?>><?php echo $templates['name'] ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>	
				</div>
			</div>
			<div class="row top-buffer">
				<div class="col-xs-3">
					<strong>Viernes</strong>
				</div>
				<div class="col-xs-9">
					<select class="selectTemplate form-control" name="selectTemplateViernes" data-id=<?php echo FRIDAY ?> >
						<option value="-1" selected>Sin asignar</option>						
						<?php if(isset($dataTemplate['allScheduleTemplate'])): ?>
							<?php if($dataTemplate['allScheduleTemplate']<>""): ?>
								<?php foreach ($dataTemplate['allScheduleTemplate'] as $templates): ?>
									<option <?php if($templates['id'] == $agendaSemanal[FRIDAY]['idScheduleTemplate']){ echo " selected "; } ?>  value=<?php echo $templates['id'] ?>><?php echo $templates['name'] ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>	
				</div>
			</div>
			<div class="row top-buffer">
				<div class="col-xs-3">
					<strong>Sábado</strong>
				</div>
				<div class="col-xs-9">
					<select class="selectTemplate form-control" name="selectTemplateSabado" data-id=<?php echo SATURDAY ?> >
						<option value="-1" selected>Sin asignar</option>						
						<?php if(isset($dataTemplate['allScheduleTemplate'])): ?>
							<?php if($dataTemplate['allScheduleTemplate']<>""): ?>
								<?php foreach ($dataTemplate['allScheduleTemplate'] as $templates): ?>
									<option <?php if($templates['id'] == $agendaSemanal[SATURDAY]['idScheduleTemplate']){ echo " selected "; } ?>  value=<?php echo $templates['id'] ?>><?php echo $templates['name'] ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>	
				</div>
			</div>
			<div class="row top-buffer">
				<div class="col-xs-3">
					<strong>Domingo</strong>
				</div>
				<div class="col-xs-9">
					<select class="selectTemplate form-control" name="selectTemplateDomingo" data-id=<?php echo SUNDAY ?> >
						<option value="-1" selected>Sin asignar</option>						
						<?php if(isset($dataTemplate['allScheduleTemplate'])): ?>
							<?php if($dataTemplate['allScheduleTemplate']<>""): ?>
								<?php foreach ($dataTemplate['allScheduleTemplate'] as $templates): ?>
									<option <?php if($templates['id'] == $agendaSemanal[SUNDAY]['idScheduleTemplate']){ echo " selected "; } ?>  value=<?php echo $templates['id'] ?>><?php echo $templates['name'] ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>	
				</div>																									
			</div>
		</li>
	</ul>
</div>
<script>
	$(document).ready(function(){
		$(".selectTemplate").change(function(){
			idWeekday = $(this).attr("data-id");
			idTemplate = $(this).val();
			if (idTemplate == '-1')
				$.post('<?php echo FRONT_CONTROLLER ."display/deleteAgendaSemanal" ?>',{idDisplay: <?php echo $idDisplay ?>, idWeekday:idWeekday},function(data){window.location.href = window.location.href;});	
			else
				$.post('<?php echo FRONT_CONTROLLER ."display/editAgendaSemanal" ?>',{idDisplay: <?php echo $idDisplay ?>, idWeekday:idWeekday,idTemplate:idTemplate},function(data){window.location.href = window.location.href;});
		});
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."display/viewDisplay/".$idDisplay ?>";
		});
		$("#copiar_semana").click(function(){
			var arrDisplays = <?php echo $arrDisplays ?>;
			var selectedDisplay = <?php echo $parameters['parameters'] ?>;
			var options = '<option value=0>Seleccione display</option>';
			for (var i=0; i< arrDisplays.length;i++) {
				if (arrDisplays[i].id!=selectedDisplay)
					options = options+'<option value="'+arrDisplays[i].id+'">'+arrDisplays[i].name+'</option>';
			}
			$("#copyModalLabel").html('Copiar semana');
			$("#copy_button").html('Copiar');
			$("#copy_message").html('Va a eliminar la configuración semanal actual y copiar la seleccionada.<br><br><select id="selectDisplay" name="selectDisplay" class="selectDisplay form-control">'+options+'</select>');
		});
		$("#copy_button").click(function(){
			copyDisplay = $("#selectDisplay").val();
			var selectedDisplay = <?php echo $parameters['parameters'] ?>;
			if (copyDisplay!=undefined && copyDisplay!=null && copyDisplay!=0)
				$.post('<?php echo FRONT_CONTROLLER ."display/copyScheduleWeek" ?>',{selectedDisplay:selectedDisplay,copyDisplay:copyDisplay},function(data){
					window.location.href = window.location.href;
				});
		});
		$("#vaciar_semana").click(function(){	
			$("#myModalLabel").html('Vaciar semana');
			$("#action_button").html('Vaciar');
			$("#delete_message").html("Está seguro que desea vaciar la configuración semanal seleccionada?");
		});					
		$("#action_button").click(function(){
			$.post('<?php echo FRONT_CONTROLLER ?>'+'display/emptyScheduleWeek',{id:<?php echo $parameters['parameters'] ?>},function(data){
				window.location.href = window.location.href;
			});
		});
	});
</script>