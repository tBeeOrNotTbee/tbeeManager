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
			<p>Pregunta creada con éxito.</p> 
		</div>
	<?php endif; ?>
	<form action="<?php echo FRONT_CONTROLLER ."trivia/newTriviaItem/" ?>" method="post">
		<label for="name">Pregunta:</label>
		<input type="text" value="" class="form-control" id="name" name="name" required />		
		<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="crear" value="Crear" />
	</form>
</div>
<script type="text/javascript">
$("document").ready(function(){
	$("#volver").click(function(){
		location.href="<?php echo FRONT_CONTROLLER ."trivia/viewTriviaItems/" ?>";
	});
});
</script>