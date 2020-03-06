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
				<div class="col-xs-8">Contenido</div>
				<div class="col-xs-4">Comienzo</div>
			</div>
		</li>
		<?php if(!empty($data['contents'])): ?>
			<?php foreach ($data['contents']  as $content):?>
				<li class="list-group-item">	
					<div class="row">
						<div class="col-xs-8">
							<?php echo $content['name'] ?>
						</div>  	
						<div class="col-xs-4">
							<?php echo $content['startHour'] .":". $content['startMinute'] ?>						
						</div>
					</div>
				</li>
			<?php endforeach ?>
		<?php endif; ?>
	</ul>		
	<?php else: ?>
		<div class="row top-buffer">	
			<div class="col-xs-12">
				<h4>Falta configurar la agenda</h4>
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