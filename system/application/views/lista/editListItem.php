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
			<p>List Item editado con éxito.</p>
		</div>
	<?php endif; ?>
	<form action="<?php echo FRONT_CONTROLLER ."lista/editListItem/". $parameters['parameters'] ?>" method="post">
		<label for="name">Nombre:</label>
		<input type="text" value="<?php echo $data_rx['name'] ?>" id="name" name="name" class="form-control" required />
		<label class="top-buffer" for="name">Tipo Item:</label>
		<select id="selectTipoItem" class="form-control" name="idTipoItem" required>
			<option value="">Seleccione Tipo Item</option>
			<?php foreach($data['tipositem'] as $tipoitem): ?>
				<?php if( $tipoitem['id'] == $data_rx['idListItemType'] ): ?>
					<option value=<?php echo $tipoitem['id'] ?> selected><?php echo $tipoitem['name'] ?></option>
				<?php else: ?>
					<option value=<?php echo $tipoitem['id'] ?>><?php echo $tipoitem['name'] ?></option>
				<?php endif; ?>
			<?php endforeach ?>
		</select>		
		<label class="top-buffer" for="name">Iconos:</label>
		<br>
		<?php if(isset($data['iconos'])): ?>
			<?php if($data['iconos']<>""): ?>
				<?php foreach($data['iconos'] as $icono): ?>
					<?php if(in_array($icono['id'], $data_rx['icons'])): ?>
						<input id="icon<?php echo $icono['id'] ?>" type="checkbox" name="selectIconos[] ?>"  value="<?php echo $icono['id'] ?>" checked> <?php echo $icono['name'] ?></input>
						<br>
					<?php else: ?>
						<input id="icon<?php echo $icono['id'] ?>" type="checkbox" name="selectIconos[] ?>"  value="<?php echo $icono['id'] ?>" > <?php echo $icono['name'] ?></input>
						<br>					
					<?php endif; ?>
				<?php endforeach ?>
			<?php endif; ?>
		<?php endif; ?>		
		<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="editar" value="Editar" />
	</form>
</div>
<script type="text/javascript">
$("document").ready(function(){
	$("#volver").click(function(){
		location.href="<?php echo FRONT_CONTROLLER ."lista/viewListItems/" ?>";
	});
});
</script>