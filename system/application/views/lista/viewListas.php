<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
		<div class="btn-group" role="group" aria-label="...">
			<button id="listarListItems" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Listar list items</div>
			</button>
			<button id="listarTipo" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Listar tipo items</div>
			</button>			
			<button id="nuevo" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Nueva Lista</div>
			</button>
			<button id="asignar" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Asignar Lista</div>
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
					<div class="col-xs-6"></div> 
					<div class="col-xs-6">
						<div class="pull-right">Acciones</div>
					</div>
				</div> 
			</li>
			<?php foreach ($data['contents']  as $content):?>
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-6">
						<a href="<?php  echo FRONT_CONTROLLER ."lista/viewListContent/".$content['id'] ?>"><?php echo $content['name'] ?></a>
					</div>
					<div class="col-xs-6">
						<div class="pull-right">
							<a href='<?php echo FRONT_CONTROLLER ?>lista/editLista/<?php echo $content['id'] ?>'>
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
			$("#myModalLabel").html('Eliminar Lista');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar la lista " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");	
			$.post('<?php echo FRONT_CONTROLLER ."lista/deleteLista" ?>',{id:delId},function(data){
				location.reload();
			});			
		});
		$("#nuevo").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."lista/newLista/".$parameters['parameters'] ?>";
		});
		$("#listarListItems").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."lista/viewListItems/" ?>";
		});
		$("#listarTipo").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."lista/viewTipoItems/" ?>";
		});
		$("#asignar").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."lista/viewListDisplay/".$parameters['parameters'] ?>";
		});		
	});
</script>