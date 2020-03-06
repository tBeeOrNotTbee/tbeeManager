<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Volver</div>
			</button>
	    </div>
	    <div class="btn-group" role="group" aria-label="...">
	  		<button id="nuevo_bloque" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Nuevo bloque</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<?php if($data['contents']<>""): ?>
	<ul class="list-group">
		<?php foreach($data['contents'] as $block): ?>	
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-9">
						<a href="<?php echo FRONT_CONTROLLER ."schedule/editScheduleBlockItems/".$block['id'] ?>"><?php echo $block['name'] ?></a>
					</div>
					<div class="col-xs-3">
						<div class="pull-right">
							<a href='<?php echo FRONT_CONTROLLER ?>schedule/editScheduleBlock/<?php echo $block['id'] ?>'>
								<span  class="edit " edName="<?php echo $block['name'] ?>" edId="<?php echo $block['id'] ?>"> 
									<span class="glyphicon glyphicon-pencil"></span>
								</span>
							</a>
							<!-- Solo permite eliminar un Bloque que no este siendo utilizado -->
							<?php if ($block['delete']){ ?>
							<a href="#">
								<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $block['name'] ?>" delId="<?php echo $block['id'] ?>"> 
									<span class="glyphicon glyphicon-trash"></span>
								</span>
							</a>
							<?php } ?>
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
</div>
<script>
	$(document).ready(function(){
		$(".delete").click(function(){				
			$("#action_button").attr('delId', $(this).attr("delId"));	
			$("#action_button").attr('delName',$(this).attr("delName"));		
			$("#myModalLabel").html('Eliminar Bloque');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar el bloque " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");		
			$.post('<?php echo FRONT_CONTROLLER ."schedule/deleteBlock" ?>',{id:delId},function(data){
				location.reload();
			});
		});
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."schedule/viewScheduleTemplate/".$parameters['parameters'] ?>";
		});
		$("#nuevo_bloque").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."schedule/newScheduleBlock/".$parameters['parameters'] ?>";
		});
	});
</script>