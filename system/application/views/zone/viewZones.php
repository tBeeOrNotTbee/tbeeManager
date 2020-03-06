<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
		<div class="btn-group" role="group" aria-label="...">
			<?php if(isset($data['admin']) and $data['admin']): ?>		  
				<button id="volver" type="button" class="navbtn btn btn-default">
					<div class="round button left dropshadow">Volver</div>
				</button>
			<?php endif; ?>		
		</div>
		<div class="btn-group" role="group" aria-label="...">	 
			<button id="nuevo" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Nueva zona</div>
			</button>
		 </div>	 
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<?php if($data['contents']<>""): ?>
		<ul class="list-group">
			<?php foreach ($data['contents']  as $content):?>
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-9">
						<a href="<?php echo FRONT_CONTROLLER ."store/viewStores/".$content['id'] ?>"><?php echo $content['name'] ?></a>
					</div>
					<div class="col-xs-3">
						<div class="pull-right">
							<a href='<?php echo FRONT_CONTROLLER ?>zone/editZone/<?php echo $content['id'] ?>'>
								<span  class="edit " edName="<?php echo $content['name'] ?>" edId="<?php echo $content['id'] ?>"> 
									<span class="glyphicon glyphicon-pencil"></span>
								</span>
							</a>
							<a href="#">
								<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $content['name'] ?>" delId="<?php echo $content['id'] ?>"> 
									<span class="glyphicon glyphicon-trash"></span>
								</span>
							</a>
						</div>
					</div>
				</div>
			</li>
		<?php endforeach ?>
	</ul>	
	<?php else: ?>
		<div class="row">
			<div class="col-xs-12">
				No se obtuvieron resultados
			</div>
		</div> 
	<?php endif; ?>
</div>
<script>
	$(document).ready(function(){
		$(".delete").click(function(){				
			$("#action_button").attr('delId', $(this).attr("delId"));	
			$("#action_button").attr('delName',$(this).attr("delName"));		
			$("#myModalLabel").html('Eliminar Zona');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar la zona " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");		
			$.post('<?php echo FRONT_CONTROLLER ."zone/deleteZone" ?>',{id:delId},function(data){
				location.reload();
			});
		});
		<?php if(isset($data['admin']) and $data['admin']): ?>	
			$("#volver").click(function(){
				location.href="<?php  echo FRONT_CONTROLLER ."admin/" ?>";
			});
		<?php endif; ?>		
		$("#nuevo").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."zone/newZone/". $data['idCustomer'] ?>";
		});
	});
</script>