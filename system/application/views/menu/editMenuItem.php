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
			<p>Menú Item editado con éxito.</p>
		</div>
	<?php endif; ?>
	<form action="<?php echo FRONT_CONTROLLER ."menu/editMenuItem/". $parameters['parameters'] ?>" method="post">
		<fieldset>
			<label for="title">Titulo:</label>
			<input type="text"  id="title" name="title" class="form-control" value="<?php echo $data_rx['title'] ?>" />	
			<label class="top-buffer" for="name">Nombre:</label>
			<input type="text" id="name" name="name" class="form-control" value="<?php echo $data_rx['name'] ?>" />	
			<label class="top-buffer" for="description">Descripción:</label>
			<input type="text" id="description" name="description" class="form-control" value="<?php echo $data_rx['description'] ?>" />		
			<label class="top-buffer" for="price">Precio:</label>
			<input type="text" id="price" name="price" class="form-control" value="<?php echo $data_rx['price'] ?>" />	
			<label class="top-buffer" for="selectImage">Contenido:</label>		
			<select  class="form-control"  id="selectImage" name="selectImage">
				<option value=0>Seleccionar...</option>
				<?php foreach($data['customerImages'] as $image): ?>		
					<?php if($image['id'] == $data_rx['idContent']): ?>
						<option selected value=<?php echo $image['id'] ?>> <?php echo $image['name'] ?></option>
					<?php else: ?>					
						<option value=<?php echo $image['id'] ?>> <?php echo $image['name'] ?></option>
					<?php endif; ?>
				<?php  endforeach; ?>
			</select>
			<input id="submit" class="form-control btn btn-info top-buffer"  type="submit" name="editar" value="Editar" />			
		</fieldset>
	</form>
</div>
<script type="text/javascript">
	$("#volver").click(function(){
		location.href="<?php  echo FRONT_CONTROLLER ."menu/listMenuItems/" ?>";
	});
</script>