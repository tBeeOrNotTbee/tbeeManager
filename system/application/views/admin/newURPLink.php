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
			<p>Enlace agregado con éxito.</p>
		</div>
	<?php endif; ?>
	<select id="selectCustomer" name="selectCustomer" class="form-control">
		<option value=>Seleccione un cliente</option>
		<?php if(isset($data['customers'])): ?>
			<?php if($data['customers']<>""): ?>
				<?php foreach($data['customers'] as $dataCustomer): ?>
					<option value=<?php echo $dataCustomer['id'] ?>><?php echo $dataCustomer['name'] ?></option>
				<?php endforeach ?>
			<?php endif; ?>
		<?php endif; ?>
	</select>
	<form action="<?php echo FRONT_CONTROLLER ."admin/newURPLink" ?>" method="post">
		<label class="top-buffer" for="selectURPsNotUsed">URP sin usar:</label>
		<select id="selectURPsNotUsed" name="selectURPsNotUsed" class="form-control">			
		</select>
		<label class="top-buffer" for="selectDisplayNotUser">Display:</label>
		<select id="selectDisplayNotUser" name="selectDisplayNotUser" class="form-control">				
		</select>
		<input id="submit" class="form-control btn btn-info top-buffer" type="submit" name="submit" value="Enlazar" />
	</form>
</div>
<script>
	$(document).ready(function(){
	<?php if(!empty($data['idCustomer'])){ ?>
		var idcustomer = <?php echo $data['idCustomer'] ?>;
		$("#selectCustomer").val(idcustomer);
		$.post("<?php echo FRONT_CONTROLLER ?>admin/getCustomerURPsNotUsed/" + idcustomer,function(data){
			$("#tableContent").html(data);
		});
	<?php } ?>
		$("#submit").click(function(e){				
			if (!($("#selectURPsNotUsed").val()) || !($("#selectDisplayNotUser").val())) {
				$('.alert-modal-title').text("Error");
				$('#alert-modal-message').text("Debe ingresar URP y Display para enlazar");
				$('#AlertModal').modal();	
				return false;
			}
		});
		$("#selectCustomer").change(function(){
			$.getJSON("<?php echo FRONT_CONTROLLER ?>admin/getCustomerURPsNotUsed/" + $("#selectCustomer").val(),function(data){
				if (data.length==0)
					text = "Sin URPs libres";
				else
					text = "Seleccione una URP";
				$("#selectURPsNotUsed").find('option').remove().end().append("<option value=>" + text + "</option>");
				$.each(data, function(key,val) {					
						$("#selectURPsNotUsed").append("<option value=" + val.id + ">" + val.name + "</option>");
					});					
			});
			$.getJSON("<?php echo FRONT_CONTROLLER ?>admin/getCustomerDisplaysNotUsed/" + $("#selectCustomer").val(),function(data){			
				if (data.length==0)
					text = "Sin Displays libres";
				else
					text = "Seleccione un Display";
				$("#selectDisplayNotUser").find('option').remove().end().append("<option value=>"+ text +"</option>");
				$.each(data, function(key,val) {					
					$("#selectDisplayNotUser").append("<option value=" + val.id + ">" + val.zoneName + " / " + val.storeName + " / " + val.name + "</option>");
				});	
			});
		});
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."admin/viewURPs" ?>";
		});
	});
</script>