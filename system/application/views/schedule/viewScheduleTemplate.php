<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="nuevo" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Nueva agenda</div>
			</button>
			<button id="nuevo_bloque" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Nuevo bloque</div>
			</button>
			<button id="ver_bloques" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Listar bloques</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<?php if($data['template']<>""): ?>
	<ul class="list-group">
		<?php foreach ($data['template']  as $template):?>
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-9">
					<a href="<?php echo FRONT_CONTROLLER ."schedule/viewScheduleContent/".$template['id'] ?>"><?php echo $template['name'] ?></a>
				</div> 
				<div class="col-xs-3">
					<div class="pull-right">
						<a href='<?php echo FRONT_CONTROLLER ?>schedule/editScheduleTemplate/<?php echo $template['id'] ?>'>
							<span  class="edit " edName="<?php echo $template['name'] ?>" edId="<?php echo $template['id'] ?>"> 
								<span class="glyphicon glyphicon-pencil"></span>
							</span>
						</a>
						<!-- Solo permite eliminar una Agenda que no este siendo utilizada -->
						<?php if ($template['delete']){ ?>
							<a href="#">
								<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $template['name'] ?>" delId="<?php echo $template['id'] ?>">
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
<script>
	$(document).ready(function(){
		$(".delete").click(function(){				
			$("#action_button").attr('delId', $(this).attr("delId"));	
			$("#action_button").attr('delName',$(this).attr("delName"));		
			$("#myModalLabel").html('Eliminar Agenda');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar la agenda " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");		
			$.post('<?php echo FRONT_CONTROLLER ."schedule/deleteScheduleTemplate" ?>',{id:delId},function(data){
				location.reload();
			});
		});	
		$("#nuevo").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."schedule/newScheduleTemplate/" ?>";
		});	
		$("#nuevo_bloque").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."schedule/newScheduleBlock/" ?>";
		});
		$("#ver_bloques").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."schedule/viewCustomerScheduleBlocks/" ?>";
		});
	});
</script>