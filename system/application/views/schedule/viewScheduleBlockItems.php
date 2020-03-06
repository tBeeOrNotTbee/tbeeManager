<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<?php if($data['blockItems']<>""): ?>
	<ul class="list-group">
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-8">Contenido</div>					
				<div class="col-xs-2">Duración (min)</div>	 
				<div class="col-xs-2">Orden</div>
			</div>
		</li>
		<?php foreach($data['blockItems'] as $item): ?>	
			<li class="list-group-item">
				<div class="row">	
					<div class="col-xs-8">
						<?php echo $item['name'] ?>
					</div>
					<div class="col-xs-2">
						<?php echo $item['length'] ?>
					</div>
					<div class="col-xs-2">
						<?php echo $item['order'] ?>
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
			location.href="<?php  echo FRONT_CONTROLLER ."schedule/viewCustomerScheduleBlocks/" ?>";
		});
	});
</script>		