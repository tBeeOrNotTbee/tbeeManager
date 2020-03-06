<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="nuevo" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Nuevo usuario</div>
			</button>
	    </div>
	</nav>
	<select id="selectCustomer" class="form-control top-buffer" name="selectCustomer">
		<option value="admin">Administración general</option>
		<option value="monitor">Monitor</option>
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
		function updateViewUsers(){
			if ($("#selectCustomer").val()=="admin")
				$.post("<?php echo FRONT_CONTROLLER ?>admin/getRoleUsers/<?php echo ROLE_ADMIN ?>",function(data){$("#tableContent").html(data);});	
			else if ($("#selectCustomer").val()=="monitor")
				$.post("<?php echo FRONT_CONTROLLER ?>admin/getRoleUsers/<?php echo ROLE_MONITOR ?>",function(data){$("#tableContent").html(data);});		
			else
				$.post("<?php echo FRONT_CONTROLLER ?>admin/getCustomerUsers/" + $("#selectCustomer").val(),function(data){$("#tableContent").html(data);});
		}
		updateViewUsers();
		$("#selectCustomer").change(function(){	
			updateViewUsers();
		});
		$("#nuevo").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."admin/newUser" ?>";
		});
	});
</script>