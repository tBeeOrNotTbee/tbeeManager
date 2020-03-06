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
			<p>Trivia editado con éxito.</p>
		</div>
	<?php endif; ?>
	<form action="<?php echo FRONT_CONTROLLER ."trivia/editTrivia/". $parameters['parameters'] ?>" method="post">
		<label for="name">Nombre:</label>
		<input  type="text" value="<?php echo $data_rx['name'] ?>" id="name" name="name" class="form-control" required />
		<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="editar" value="Editar" />
	</form>
</div>
<script type="text/javascript">
$("document").ready(function(){
	$("#volver").click(function(){
		location.href="<?php echo FRONT_CONTROLLER ."trivia/index/" ?>";
	});
});
</script>