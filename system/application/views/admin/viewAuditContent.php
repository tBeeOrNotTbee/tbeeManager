<div id="content-main" class="round dropshadow">
	<?php echo setBreadcrumb($breadcrumb) ?>
	<select id="selectCustomer" class="form-control" name="selectCustomer">
		<option value="">Todos los clientes</option>
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
		function updateViewAudit(){
			if ($("#selectCustomer").val()!="")
				$.post("<?php echo FRONT_CONTROLLER ?>admin/getAuditContentByCustomer/" + $("#selectCustomer").val(),function(data){$("#tableContent").html(data);});	
			else
				$.post("<?php echo FRONT_CONTROLLER ?>admin/getAuditContent/",function(data){$("#tableContent").html(data);});	
		}
		updateViewAudit();
		$("#selectCustomer").change(function(){	
			updateViewAudit();
		});
	});
</script>