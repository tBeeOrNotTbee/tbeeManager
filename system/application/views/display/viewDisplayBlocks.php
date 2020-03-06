<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<?php if($data['contents']<>""): ?>
	<ul class="list-group">	
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-7 col-md-4">Contenido</div>
				<div class="hidden-xs hidden-sm col-md-4">Fecha Actualización</div>
				<div class="col-xs-2 col-md-2">Duración (min)</div>
				<div class="col-xs-2 col-md-2">Orden</div> 	
			</div>
		</li>
		<?php foreach ($data['contents']  as $content):?>
			<li class="list-group-item">				
				<div class="row">
					<div class="col-xs-7 col-md-4">
						<?php echo $content['name'] ?></a>
					</div> 
					<div class="hidden-xs hidden-sm col-md-4">
						<span><?php echo $content['contentUpdate'] ?></span>			
					</div> 
					<div class="col-xs-2 col-md-2">
						<span><?php echo $content['length'] ?></span>			
					</div> 	
					<div class="col-xs-2 col-md-2">
						<span><?php echo $content['order'] ?></span>			
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
			location.href="<?php  echo FRONT_CONTROLLER ."display/viewDisplay/".$idDisplay ?>";
		});
	});
</script>