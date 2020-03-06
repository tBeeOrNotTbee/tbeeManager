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
			<p>URP modificada con éxito.</p>
		</div>
	<?php endif; ?>
	<form id="formUrp" action="<?php echo FRONT_CONTROLLER ."admin/editURP/".$parameters['parameters'] ?>" method="post" >
		<fieldset>
			<label for="selectCustomer">Cliente:</label>
			<select id="selectCustomer" name="selectCustomer" class="form-control">
			<option value=>Seleccione un cliente</option>
				<?php if(isset($data['customers'])): ?>
					<?php if($data['customers']<>""): ?>
						<?php foreach($data['customers'] as $customer): ?>
							<?php if( $customer['id'] == $data['urp']['idCustomer'] ): ?>
								<option selected value=<?php echo $customer['id'] ?>><?php echo $customer['name'] ?></option>
							<?php else: ?>
								<option value=<?php echo $customer['id'] ?>><?php echo $customer['name'] ?></option>
							<?php endif; ?>
						<?php endforeach ?>
					<?php endif; ?>
				<?php endif; ?>
			</select>		
			<label class="top-buffer" for="name">Nombre:</label>
			<input type="text" value="<?php echo $data['urp']['name']; ?>" id="name" name="name" class="form-control"/>
			<label class="top-buffer" for="mac">MAC address:</label>
			<input type="text" value="<?php echo $data['urp']['macAddress'] ?>" id="mac" name="mac" class="form-control" placeholder="00:00:00:00:00:00" />
			<label class="top-buffer" for="videoWall">Wall:</label>
			<input class="form-control" type="checkbox" name="videoWall" id="videoWall" value="true" <?php if($data['urp']['videoWall']){ echo "checked"; } ?> />
			<input type="hidden" id="idUrp" name="idUrp" value="<?php echo $data['urp']['id'] ?> ">
			<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="modificar" value="Modificar"/>
		</fieldset>
	</form>
</div>
<script>
	$(document).ready(function(){
		$("#formUrp").submit(function(e){
			var error = false;
			var mac = $("#mac").val();
			var regex = /^([0-9a-fA-F]{2}[:-]){5}([0-9a-fA-F]{2})$/;
			if (!$("#selectCustomer").val()) {
				$('.alert-modal-title').text("Error");
				$('#alert-modal-message').text("Debe seleccionar un cliente");
				$('#AlertModal').modal();
				error=true;			
			}
			if (!$("#name").val()) {
				$('.alert-modal-title').text("Error");
				$('#alert-modal-message').text("Debe ingresar un nombre");
				$('#AlertModal').modal();
				error=true;			
			}
			if (!regex.test(mac)) {
				$('.alert-modal-title').text("Error");
				$('#alert-modal-message').text("La MAC ingresada es incorrecta");
				$('#AlertModal').modal();
				error=true;
			}
			if (error)
				e.preventDefault();
		});
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."admin/viewURPDetails/" . $data['urp']['id'] ?>";
		});
	});
</script>