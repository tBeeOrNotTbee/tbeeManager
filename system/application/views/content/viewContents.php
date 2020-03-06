<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
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
					<div class="col-xs-5 col-md-5">Nombre</div> 
					<div class="col-xs-3 col-md-3">Tipo</div> 
					<div class="hidden-xs hidden-sm col-md-2">Actualizado</div> 
					<div class="col-xs-4 col-md-2">
						<div class="pull-right">Acciones</div>
					</div>
				</div> 
			</li>
			<?php foreach ($data['contents']  as $content):?>
				<?php if ($content['idContentType'] != TYPE_PLAYLIST){ ?>
					<li class="list-group-item">
						<div class="row">	
							<div class="col-xs-5 col-md-5">
								<?php echo $content['name'] ?>
							</div> 
							<div class="col-xs-3 col-md-3">
								<?php echo $content['contentTypeName'] ?>
							</div>
							<div class="hidden-xs hidden-sm col-md-2">
								<?php echo $content['updateTime'] ?>
							</div>
							<div class="col-xs-4 col-md-2">
								<div class="pull-right">
									<a href="<?php echo FRONT_CONTROLLER.'content/infoContent/'.$content['id'] ?>">
										<span class="edit"> 
											<span class="glyphicon glyphicon-info-sign"></span>
										</span>
									</a>	
									<a href="<?php echo FRONT_CONTROLLER.'content/editContent/'.$content['id'] ?>">
										<span class="edit"> 
											<span class="glyphicon glyphicon-pencil"></span>
										</span>
									</a>
									<!-- Solo permite eliminar un Contenido que no este siendo utilizado -->
									<?php if ($content['delete']){ ?>
									<a href="#">
										<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $content['name'] ?>" delId="<?php echo $content['id'] ?>"> 
											<span class="glyphicon glyphicon-trash"></span>
										</span>
									</a>
									<?php } ?>
								</div>
							</div>
						</div> 
					</li>
				<?php } ?>
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
			$("#myModalLabel").html('Eliminar Contenido');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar el contenido " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");		
			$.post('<?php echo FRONT_CONTROLLER ."content/deleteContent" ?>',{id:delId},function(data){
				location.reload();
			});
		});
		$("#nuevo").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."content/newContent" ?>";
		});
	});
</script>