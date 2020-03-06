<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="nuevo" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Nueva URP</div>
			</button>		
			<button id="enlazar" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Enlazar URP</div>
			</button>
	    </div>
	    <div class="btn-group" role="group" aria-label="...">
			<button id="wall" type="button" class="navbtn btn btn-default">
				<div class="round button dropshadow">Walls</div>
			</button>
		</div>
	</nav>
	<select id="selectCustomer" class="form-control" name="selectCustomer">
		<option value=>Seleccione un cliente</option>
		<?php if(isset($data['customers'])): ?>
			<?php if($data['customers']<>""): ?>
				<?php foreach($data['customers'] as $dataCustomer): ?>
					<option value=<?php echo $dataCustomer['id'] ?>><?php echo $dataCustomer['name'] ?></option>
				<?php endforeach ?>
			<?php endif; ?>
		<?php endif; ?>
	</select>
	<div id="tableContent" class="table table-striped top-buffer"></div>
</div>
<script>
	$(document).ready(function(){
	<?php if (!empty($data['idCustomer'])) { ?>
		var idcustomer = <?php echo $data['idCustomer'] ?>;
		$("#selectCustomer").val(idcustomer);
		$.post("<?php echo FRONT_CONTROLLER ?>admin/getCustomerURPs/" + idcustomer,function(data){
			$("#tableContent").html(data);
		});			
	<?php } ?>
		$("#selectCustomer").change(function(){					
			$.post("<?php echo FRONT_CONTROLLER ?>admin/getCustomerURPs/" + $("#selectCustomer").val(),function(data){		
				$("#tableContent").html(data);
			});	
		});
		$("#nuevo").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."admin/newURP" ?>";
		});
		$("#enlazar").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."admin/newURPLink" ?>";
		});
		$("#wall").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."admin/viewWalls" ?>";
		});
	});
</script>