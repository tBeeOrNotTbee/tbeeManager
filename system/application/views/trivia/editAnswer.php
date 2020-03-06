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
			<p>Respuesta editada con éxito.</p>
		</div>
	<?php endif; ?>
	<form action="<?php echo FRONT_CONTROLLER ."trivia/editAnswer/". $parameters['parameters'] ?>" method="post">
		<label for="name">Respuesta:</label>
		<input type="text" value="<?php echo $data_rx['answer'] ?>" id="answer" name="answer" class="form-control" required />	
		<label class="top-buffer" for="correct">Correcta:</label>
		<input type="checkbox" <?php if($data_rx['correct']){ echo "checked=checked"; } ?> class="form-control" id="correct" name="correct" />
		<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="editar" value="Editar" />
	</form>
</div>
<script type="text/javascript">
$("document").ready(function(){
	$("#volver").click(function(){
		location.href="<?php echo FRONT_CONTROLLER ."trivia/viewAnswers/" . $data_rx['idTriviaQuestion'] ?>";
	});
});
</script>