<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<?php if($data['contents']<>""): ?>
		<ul class="list-group">
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-8">Contenido</div>
					<div class="col-xs-4">Hora Inicio</div>
				</div>
			</li>
			<?php foreach ($data['contents']  as $content):?>
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-8">
						<?php echo $content['name'] ?>
					</div>
					<div class="col-xs-4">
						<?php echo $content['startHour'].":".$content['startMinute'] ?>
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
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."schedule/viewScheduleContent/".$parameters['parameters'] ?>";
		});	
	});
</script>