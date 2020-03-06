<div id="content-main" class="round dropshadow"> 
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
	    </div>
	    <div class="btn-group" role="group" aria-label="...">
			<button id="nuevo" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Nuevo</div>
			</button>	
	    </div>			
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<?php if(isset($data['error'])): ?>
		<div class="alert-message error">
			<a class="close" href="#">x</a>
			<p>Error: <?php echo $data['errorMessage'] ?></p>
		</div>
	<?php endif; ?>	
	<?php if($data['contents']<>""): ?>
	<ul class="list-group">
		<li class="list-group-item">
			<div class="row">						
				<div class="col-xs-4">Título</div> 
				<div class="col-xs-2">Nombre</div> 
				<div class="col-xs-2">Descripción</div> 
				<div class="col-xs-2">Precio</div> 					
				<div class="col-xs-2">
					<div class="pull-right">Acciones</div>
				</div>
			</div> 
		</li>
		<?php foreach ($data['contents']  as $content):?>
				<li class="list-group-item">
					<div class="row">
						<div class="col-xs-4">
							<?php echo $content['title'] ?>
						</div>
						<div class="col-xs-2">
							<?php echo $content['name'] ?>
						</div>
						<div class="col-xs-2">
							<?php echo $content['description'] ?>
						</div>
						<div class="col-xs-2">
							<?php echo $content['price'] ?>	
						</div>
						<div class="col-xs-2">
							<div class="pull-right">
								<a href='<?php echo FRONT_CONTROLLER ?>menu/editMenuItem/<?php echo $content['id'] ?>'>
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
			$("#myModalLabel").html('Eliminar Menú Item');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar el menú item " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");	
			$.post('<?php echo FRONT_CONTROLLER ."menu/deleteMenuItem" ?>',{id:delId},function(data){
				location.reload();
			});
		});
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."menu/viewMenus/" ?>";
		});
		$("#nuevo").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."menu/newMenuItem/".$parameters['parameters'] ?>";
		});
	});
</script>