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
	<form>
		<fieldset>
			<label for="name">Nombre:</label>
			<input type="text" class="form-control" required=required id="name" name="name" value="<?php echo $data['displays']['name'] ?>" />
			<label class="top-buffer" for="name">Vertical:</label>
			<input type="checkbox" id="vertical" class="form-control" name="vertical" <?php if($data['displays']['vertical']){ echo "checked"; } ?> />
			<label class="top-buffer" for="selectTemplate">Agenda:</label>					
			<select class="selectTemplate form-control" name="selectTemplate" id="selectTemplate" >				
				<?php if($idTemplateDefault): ?>
					<option value=<?php echo $idTemplateDefault; ?> ><?php echo $templateDefault ?></option>
				<?php else: ?>
					<option value="" >Seleccione...</option>						
				<?php endif; ?>
				<?php if(isset($dataTemplate['allScheduleTemplate'])): ?>
					<?php if($dataTemplate['allScheduleTemplate']<>""): ?>		
						<?php foreach ($dataTemplate['allScheduleTemplate'] as $templates): ?>
							<?php if( $templates['name'] <> $templateDefault): ?>
								<option value=<?php echo $templates['id'] ?>><?php echo $templates['name'] ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endif; ?>					
			</select>
			<label class="top-buffer" for="checkTime">Intervalo conexión:</label>
			<input type="number" class="form-control"  name="checkTime" id="checkTime" min="1" max="1440" value=<?php echo $data['displays']['checkTime'] ?>>
			<label class="top-buffer" for="reinicio">Reinicio automático:</label>
			<input  class="form-control"  type="checkbox" name="reinicio" id="reinicio" value="true" <?php if($data['displays']['autoReboot']){ echo " checked "; } ?> />
			<select class="reboot selectTemplate form-control top-buffer" name="selectDays" id="selectDays">				
				<option value="" >Seleccione días</option>	
				<option <?php if($data['displays']['rebootDay'] == ALL_DAYS){ echo " selected "; } ?>  value=<?php echo ALL_DAYS ?>>Todos los días</option>
				<option <?php if($data['displays']['rebootDay'] == MONDAY){ echo " selected "; } ?> value=<?php echo MONDAY ?>>Lunes</option>
				<option <?php if($data['displays']['rebootDay'] == TUESDAY){ echo " selected "; } ?> value=<?php echo TUESDAY ?>>Martes</option>
				<option <?php if($data['displays']['rebootDay'] == WEDNESDAY){ echo " selected "; } ?> value=<?php echo WEDNESDAY ?>>Miércoles</option>
				<option <?php if($data['displays']['rebootDay'] == THURSDAY){ echo " selected "; } ?> value=<?php echo THURSDAY ?>>Jueves</option>
				<option <?php if($data['displays']['rebootDay'] == FRIDAY){ echo " selected "; } ?> value=<?php echo FRIDAY ?>>Viernes</option>
				<option <?php if($data['displays']['rebootDay'] == SATURDAY){ echo " selected "; } ?> value=<?php echo SATURDAY ?>>Sábado</option>
				<option <?php if($data['displays']['rebootDay'] == SUNDAY){ echo " selected "; } ?>  value=<?php echo SUNDAY ?>>Domingo</option>
			</select>
			<label class="reboot top-buffer" for="rebootTime">Hora reinicio:</label>
            <div class='reboot input-group date' id='datetimepicker'>
                <input  id="rebootTime" name="rebootTime" type="text"  class="form-control" value=<?php echo $rebootTime ?> />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
			<input class="form-control btn btn-info top-buffer" type="submit" name="editar" id="editar" value="Editar" />
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
			location.href="<?php  echo FRONT_CONTROLLER ."display/viewDisplays/".$data['storeId'] ?>";
		});
		$(".reboot").hide();
		if ($("#reinicio").prop("checked"))
			$(".reboot").show();
		$("#reinicio").click(function(){
			$(".reboot").toggle();	
		});
		$("form").submit(function(e){
			e.preventDefault();
			name=$("#name").val();
			vertical=$("#vertical").prop('checked');
			reinicio=$("#reinicio").prop('checked');
			selectDays =$("#selectDays").val();
			rebootTime =$("#rebootTime").val();
			selectTemplate= $("#selectTemplate").val();
			checkTime= $("#checkTime").val();
			$.post('<?php echo FRONT_CONTROLLER ."display/editDisplay" ?>',{name:name,vertical:vertical,selectTemplate:selectTemplate,reinicio:reinicio,selectDays:selectDays,rebootTime:rebootTime,checkTime:checkTime,id:<?php echo $data['displays']['id'] ?>},function(data){
				location.href = "<?php echo FRONT_CONTROLLER ."display/viewDisplays/".$data['storeId'] ?>";
			});						
		});
	});
</script>