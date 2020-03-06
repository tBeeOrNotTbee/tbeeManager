<link rel="stylesheet" href="<?php echo CSS_PATH.'bootstrap-datetimepicker.css'?>"/>
<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
		<div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Volver</div>
			</button>
		</div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<form action="<?php echo FRONT_CONTROLLER ."admin/viewWall/".$parameters['parameters'] ?>" method="post" >
		<fieldset>
			<div class="row">
				<div class="col-md-8">
					<select id="selectURP" class="form-control input" name="selectURP" required>
						<option value="">Seleccione URP</option>
						<?php if (!empty($data['URPs'])): ?>
							<?php foreach($data['URPs'] as $dataURP): ?>
								<option value=<?php echo $dataURP['id'] ?>><?php echo $dataURP['name'] ?></option>
							<?php endforeach ?>
						<?php endif; ?>
					</select>
				</div>	
		        <div class="col-md-4">                						 
					<input id="submit" class="form-control btn btn-info" type="submit" name="submit" value="Agregar" />
				</div>
		</fieldset>
	</form>
	<ul class="list-group">
		<li class="list-group-item" style="border:none">
			<div class="row">		
				<div class="col-md-8">URP</div>
				<div class="col-md-4">
					<div class="pull-right" >
						Acciones
					</div>
				</div>
			</div>
		</li>
		<?php if (!empty($data['wall'])): ?>
			<?php foreach ($data['wall']  as $wall):?>
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-8">
						<?php echo $wall['name'] ?>
					</div>
					<div class="col-md-4">
						<span class="pull-right">
							<a href="#">
									<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $wall['name'] ?>" delId="<?php echo $wall['id'] ?>"> 
										<span class="glyphicon glyphicon-trash"></span>
									</span>
								</a>
						</span>
					</div>
				</div>		
			</li>
			<?php endforeach ?>
		<?php endif; ?>
	</ul>
</div>
<script>
	$(document).ready(function(){
		var params = {};
		var action = "";
		$("#submit").click(function(){
			$("form").submit();
		});
		$(".delete").click(function(){				
			$("#action_button").attr('delId', $(this).attr("delId"));	
			$("#action_button").attr('delName',$(this).attr("delName"));		
			$("#myModalLabel").html('Eliminar URP del Wall');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar la URP " + $(this).attr("delName") + " del wall?");		
		});			
		$("#action_button").click(function(){
			delId = $(this).attr("delId");	
			$.post('<?php echo FRONT_CONTROLLER ."admin/removeURPWall" ?>',{id:delId},function(data){
				location.reload();
			});
		});
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."admin/viewWalls" ?>";
		});
	});
</script>