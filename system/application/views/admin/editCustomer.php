<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<form action="<?php echo FRONT_CONTROLLER ."admin/editCustomer" ?>" method="post">
		<fieldset>					
			<label for="name">Nombre:</label>
			<input type="text" value="<?php echo $data['customer']['name']; ?>" id="name" name="name" class="form-control"/>
			<input type="hidden" id="id" name="id" value="<?php echo $data['customer']['id'] ?> ">
			<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="submit" value="Editar"/>
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