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
			<p>Zona agregada con éxito.</p>
		</div>
	<?php endif; ?>
	<form action="<?php echo FRONT_CONTROLLER ."zone/newZone/".$parameters['parameters'] ?>" method="post">
		<fieldset>
			<label for="name">Nombre:</label>
			<input type="text" class="form-control" required=required value="" placeholder="Nueva zona" id="name" name="name" />
			<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="crear" value="Crear" />
		</fieldset>
	</form>
</div>
<script>
	$(document).ready(function(){
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."zone/viewZones/".$parameters['parameters'] ?>";
		});
	});
</script>