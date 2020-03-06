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
			<p>Cliente agregado con éxito.</p>
		</div>
	<?php endif; ?>
	<form action="<?php echo FRONT_CONTROLLER ."admin/newCustomer" ?>" method="post">
		<fieldset>					
			<label for="name">Nombre:</label>
			<input type="text" value="" id="name" name="name" class="form-control"/>
			<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="crear" value="Crear"/>
		</fieldset>
	</form>
</div>
<script>
	$(document).ready(function(){	
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."admin/" ?>";
		});
	});
</script>