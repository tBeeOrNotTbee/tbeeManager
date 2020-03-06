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
			<p>Usuario agregado con éxito.</p>
		</div>
	<?php endif; ?>
	<form action="<?php echo FRONT_CONTROLLER ."customer/newUser" ?>" method="post">
		<fieldset>	
			<label for="type">Tipo:</label>
			<select class="form-control" id="usrType" name="usrType">
				<option value=<?php echo ROLE_CUSTOMER_ADMIN ?>>Admin Cliente</option>
				<option value=<?php echo ROLE_STORE_ADMIN ?>>Admin Local</option>
				<option value=<?php echo ROLE_CONTENT_MANAGER ?>>Content Manager</option>
			</select>
			<div id="usrShop">
				<label class="top-buffer" for="type">Local:</label>
				<select id="selectShop" class="form-control" name="usrStore">				
				</select>
			</div>
			<label class="top-buffer" for="email">Email:</label>
			<input type="email" value="" id="email" name="email" class="form-control" />
			<label class="top-buffer" for="password">Contrase&ntilde;a:</label>
			<input id="password"  name="password" type="password" value="" class="form-control" >
			<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="crear" value="Crear" />
		</fieldset>
	</form>
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
$(document).ready(function(){
	$("#usrShop").hide();
	$("#usrType").change(function(){
		var $usrType = $("#usrType").val();
		var selectShop = $("#selectShop");
		if ($usrType == <?php echo ROLE_STORE_ADMIN ?>)
			$("#usrShop").show();
		else
			$("#usrShop").hide();
	});
	$.getJSON("<?php echo FRONT_CONTROLLER ?>store/getStores/<?php echo $data['idCustomer'] ?>",function(data){
		$("#selectShop").find('option').remove().end().append("<option value=>Seleccione un local</option>");
		$.each(data, function(key,val) {					
			$("#selectShop").append("<option value=" + val.id + ">" + val.name + "</option>");
		});
	});
	$("#volver").click(function(){
		location.href="<?php echo FRONT_CONTROLLER . 'customer/viewUsers' ?>";
	});
});
</script>