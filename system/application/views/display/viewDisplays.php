<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<?php if(!$store_admin): ?>	  
				<button id="volver" type="button" class="navbtn btn btn-default">
					<div class="round button left dropshadow">Volver</div>
				</button>
			<?php endif; ?>
	    </div>			
	    <div class="btn-group" role="group" aria-label="...">			
			<button id="nuevo" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Nuevo display</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<?php if($data['contents']<>""): ?>	
	<ul class="list-group">
		<li class="list-group-item">
			<div class="row">		
				<div class="col-xs-5 col-md-4"></div>
				<div class="col-xs-4 col-md-2">Última Conexión</div> 			
				<div class="hidden-xs hidden-sm col-md-1">Vertical</div> 	
				<div class="hidden-xs hidden-sm col-md-3">Agenda</div>
				<div class="col-xs-3 col-md-2">
					<div class="pull-right">Acciones</div>
				</div>
			</div>
		</li>
		<?php foreach ($data['contents'] as $content):?>
			<li class="list-group-item">			
				<div class="row">	
					<div class="col-xs-5 col-md-4">
						<a href="<?php  echo FRONT_CONTROLLER ."display/viewDisplay/".$content['id'] ?>"><?php echo $content['name'] ?></a>
					</div> 
					<div class="col-xs-4 col-md-2">
						<?php echo $content['lastUpdate'] ?>			
					</div> 			
					<div class="hidden-xs hidden-sm col-md-1">
						<?php if($content['vertical']):  ?>
							<div class="center_icon"><span class="glyphicon glyphicon-ok"></span></div>
						<?php endif; ?>
					</div> 	
					<div class="hidden-xs hidden-sm col-md-3">
						<select class="selectTemplate form-control" name="selectTemplate" data-id=<?php echo $content['id'] ?> >
							<?php if($content['idTemplateDefault']): ?>
								<option value=<?php echo $content['idTemplateDefault']; ?> ><?php echo $content['templateDefault'] ?></option>
							<?php else: ?>
								<option value="" >Seleccione...</option>						
							<?php endif; ?>
							<?php if(isset($dataTemplate['allScheduleTemplate'])): ?>	
								<?php if($dataTemplate['allScheduleTemplate']<>""): ?>							
									<?php foreach ($dataTemplate['allScheduleTemplate'] as $templates): ?>
										<?php if( $templates['name'] <> $content['templateDefault']): ?>
											<option value=<?php echo $templates['id'] ?>><?php echo $templates['name'] ?></option>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							<?php endif; ?>
						</select>	
					</div> 					 
					<div class="col-xs-3 col-md-2">
						<div class="pull-right">
							<a href='<?php echo FRONT_CONTROLLER ?>display/editDisplay/<?php echo $content['id'] ?>'>
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
			$("#myModalLabel").html('Eliminar Display');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar el display " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");			
			$.post('<?php echo FRONT_CONTROLLER ."display/deleteDisplay" ?>',{id:delId},function(data){
				location.reload();
			});	
		});	
		$(".selectTemplate").change(function(){
			idDisplay = $(this).attr("data-id");
			idTemplate = $(this).val();
			$.post('<?php echo FRONT_CONTROLLER ."display/editDisplayDefaultTemplate" ?>',{idDisplay:idDisplay,idTemplate:idTemplate},function(data){	
				window.location.href = window.location.href;
			});		
		});	
		<?php if(!$store_admin): ?>	  
			$("#volver").click(function(){
				location.href="<?php  echo FRONT_CONTROLLER ."store/viewStores/".$data['zoneId'] ?>";
			});
		<?php endif; ?>
		$("#nuevo").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."display/newDisplay/". $idStore ?>";
		});
	});
</script>