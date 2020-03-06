<style>
	.clickable_row:hover{	
		cursor:pointer;
	}
	.table{	
		text-align:center;
		font-size:13px;
	}
</style>
<div id="content-main" class="round dropshadow">
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
		function updateViewOnline(){
			if ($("#selectCustomer").val()!=""){
				$.post("<?php echo FRONT_CONTROLLER ?>online/getAdminOnlineByCustomer/" + $("#selectCustomer").val(),function(data){
					$("#tableContent").html(data);
					$(".clickable_row").click(function(){
						window.document.location = $(this).data("href");
					});
				});	
			}
			else{
				$.post("<?php echo FRONT_CONTROLLER ?>online/getAdminOnline/",function(data){
					$("#tableContent").html(data);
					$(".clickable_row").click(function(){
						window.document.location = $(this).data("href");
					});
				});	
			}
		}
		updateViewOnline();
		$("#selectCustomer").change(function(){	
			updateViewOnline();
		});
	});
</script>