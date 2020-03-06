<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<?php if(isset($data['alert'])): ?>
		<div class="alert-message success">
			<a class="close" href="#">x</a>
			<p>Contenido agregado con éxito.</p>
		</div>
	<?php endif; ?>
	<div id="error" class="alert-message error selectContent" style="display:none" >
		<a class="close" href="#">x</a>
		<p>Debe seleccionar un contenido.</p>
	</div>
	<div id="error" class="alert-message error errorDuracion" style="display:none" >
		<a class="close" href="#">x</a>
		<p>La duración debe ser entre 1 y 1440.</p>
	</div>
	<div id="error" class="alert-message error errorOrden" style="display:none" >
		<a class="close" href="#">x</a>
		<p>El orden debe ser un número.</p>
	</div>
	<form id="formblock" action="<?php echo FRONT_CONTROLLER ."schedule/editScheduleBlockItems/" . $data['idBlock'] ?>" method="post">	
		<fieldset>
			<div class="row">
				<div class="col-xs-6 col-md-7">
					<select id="selectContent" class="form-control" name="idContent" style="float:left">
						<option value=>Seleccione contenido</option>
						<?php foreach($data['contents'] as $dataContent): ?>
							<option value=<?php echo $dataContent['id'] ?>><?php echo $dataContent['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="col-xs-3 col-md-3">
					<input class="form-control" type="text" name="duracion" id="duracion" placeholder="Duración" />
				</div>
				<div class="col-xs-3 col-md-2">
					<input id="submit" class="form-control btn btn-info" type="submit" name="submit" value="Agregar" />
				</div>
			</div>
		</fieldset>
	</form>
	<?php if($data['blockItems']<>""): ?>
		<ul class="list-group top-buffer">
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-4 col-md-6">Contenido</div>			
					<div class="col-xs-3 col-md-2">Duración (min)</div>	 
					<div class="col-xs-3 col-md-2">Orden</div>	 
					<div class="col-xs-2 col-md-2">
						<div class="pull-right">Acciones</div>
					</div>
				</div>
			</li>
			<?php foreach ($data['blockItems']  as $content):?>
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-4 col-md-6">
						<?php echo $content['name'] ?>
					</div> 
					<div class="col-xs-3 col-md-2">
						<input type="number" id="length" class="length form-control" data-id="<?php echo $content['id'] ?>" type="text" value="<?php echo $content['length'] ?>">
					</div> 
					<div class="col-xs-3 col-md-2">
						<input type="number" id="order" class="order form-control" data-id="<?php echo $content['id'] ?>" type="text" value="<?php echo $content['order'] ?>">
					</div> 
					<div class="actions_col col-xs-2 col-md-2">
						<div class="pull-right">
							<!-- Solo permite eliminar si hay mas de un contenido -->
							<?php if (count($data['blockItems'])>1){ ?>
							<a href="#">
								<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $content['name'] ?>" delId="<?php echo $content['id'] ?>" delOrden="<?php echo $content['order'] ?>" delIdScheduleBlock="<?php echo $data['idBlock'] ?>" > 
									<span class="glyphicon glyphicon-remove"></span>
								</span>
							</a>
							<?php } ?>
						</div>
					</div>
				</div>	
			</li>
			<?php endforeach ?>
		</ul>	
	<?php endif; ?>
</div>
<script>
	$(document).ready(function(){
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."schedule/viewCustomerScheduleBlocks" ?>";
		});
		$(".delete").click(function(){				
			$("#action_button").attr('delId', $(this).attr("delId"));	
			$("#action_button").attr('delName',$(this).attr("delName"));
			$("#action_button").attr('delOrden',$(this).attr("delOrden"));	
			$("#action_button").attr('delIdScheduleBlock',$(this).attr("delIdScheduleBlock"));			
			$("#myModalLabel").html('Eliminar Contenido del Bloque');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar el contenido " + $(this).attr("delName") + " de este bloque?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");		
			delOrden = $(this).attr("delOrden");
			delIdScheduleBlock = $(this).attr("delIdScheduleBlock");		
			$.post('<?php echo FRONT_CONTROLLER ."schedule/deleteBlockItem" ?>',{id:delId, orden:delOrden, idScheduleBlock:delIdScheduleBlock},function(data){
				location.href="<?php echo FRONT_CONTROLLER ."schedule/editScheduleBlockItems/".$data['idBlock'] ?>";
			});
		});
		$('#formblock').submit(function(e){
			$(".alert-message").css("display","none");	
			if ($("#name").val()=="") {
				$(".blockName").css("display","block");				
				e.preventDefault();
			}
			if ($("#selectContent").val()=="") {
				$(".selectContent").css("display","block");				
				e.preventDefault();
			}
			if ($("#duracion").val()=="" || $("#duracion").val()<1 ||  $("#duracion").val()>1440 || !parseInt($("#duracion").val(),10)) {
				$(".errorDuracion").css("display","block");				
				e.preventDefault();
			}
		});
		$(".length").change(function(){
			$(".alert-message").css("display","none");
			id = $(this).attr('data-id');
			length = $(this).val();
			if (length=="" || length<1 ||  length>1440 || !parseInt(length,10))
				$(".errorDuracion").css("display","block");
			else
				$.post('<?php echo FRONT_CONTROLLER ."schedule/editScheduleBlockItemLength" ?>',{id:id, length:length},function(data){});
		});
		$(".order").change(function(){
			$(".alert-message").css("display","none");
			id = $(this).attr('data-id');
			order = $(this).val();
			if (!parseInt(order, 10))
				$(".errorOrden").css("display","block");
			else
				$.post('<?php echo FRONT_CONTROLLER ."schedule/editScheduleBlockItemOrder" ?>',{id:id, order:order},function(data){});
		});
	});
</script>