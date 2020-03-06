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
		<?php if($data['alert']): ?>
			<div class="alert-message success">
				<a class="close" href="#">x</a>
				<p>URP agregada con éxito.</p>
			</div>
		<?php else: ?>
			<div class="alert-message error">
				<a class="close" href="#">x</a>
				<p>La MacAddress ingresada ya se encuentra utilizada.</p>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<form id="formURP" action="<?php echo FRONT_CONTROLLER ."admin/newURP/".$parameters['parameters'] ?>" method="post">
		<fieldset>
			<label for="selectCustomer">Cliente:</label>
			<select id="selectCustomer" name="selectCustomer" class="form-control">
				<option value=>Seleccione un cliente</option>
				<?php if(isset($data['customers'])): ?>
					<?php if($data['customers']<>""): ?>
						<?php foreach($data['customers'] as $data): ?>
							<option value=<?php echo $data['id'] ?>><?php echo $data['name'] ?></option>
						<?php endforeach ?>
					<?php endif; ?>
				<?php endif; ?>
			</select>		
			<label class="top-buffer" for="name">Nombre:</label>
			<input type="text" value="" id="name" name="name" class="form-control" />
			<label class="top-buffer" for="mac">MAC address:</label>
			<input type="text" value="" id="mac" name="mac" class="form-control" placeholder="00:00:00:00:00:00" />
			<label class="top-buffer" for="videoWall">Wall:</label>
			<input  class="form-control"  type="checkbox" name="videoWall" id="videoWall" value="true" />
			<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="crear" value="Crear" />
		</fieldset>
	</form>
</div>
<script>
	$(document).ready(function(){
		$("#formURP").submit(function(e){
			var error = false;
			var mac = $("#mac").val();
			var regex = /^([0-9a-fA-F]{2}[:-]){5}([0-9a-fA-F]{2})$/;
			if (!$("#selectCustomer").val()) {
				$('.alert-modal-title').text("Error");
				$('#alert-modal-message').text("Debe seleccionar un cliente");
				$('#AlertModal').modal();
				error=true;			
			}
			if (!regex.test(mac)) {
				$('.alert-modal-title').text("Error");
				$('#alert-modal-message').text("La MAC ingresada es incorrecta");
				$('#AlertModal').modal();
				error=true;
			};
			if (error)
				e.preventDefault();
		});
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."admin/viewURPs" ?>";
		});
	});
</script>