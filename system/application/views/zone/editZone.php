<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<form>
		<fieldset>	
			<label for="name">Nombre:</label>
			<input type="text" class="form-control" required=required id="name" name="name" value="<?php echo $data['zone']['name'] ?>" />
			<input class="form-control btn btn-info top-buffer" type="submit" name="editar" id="editar" value="Editar" />
		</fieldset>
	</form>
</div>
<script>
	$(document).ready(function(){
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."zone/viewZones/".$data['zone']['idCustomer'] ?>";
		});
		$("form").submit(function(e){
			e.preventDefault();
			var name = $("#name").val();
			$.post('<?php echo FRONT_CONTROLLER ."zone/editZone" ?>',{name:name,id:<?php echo $data['zone']['id'] ?>},function(data){
				location.href = "<?php echo FRONT_CONTROLLER ."zone/viewZones/".$data['zone']['idCustomer'] ?>";
			});						
		});
	});
</script>