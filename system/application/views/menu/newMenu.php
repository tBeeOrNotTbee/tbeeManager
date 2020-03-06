<link rel="stylesheet" href="<?php echo CSS_PATH.'bootstrap-datetimepicker.css'?>"/>
<link rel="stylesheet" href="<?php echo CSS_PATH.'pick-a-color-1.2.3.min.css'?>"/>
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
			<p>Menu agregado con éxito.</p> 
		</div>
	<?php endif; ?>
	<form action="<?php echo FRONT_CONTROLLER ."menu/newMenu/" ?>" method="post">
		<fieldset>
			<label for="name">Nombre de menú:</label>
			<input type="text" value="" class="form-control" id="name" name="name" required/>
			<label class="top-buffer" for="startHour">Hora de inicio:</label>
	        <div class='input-group date' id='datetimepicker'>
	            <input  id="startHour" name="startHour" type="text"  class="form-control" />
	            <span class="input-group-addon">
	                <span class="glyphicon glyphicon-time"></span>
	            </span>
	        </div>			
			<select id="selectBackgroundType" class="form-control top-buffer" name="selectBackgroundType">
				<option value=0>Tipo de fondo...</option>
				<option value=1>Imagen de fondo</option>	
				<option value=2>Color de fondo</option>
			</select>	
			<select  id="selectImage"  class="form-control top-buffer" name="selectImage">
				<option value=0>Imagenes...</option>
					<?php foreach($data['customerImages'] as $image): ?>
						<option value=<?php echo $image['id'] ?>> <?php echo $image['name'] ?></option>
					<?php endforeach; ?>
			</select>
			<div class="top-buffer">
				<input type="text" id="colorPicker" name="colorPicker" class="pick-a-color form-control">
			</div>
			<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="crear" value="Crear" />
		<fieldset>
	</form>
</div>
<script src="<?php echo JS_PATH?>tinycolor-0.9.15.min.js"></script>
<script src="<?php echo JS_PATH?>pick-a-color-1.2.3.min.js"></script>
<script src="<?php echo JS_PATH?>moment-with-locales.js"></script>
<script src="<?php echo JS_PATH?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
	$(function () {
	    $('#datetimepicker').datetimepicker({format: 'HH:mm'});        
	});
	$("document").ready(function(){
		$(".pick-a-color").pickAColor();
		$("#selectImage").hide();
		$("#colorPicker").hide();
		$("#selectBackgroundType").change(function(){
			if ($("#selectBackgroundType").val()=="1") {
				$("#selectImage").show();
				$("#colorPicker").hide();
				$("#colorPicker").val("");
			} else if ($("#selectBackgroundType").val()=="2") {
				$("#selectImage").hide();
				$("#selectImage").val("");
				$("#colorPicker").show();			
			} else {
				$("#selectImage").hide();
				$("#colorPicker").hide();
				$("#selectImage").val("");	
				$("#colorPicker").val("");		
			}
		});
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."menu/viewMenus/" .$data['idCustomer'] ?>";
		});
	});
</script>