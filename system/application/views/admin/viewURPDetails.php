<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
		<div class="btn-group" role="group" aria-label="...">	  
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>	
		</div>		
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<ul class="list-group">
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					URP
				</div>
				<div class="col-md-2 hidden-xs hidden-sm">
					Cliente					
				</div> 	
				<div class="col-md-2 hidden-xs hidden-sm">
					Zona				
				</div> 			
				<div class="col-md-2 hidden-xs hidden-sm">
					Local					
				</div>
				<div class="col-xs-5 col-md-2">
					MacAddress		
				</div>
				<div class="col-xs-1 col-md-1">
					Wall		
				</div>
				<div class="col-xs-4 col-md-1">
					<div class="pull-right">Acciones</div>		
				</div> 	
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">		
				<div class="col-xs-3 col-md-2">
					<?php echo $data['name'] ?>
				</div>
				<div class="col-md-2 hidden-xs hidden-sm">
					<?php echo $data['customer'] ?>					
				</div>
				<div class="col-md-2 hidden-xs hidden-sm">
					<?php echo $data['zone'] ?>				
				</div>
				<div class="col-md-2 hidden-xs hidden-sm">
					<?php echo $data['store'] ?>					
				</div>
				<div class="col-xs-5 col-md-2">
					<?php echo $data['macAddress'] ?>		
				</div>
				<div class="col-xs-1 col-md-1">
					<?php if($data['videoWall']):  ?>
						<div class="center_icon"><span class="glyphicon glyphicon-ok"></span></div>
					<?php endif; ?>
				</div> 
				<div class="col-xs-4 col-md-1">
					<div class="pull-right" >
						<a href='<?php echo FRONT_CONTROLLER ?>admin/editURP/<?php echo $data['id'] ?>'>
							<span class="edit " edName="<?php echo $data['name'] ?>" edId="<?php echo $data['id'] ?>"> 
								<span class="glyphicon glyphicon-pencil"></span>
							</span>
						</a>	
						<a href="#"><span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $data['name'] ?>" delId="<?php echo $data['id'] ?>"> 
							<span class="glyphicon glyphicon-trash"></span></span>
						</a>	
					</div>			
				</div> 
			</div>
		</li>
	</ul>	
</div>
<script>
	$(document).ready(function(){
		$(".delete").click(function(){				
			$("#action_button").attr('delId', $(this).attr("delId"));	
			$("#action_button").attr('delName',$(this).attr("delName"));		
			$("#myModalLabel").html('Eliminar URP');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar la URP " + $(this).attr("delName") + "?");	
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");
			$.post('<?php echo FRONT_CONTROLLER ."admin/deleteURP/"?>' + delId,{id:delId},function(data){
				location.href="<?php  echo FRONT_CONTROLLER ."admin/viewURPs/". $data['idCustomer'] ?>";
			});
		});
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."admin/viewURPs/". $data['idCustomer'] ?>";
		});
	});
</script>