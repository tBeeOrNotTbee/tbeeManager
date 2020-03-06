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
			<p>List Item agregado con éxito.</p> 
		</div>
	<?php endif; ?>
	<form action="<?php echo FRONT_CONTROLLER ."lista/newListItem/" ?>" method="post">
		<label for="name">Nombre:</label>
		<input type="text" value="" class="form-control" id="name" name="name" required/>		
		<label class="top-buffer" for="name">Tipo Item:</label>
		<select id="selectTipoItem" class="form-control" name="idTipoItem" required>
			<option value="">Seleccione Tipo Item</option>
			<?php if(isset($data['tipositem']) and !empty($data['tipositem'])): ?>
				<?php foreach($data['tipositem'] as $tipoitem): ?>
					<option value=<?php echo $tipoitem['id'] ?>><?php echo $tipoitem['name'] ?></option>
				<?php endforeach ?>
			<?php endif; ?>
		</select>		
		<label class="top-buffer" for="name">Iconos:</label>
		<br>
		<?php if(isset($data['iconos']) and !empty($data['iconos'])): ?>
			<?php foreach($data['iconos'] as $icono): ?>
				<input id="icon<?php echo $icono['id'] ?>" type="checkbox" name="selectIconos[] ?>"  value="<?php echo $icono['id'] ?>"> <?php echo $icono['name'] ?></input>
				<br>
			<?php endforeach ?>
		<?php endif; ?>
		<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="crear" value="Crear" />
	</form>
</div>
<script type="text/javascript">
$("document").ready(function(){
	$("#volver").click(function(){
		location.href="<?php echo FRONT_CONTROLLER ."lista/viewListItems/" ?>";
	});
});
</script>