<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Volver</div>
			</button>
		</div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<div id="tableContent" class="table table-striped top-buffer">
		<?php if (!empty($data['walls'])): ?>	
			<ul class="list-group">
				<li class="list-group-item">
					<div class="row">
						<div class="col-md-2">
							URP
						</div>
						<div class="col-md-2">
							Cliente					
						</div> 	
						<div class="col-md-3">
							Zona				
						</div> 			
						<div class="col-md-3">
							Local					
						</div>
						<div class="col-md-2">
							Display					
						</div>	
					</div>
				</li>
				<?php foreach ($data['walls']  as $wall):?>
					<li class="list-group-item">
						<div class="row">
							<div class="col-md-2">
								<a href="<?php  echo FRONT_CONTROLLER ."admin/viewWall/" . $wall['idURP'] ?>"><?php echo $wall['name'] ?></a>
							</div>
							<div class="col-md-2">
								<?php echo $wall['customer'] ?>
							</div>
							<div class="col-md-3">
								<?php echo $wall['zone'] ?>
							</div>
							<div class="col-md-3">
								<?php echo $wall['store'] ?>
							</div>
							<div class="col-md-2">
								<?php echo $wall['display'] ?>
							</div>
						</div>
					</li>
				<?php endforeach ?>
			</ul>
		<?php endif; ?>
	</div>
</div>
<script>
	$(document).ready(function(){
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."admin/viewURPs" ?>";
		});
	});
</script>