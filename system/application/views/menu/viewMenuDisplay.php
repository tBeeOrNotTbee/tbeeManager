<link rel="stylesheet" href="<?php echo CSS_PATH ?>bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript" src="<?php echo JS_PATH ?>bootstrap-multiselect.js"></script>
<div id="content-main" class="round dropshadow"> 
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button right dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<form action="<?php echo FRONT_CONTROLLER ."menu/viewMenuDisplay/".$parameters['parameters'] ?>" method="post">
		<fieldset>
			<div class="row">
				<div class="col-xs-6">
					<select  class="form-control" id="display" name="display[]" multiple="multiple">
						<?php if(isset($select_content['displays'])): ?>
							<?php if($select_content['displays']<>""): ?>								
								<?php foreach ($select_content['displays']  as $display):?>
									<option value=<?php echo $display['id'] ?>><?php echo $display['zoneName'] . " / " .  $display['storeName'] . " / " .  $display['name'] ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>	
				</div>
				<div class="col-xs-6">									
					<select class="form-control" id="menu" name="menu">
						<option selected value="">Seleccione Menú...</option>
							<?php if(isset($select_content['menus'])): ?>
								<?php if($select_content['menus']<>""): ?>
									<?php foreach ($select_content['menus']  as $menu):?>
											<option value=<?php echo $menu['id'] ?>><?php echo $menu['name'] ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							<?php endif; ?>
					</select>
				</div>
			</div>
			<div class="row top-buffer">
				<div class="col-xs-12">			
					<input id="submit" class="form-control btn btn-info" type="submit" name="crear" value="Programar" />
				</div>
			</div>
		</fieldset>
	</form>
	<?php if($data['contents']<>""): ?>
		<ul id="table_content" class="list-group">
			<li class="list-group-item" style="border:none">
				<div class="row">		
					<div class="col-xs-4">Display</div>					
					<div class="col-xs-4">Menú </div>	 
					<div class="col-xs-4">
						<div class="pull-right">Acciones</div>
					</div> 
				</div>
			</li>
			<?php foreach ($data['contents']  as $content):?>
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-4">
						<select name="selectDisplay" class="selectDisplay form-control" idMenu=<?php echo $content['idMenu'] ?>  data-id=<?php echo $content['id'] ?>>
							<option value=<?php echo $content['idDisplay'] ?>><?php echo $content['nameDisplay'] ?></option>
							<?php foreach ($select_content['displays']  as $display):?>
								<?php if($display['id'] <> $content['idDisplay']): ?>
									<option value=<?php echo $display['id'] ?>><?php echo $display['zoneName'] . " / " .  $display['storeName'] . " / " .  $display['name'] ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>		
					</div>
					<div class="col-xs-4">
						<select name="selectMenu" class="selectMenu form-control" iddisplay=<?php echo $content['idDisplay'] ?> data-id=<?php echo $content['id'] ?>>
							<option value=<?php echo $content['idMenu'] ?>><?php echo $content['nameMenu'] ?></option>
							<?php foreach ($select_content['menus']  as $menu):?>
								<?php if($menu['id'] <> $content['idMenu']): ?>
									<option value=<?php echo $menu['id'] ?>><?php echo $menu['name'] ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-xs-4">
						<div class="pull-right">
							<a href="#">
								<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $content['nameDisplay'] ?>" delId="<?php echo $content['id'] ?>"> 
									<span class="glyphicon glyphicon-remove"></span>
								</span>
							</a>						
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
		$('#display').multiselect({
			numberDisplayed: 1,
			nonSelectedText: 'Seleccione Display...'
		});
		valarray = [];
		if ($(".selectDisplay").length>0) {
			$(".selectDisplay").each(function(){
				val = $(this).val();
				$("#display option[value=" + val + "]").hide();	
				valarray.push(val);
			});
			$(".selectDisplay > option").each(function(){
				for(var i=0; i< valarray.length;i++){				
					if($.inArray($(this).val(),valarray)!=-1){						
						$(this).hide();	
					}
				}
			});
		}
		$(".delete").click(function(){				
			$("#action_button").attr('delId', $(this).attr("delId"));	
			$("#action_button").attr('delName',$(this).attr("delName"));		
			$("#myModalLabel").html('Quitar Menú Programado');	
			$("#action_button").html('Quitar');	
			$("#delete_message").html("Está seguro que desea quitar el menú del display " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");	
			$.post('<?php echo FRONT_CONTROLLER ."menu/removeMenuDisplay" ?>',{id:delId},function(data){
				window.location.href = '<?php echo FRONT_CONTROLLER ."menu/viewMenuDisplay/".$parameters['parameters'] ?>';
			});
		});
		$(".selectDisplay").change(function(){
			dataId = $(this).attr("data-id");			
			idDisplay = $(this).val();
			idMenu = $(this).attr("idMenu");
			$.post('<?php echo FRONT_CONTROLLER ."menu/editMenuDisplay" ?>',{id:dataId,idDisplay:idDisplay,idMenu:idMenu},function(data){
				$("#display").val("");	
				window.location.href = window.location.href;
			});		
		});
		$(".selectMenu").change(function(){
			dataId = $(this).attr("data-id");			
			idDisplay = $(this).attr("idDisplay");
			idMenu = $(this).val();
			$.post('<?php echo FRONT_CONTROLLER ."menu/editMenuDisplay" ?>',{id:dataId,idDisplay:idDisplay,idMenu:idMenu},function(data){	
				$("#display").val("");	
				window.location.href = window.location.href;
			});		
		});
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."menu/viewMenus/" ?>";
		});	
});
</script>