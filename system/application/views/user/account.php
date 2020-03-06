<div id="content-main" class="round dropshadow"> 	
	<?php if(isset($data['alert'])): ?>
		<div class="alert-message success">
			<a class="close" href="#">x</a>
			<p>Usuario modificado con éxito.</p>
		</div>
	<?php endif; ?>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<?php if($validate): ?>
		<form  action="<?php echo FRONT_CONTROLLER ."user/account/". $id ?>" method="post">
			<fieldset>
				<label for="email">Email</label>
				<input type="text" value="<?php echo $data['userData']['email'] ?>" name="email" class="form-control" />
				<label class="top-buffer" for="password">Contraseña</label>
				<input id="password"  name="password" type="password" value="" class="form-control" >
				<em>(Dejar en blanco para mantener la actual)</em>
				<input id="submit" type="submit" class="form-control btn btn-info top-buffer" name="login" value="Modificar" />
			</fieldset>
		</form>
	<?php else: ?>
		<div class="row">					
			<div class="col-xs-12">
				No tiene permisos para esta acción.
			</div>
		</div>
	<?php endif; ?>	
</div>
<script src="<?php echo JS_PATH.'bootstrap-show-password.min.js'?>"></script>
<script>
    $(function() {
        $('#password').password().on('show.bs.password', function(e) {
            $('#methods').prop('checked', true);
        }).on('hide.bs.password', function(e) {
            $('#methods').prop('checked', false);
        });
    });
</script>