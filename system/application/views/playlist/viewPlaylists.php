<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
		<div class="btn-group" role="group" aria-label="...">		
			<button id="nuevo_playlist_video" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Nuevo Playlist video</div>
			</button>
			<button id="nuevo_playlist_audio" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Nuevo Playlist audio</div>
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
	<?php if($data['playlists']<>""): ?>
		<ul class="list-group">
		<li class="list-group-item">
				<div class="row">
					<div class="col-xs-5 col-md-6">Nombre</div> 
					<div class="col-xs-3 col-md-3">Tipo</div> 
					<div class="col-xs-3">
						<div class="pull-right">Acciones</div>
					</div>
				</div> 
			</li>
			<?php foreach ($data['playlists']  as $playlist):?>
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-6">
						<a href="<?php  echo FRONT_CONTROLLER ."playlist/editPlaylist/".$playlist['id'] ?>"><?php echo $playlist['name'] ?></a>
					</div>
					<div class="col-xs-3 col-md-3">
						<?php echo $playlist['content'] ?>
					</div>
					<div class="col-xs-3">
						<div class="pull-right">
							<a href='<?php echo FRONT_CONTROLLER ?>playlist/editPlaylistName/<?php echo $playlist['id'] ?>'>
								<span  class="edit " edName="<?php echo $playlist['name'] ?>" edId="<?php echo $playlist['id'] ?>"> 
									<span class="glyphicon glyphicon-pencil"></span>
								</span>
							</a>
							<!-- Solo permite eliminar un Contenido que no este siendo utilizado -->
							<?php if ($playlist['delete']){ ?>
							<a href="#">
								<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $playlist['name'] ?>" delId="<?php echo $playlist['id'] ?>"> 
									<span class="glyphicon glyphicon-trash"></span>
								</span>
							</a>
							<?php } ?>
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
			$("#myModalLabel").html('Eliminar Playlist');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar el playlist " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");	
			$.post('<?php echo FRONT_CONTROLLER ."playlist/deletePlaylist" ?>',{id:delId},function(data){
				location.reload();
			});			
		});
		$("#nuevo_playlist_video").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."playlist/newPlaylistVideo/" ?>";
		});
		$("#nuevo_playlist_audio").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."playlist/newPlaylistAudio/" ?>";
		});
	});
</script>