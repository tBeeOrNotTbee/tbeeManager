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
	<div id="error" class="alert-message error playlistName" style="display:none">
		<a class="close" href="#">x</a>
		<p>Debe asignar un nombre al playlist.</p>
	</div>
	<form id="formplaylist" action="<?php echo FRONT_CONTROLLER ."playlist/newPlaylistAudio/" . $data['idPlaylist'] ?>" method="post">
		<fieldset>
			<div class="row">
				<div class="col-md-12">
					<label for="name">Nombre:</label>
					<?php if ($data['playlistName'] <> ""){ ?>
						 <label><?php echo $data['playlistName'] ?></label>
					<?php } else { ?>
						<input type="text" class="form-control" required=required value=""  id="name" name="name" />
					<?php } ?>
				</div>
			</div>
			<div class="row top-buffer">
				<div class="col-xs-9 col-md-9">
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
					<input id="submit" class="form-control btn btn-info"  type="submit" name="crear" value="Crear" />
				</div>
			</div>
		</fieldset>
	</form>		
</div>
<script>
	$(document).ready(function(){
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."playlist/index" ?>";
		});
		$('#formplaylist').submit(function(e){
			$(".alert-message").css("display","none");	
			if ($("#name").val()=="") {
				$(".playlistName").css("display","block");				
				e.preventDefault();
			}
			if ($("#selectContent").val()=="") {
				$(".selectContent").css("display","block");				
				e.preventDefault();
			}
		});
	});
</script>