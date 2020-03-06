<?php if (!empty($data['urps'])): ?>	
	<ul class="list-group">
		<li class="list-group-item">
				<div class="row">					
					<div class="col-xs-4">URP</div> 
					<div class="col-xs-4">Mac Address</div> 
					<div class="col-xs-4">Conectado</div>
				</div> 
			</li>	
			<?php foreach ($data['urps']  as $content):?>
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-4">
						<a href="<?php  echo FRONT_CONTROLLER ."admin/viewURPDetails/" . $content['id'] ?>"><?php echo $content['name'] ?></a>
					</div>
					<div class="col-xs-4">
						<?php echo $content['macAddress'] ?>
					</div>
					<div class="col-xs-4">
						<?php if(!empty($content['displayName'])) { 
							echo '<a href="#"><span class="delete" data-toggle="modal" data-target="#myModal" displayName="' .$content['displayName']. '" delName="' .$content['name']. '" delId="' .$content['id']. '"><span class="glyphicon glyphicon-remove"></span></span></a> '.$content['displayName'];  
						}?>		
					</div> 	
				</div>
			</li>
		<?php endforeach ?>
	</ul>	
	<?php else: ?>
		<div class="row">					
			<div class="col-xs-12">
				No hay registros para este cliente.
			</div>
		</div>
	<?php endif; ?>
<script>
	$(document).ready(function(){
		$(".delete").click(function(){				
			$("#action_button").attr('delId', $(this).attr("delId"));	
			$("#action_button").attr('delName',$(this).attr("delName"));		
			$("#myModalLabel").html('Desvincular display de URP');	
			$("#action_button").html('Desvincular');	
			$("#delete_message").html("Está seguro que desea desvincular el display " + $(this).attr("displayName") + " de " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");
			$.post('<?php echo FRONT_CONTROLLER ."admin/deleteURPLink/"?>' + delId,{id:delId},function(data){
				location.href="<?php  echo FRONT_CONTROLLER ."admin/viewURPs/" ?>" + $("#selectClient").val();
			});
		});
	});
</script>