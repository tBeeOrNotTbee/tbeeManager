<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	  	<div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
		</div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<?php if(isset($data['error'])): ?>
		<div class="alert-message error">
			<a class="close" href="#">x</a>
			<p>Error: <?php echo $data['errorMessage'] ?></p>
		</div>
	<?php endif; ?>	
	<div id="error" class="alert-message error errorOrden" style="display:none" >
		<a class="close" href="#">x</a>
		<p>El orden debe ser un número.</p>
	</div>
	<form id="formblock" action="<?php echo FRONT_CONTROLLER ."lista/viewListContent/".$parameters['parameters'] ?>" method="post" >
		<fieldset>
			<div class="row">
				<div class="col-xs-8">
					<select id="selectContent" name="idSelectedContent" class="form-control">
						<option value="">Seleccione List Item</option>
						<?php if(isset($data['allListItems'])): ?>
							<?php if($data['allListItems']<>""): ?>
								<?php foreach($data['allListItems'] as $dataItem): ?>
									<option value=<?php echo $dataItem['id'] ?>><?php echo $dataItem['name'] ?></option>
								<?php endforeach ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>
				</div>
				<div class="col-xs-4">
					<input id="submit" class="form-control btn btn-info" type="submit" name="crear" value="Agregar" />
				</div>
			</div>
		</fieldset>
	</form>
	<?php if($data['contents']<>""): ?>
		<ul class="list-group">
		<li class="list-group-item" style="border:none">
				<div class="row">		
					<div class="col-xs-4 col-md-6"></div> 
					<div class="col-xs-3 col-md-2">Orden</div> 
 					<div class="col-xs-3 col-md-2">Activo</div> 
					<div class="col-xs-2 col-md-2">
						<div class="pull-right">Acciones</div>
					</div>
				</div> 
			</li>
			<?php foreach ($data['contents']  as $content):?>
			<li class="list-group-item">
				<div class="row">	
					<div class="col-xs-4 col-md-6">
						<?php echo $content['name'] ?>
					</div>
	 				<div class="col-xs-3 col-md-2">
						<input type="number" id="order" class="order form-control" data-id="<?php echo $content['id'] ?>" type="text" value="<?php echo $content['order'] ?>">
					</div> 
	 				<div class="col-xs-3 col-md-2">
	 					<input  class="itemDisabled form-control"  type="checkbox" name="itemDisabled" id="itemDisabled" value="false" <?php if(!$content['disabled']){echo " checked ";} ?> data-id=<?php echo $content['id'] ?> >
					</div>
					<div class="col-xs-2 col-md-2">
						<div class="pull-right">
							<a href="#">
								<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $content['name'] ?>" delId="<?php echo $content['id'] ?>" delOrden="<?php echo $content['order'] ?>" delIdList="<?php echo $content['idList'] ?>" > 
									<span class="glyphicon glyphicon-remove"></span>
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
			$("#action_button").attr('delOrden',$(this).attr("delOrden"));	
			$("#action_button").attr('delIdList',$(this).attr("delIdList"));		
			$("#myModalLabel").html('Quitar List Content');	
			$("#action_button").html('Quitar');	
			$("#delete_message").html("Está seguro que desea quitar el list content " + $(this).attr("delName") + "?");		
		});
		$(".itemDisabled").click(function(){
			var idListContent = $(this).attr("data-id");
			if($(this).is(':checked'))
				$.post('<?php echo FRONT_CONTROLLER ."lista/editListContentDisabled" ?>',{id:idListContent, disabled:0},function(data){});  
	        else
	            $.post('<?php echo FRONT_CONTROLLER ."lista/editListContentDisabled" ?>',{id:idListContent, disabled:1},function(data){}); 
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");	
			delOrden = $(this).attr("delOrden");
			delIdList = $(this).attr("delIdList");
			$.post('<?php echo FRONT_CONTROLLER ."lista/deleteListContent" ?>',{id:delId, orden:delOrden, idList:delIdList},function(data){
				location.href="<?php echo FRONT_CONTROLLER ."lista/viewListContent/".$parameters['parameters'] ?>";
			});
		});
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."lista/index/" ?>";
		});
		$(".order").change(function(){
			$(".alert-message").css("display","none");
			id = $(this).attr('data-id');
			order = $(this).val();
			if (!parseInt(order, 10))
				$(".errorOrden").css("display","block");
			else
				$.post('<?php echo FRONT_CONTROLLER ."lista/editListContentOrder" ?>',{id:id, order:order},function(data){});
		});
	});
</script>