<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>	
	<div id="error" class="alert-message error selectContent" style="display:none">
		<a class="close" href="#">x</a>
		<p>Debe seleccionar un contenido.</p>
	</div>
	<div id="error" class="alert-message error blockName" style="display:none">
		<a class="close" href="#">x</a>
		<p>Debe asignar un nombre al bloque.</p>
	</div>
	<div id="error" class="alert-message error errorDuracion" style="display:none">
		<a class="close" href="#">x</a>
		<p>La duración debe ser entre 1 y 1440.</p>
	</div>
	<form id="formblock" action="<?php echo FRONT_CONTROLLER ."schedule/newScheduleBlock/" . $data['idBlock'] ?>" method="post">
		<fieldset>
			<div class="row">
				<div class="col-md-12">
					<label for="name">Nombre:</label>
					<?php if ($data['blockName'] <> ""){ ?>
						 <label><?php echo $data['blockName'] ?></label>
					<?php } else { ?>
						<input type="text" class="form-control" required=required value=""  id="name" name="name" />
					<?php } ?>
				</div>
			</div>
			<div class="row top-buffer">
				<div class="col-xs-6 col-md-7">
					<select id="selectContent" class="form-control" name="idContent" style="float:left">
						<option value=>Seleccione contenido</option>
						<?php if(isset($data['contents'])): ?>
							<?php if($data['contents']<>""): ?>
								<?php foreach($data['contents'] as $dataContent): ?>
									<option value=<?php echo $dataContent['id'] ?>><?php echo $dataContent['name'] ?></option>
								<?php endforeach ?>
							<?php endif; ?>
						<?php endif; ?>
					</select>
				</div>
				<div class="col-xs-3 col-md-3">
					<input class="form-control" type="text" name="duracion" id="duracion" placeholder="Duración" />
				</div>
				<div class="col-xs-3 col-md-2">
					<input id="submit" class="form-control btn btn-info"  type="submit" name="crear" value="Crear" />
				</div>
			</div>
		</fieldset>
	</form>
</div>
<script>
	$(document).ready(function(){
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."schedule/viewCustomerScheduleBlocks" ?>";
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
			if (length=="" || length<1 || length>1440 || !parseInt(length,10))
				$(".errorDuracion").css("display","block");
		});
	});
</script>