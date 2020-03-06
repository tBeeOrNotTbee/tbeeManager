<link rel="stylesheet" href="<?php echo CSS_PATH.'bootstrap-datetimepicker.css'?>"/>
<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>	
	<?php if(isset($data['alert'])): ?>
		<div class="alert-message success">
			<a class="close" href="#">x</a>
			<p>Display agregado con éxito.</p>
		</div>
	<?php endif; ?>	
	<form action="<?php echo FRONT_CONTROLLER ."display/newDisplay/".$parameters['parameters'] ?>" method="post">
		<fieldset>	
			<label for="name">Nombre:</label>
			<input type="text" class="form-control" required=required value="" placeholder="Nuevo display" id="name" name="name"/>
			<label class="top-buffer" for="vertical">Vertical:</label>
			<input class="form-control" type="checkbox" name="vertical" id="vertical" value="true" />
			<select class="selectTemplate form-control top-buffer" name="selectTemplate">				
			<option value="">Seleccione Agenda</option>						
			<?php if($dataTemplate['allScheduleTemplate']): ?>
				<?php foreach ($dataTemplate['allScheduleTemplate'] as $templates): ?>
					<?php if( isset($data['templateDefault'])): ?>
						<?php if( $templates['name'] <> $data['templateDefault']): ?>
							<option value=<?php echo $templates['id'] ?>><?php echo $templates['name'] ?></option>
						<?php endif; ?>										
					<?php else: ?>
						<option value=<?php echo $templates['id'] ?>><?php echo $templates['name'] ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>					
			</select>
			<label class="top-buffer" for="checkTime">Intervalo conexión:</label>
			<input type="number" class="form-control"  name="checkTime" min="1" max="1440" value="1">
			<label class="top-buffer" for="reinicio">Reinicio automático:</label>
			<input  class="form-control"  type="checkbox" name="reinicio" id="reinicio" value="true" />
			<select class="reboot selectTemplate form-control top-buffer" name="selectDays" id="selectDays">				
				<option value="" >Seleccione días</option>	
				<option value=<?php echo ALL_DAYS ?>>Todos los días</option>
				<option value=<?php echo MONDAY ?>>Lunes</option>
				<option value=<?php echo TUESDAY ?>>Martes</option>
				<option value=<?php echo WEDNESDAY ?>>Miércoles</option>
				<option value=<?php echo THURSDAY ?>>Jueves</option>
				<option value=<?php echo FRIDAY ?>>Viernes</option>
				<option value=<?php echo SATURDAY ?>>Sábado</option>
				<option value=<?php echo SUNDAY ?>>Domingo</option>																	
			</select>
			<label class="reboot top-buffer" for="rebootTime">Hora reinicio:</label>
            <div class='reboot input-group date' id='datetimepicker'>
                <input  id="rebootTime" name="rebootTime" type="text"  class="form-control" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>		
			<input id="submit" class="form-control btn btn-info top-buffer"  type="submit" name="crear" value="Crear" />
		</fieldset>
	</form>	
</div>
<script src="<?php echo JS_PATH?>moment-with-locales.js"></script>
<script src="<?php echo JS_PATH?>bootstrap-datetimepicker.js"></script>
<script>
    $(function () {
        $('#datetimepicker').datetimepicker({format: 'HH:mm'});        
    });
	$(document).ready(function(){
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."display/viewDisplays/".$parameters['parameters'] ?>";
		});
		$(".reboot").hide();
		$("#reinicio").click(function(){
			$(".reboot").toggle();	
		});
	});
</script>