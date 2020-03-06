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
	<form action="<?php echo FRONT_CONTROLLER ."menu/newMenuItem/" ?>" method="post">
		<fieldset>					
			<label for="name">Titulo:</label>
			<input type="text" value="" id="title" name="title" class="form-control" />		
			<label class="top-buffer" for="name">Nombre:</label>
			<input type="text" value="" id="name" name="name" class="form-control" />	
			<label class="top-buffer" for="name">Descripción:</label>
			<input type="text" value="" id="description" name="description" class="form-control" />	
			<label class="top-buffer" for="name">Precio:</label>
			<input type="text" value="" id="price" name="price" class="form-control" />		
			<select  id="selectImage" name="selectImage"  class="form-control top-buffer">
				<option value=0>Seleccione Contenido</option>
				<?php foreach($data['customerImages'] as $image): ?>
					<option value=<?php echo $image['id'] ?>> <?php echo $image['name'] ?></option>
				<?php  endforeach; ?>
			</select>		
			<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="crear" value="Crear" />
		</fieldset>
	</form>
</div>
<script>
	$(document).ready(function(){
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."menu/listMenuItems/".$data['idCustomer'] ?>";
		});
	});
</script>