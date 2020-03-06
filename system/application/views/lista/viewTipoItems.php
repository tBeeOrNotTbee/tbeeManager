<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	  	<div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
		</div>			
		<div class="btn-group" role="group" aria-label="...">	
			<button id="nuevo" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Nuevo Tipo Item</div>
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
					<div class="col-xs-3"></div> 
 					<div class="col-xs-3">Color de fondo</div> 
					<div class="col-xs-3">Color de fuente</div>
					<div class="col-xs-3">
						<div class="pull-right">Acciones</div>
					</div>
				</div> 
			</li>
			<?php foreach ($data['contents']  as $content):?>
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-3">
						<?php echo $content['name'] ?>
					</div>
	 				<div class="col-xs-3">
						<?php echo $content['backgroundColor'] ?>
						<div style="float: left; margin-right: 6px; width:20px; height:20px; background-color: #<?php echo $content['backgroundColor'] ?>" >
						</div> 
					</div>					
					<div class="col-xs-3">
						<?php echo $content['fontColor'] ?>
						<div style="float: left; margin-right: 6px; width:20px; height:20px; background-color: #<?php echo $content['fontColor'] ?>" >
						</div> 							
					</div>
					<div class="col-xs-3">
						<div class="pull-right">
							<a href='<?php echo FRONT_CONTROLLER ?>lista/editTipoItem/<?php echo $content['id'] ?>'>
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
			$("#myModalLabel").html('Eliminar Tipo Item');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar el tipo item " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");			
			$.post('<?php echo FRONT_CONTROLLER ."lista/deleteTipoItem" ?>',{id:delId},function(data){
				location.reload();
			});
		});
		$("#nuevo").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."lista/newTipoItem/".$parameters['parameters'] ?>";
		});
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."lista/index/" ?>";
		});
	});
</script>