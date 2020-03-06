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
			<label for="selectZone">Zona:</label>
			<select class="form-control" id="selectZone" name="selectZone" data-id=<?php echo $data['zoneId'] ?> >
				<?php foreach ($data['zones'] as $zone): ?>
					<?php if( $data['zoneId'] == $zone['id']): ?>
						<option selected value=<?php echo $zone['id'] ?>><?php echo $zone['name'] ?></option>
					<?php else: ?>
						<option value=<?php echo $zone['id'] ?>><?php echo $zone['name'] ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
			<label class="top-buffer" for="name">Nombre:</label>
			<input type="text" class="form-control" required=required id="name" name="name" value="<?php echo $data['store']['name'] ?>" />
			<input class="form-control btn btn-info top-buffer"  type="submit" name="editar" id="editar" value="Editar" />
		</fieldset>
	</form>
</div>
<script>
	$(document).ready(function(){
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."store/viewStores/".$data['zoneId'] ?>";
		});
		$("form").submit(function(e){
			e.preventDefault();
			name=$("#name").val();
			zone=$("#selectZone").val();
			$.post('<?php echo FRONT_CONTROLLER ."store/editStore" ?>',{zona: zone, name:name,id:<?php echo $data['store']['id'] ?>},function(data){
				location.href = "<?php echo FRONT_CONTROLLER ."store/viewStores/".$data['zoneId'] ?>";
			});						
		});	
	});
</script>