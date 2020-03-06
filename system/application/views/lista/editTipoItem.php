<link rel="stylesheet" href="<?php echo CSS_PATH?>pick-a-color-1.2.3.min.css">
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
			<p>Tipo Item editado con éxito.</p> 
		</div>
	<?php endif; ?>
	<form action="<?php echo FRONT_CONTROLLER ."lista/editTipoItem/". $parameters['parameters'] ?>" method="post">
		<label for="name">Nombre:</label>
		<input type="text" value="<?php echo $data_rx['name'] ?>" class="form-control" id="name" name="name" class="input" required/>	
		<label class="top-buffer" for="backgroundColor">Color de fondo:</label>	
		<input type="text" id="backgroundColor" name="backgroundColor" class="pick-a-color form-control" value="<?php echo $data_rx['backgroundColor'] ?>">
		<label class="top-buffer" for="fontColor">Color de fuente:</label>	
		<input type="text" id="fontColor" name="fontColor" class="pick-a-color form-control" value="<?php echo $data_rx['fontColor'] ?>">
		<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="crear" value="Editar" />
	</form>
</div>
<script src="<?php echo JS_PATH?>tinycolor-0.9.15.min.js"></script>
<script src="<?php echo JS_PATH?>pick-a-color-1.2.3.min.js"></script>
<script type="text/javascript">
$("document").ready(function(){
	$(".pick-a-color").pickAColor();
	$("#volver").click(function(){
		location.href="<?php echo FRONT_CONTROLLER ."lista/viewTipoItems/" ?>";
	});
});
</script>