<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>	
	<?php if(isset($data['alert'])): ?>
		<div class="alert-message success">
			<a class="close" href="#">x</a>
			<p>Bloque editado con éxito.</p>
		</div>
	<?php endif; ?>
	<form action="<?php echo FRONT_CONTROLLER ."schedule/editScheduleBlock/". $parameters['parameters'] ?>" method="post">
		<label for="name">Nombre:</label>
		<input type="text" class="form-control" required=required id="name" name="name" value="<?php echo $data['blockName'] ?>" />
		<input class="form-control btn btn-info top-buffer" type="submit" name="editar" id="editar" value="Editar" />
	</form>
</div>
<script>
	$(document).ready(function(){
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."schedule/viewCustomerScheduleBlocks/" ?>";
		});	
	});
</script>