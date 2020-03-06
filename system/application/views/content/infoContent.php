<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
  	<div class="container">
  		<p>Nombre: <?php echo $data['content']['name'] ?></p>
		<p>Tipo: <?php echo $data['content']['contentTypeName'] ?></p>
		<p>Versión: <?php echo $data['content']['version'] ?></p>
		<p>Fecha creación: <?php echo $data['content']['createTime'] ?></p>
		<p>Fecha actualización: <?php echo $data['content']['updateTime'] ?></p>
		<?php if ($data['content']['idContentType'] == TYPE_LABEL): ?>
			<p>Video: <?php echo $data['label']['video'] ?></p>
			<p>Texto: <?php echo $data['label']['text'] ?></p>
			<p>Posición x: <?php echo $data['label']['position_x'] ?></p>
			<p>Posición y: <?php echo $data['label']['position_y'] ?></p>
			<p>Transparencia: <?php echo $data['label']['opacity'] ?></p>
			<p>Color: <?php echo $data['label']['color'] ?></p>
			<p>Size: <?php echo $data['label']['size'] ?></p>
		<?php endif; ?>
		<?php if ($data['content']['idContentType'] == TYPE_WATERMARK): ?>
			<p>Video: <?php echo $data['watermark']['video'] ?></p>
			<p>Imagen: <?php echo $data['watermark']['image'] ?></p>
			<p>Posición x: <?php echo $data['watermark']['position_x'] ?></p>
			<p>Posición y: <?php echo $data['watermark']['position_y'] ?></p>
			<p>Transparencia: <?php echo $data['watermark']['opacity'] ?></p>
		<?php endif; ?>
		<?php if ($data['content']['idContentType'] == TYPE_VIDEO){ ?>
			<p>Preview: <a href="<?php echo FRONT_CONTROLLER.'content/viewContent/'.$data['content']['id'] ?>">
				<span  class="edit"> 
					<span class="glyphicon glyphicon-film"></span>
				</span>
			</a></p>
		<?php } else if (($data['content']['idContentType'] == TYPE_IMAGE)||($data['content']['idContentType'] == TYPE_BACKGROUND)||($data['content']['idContentType'] == TYPE_ICON)){ ?>
			<p>Preview: <a href="<?php echo FRONT_CONTROLLER.'content/viewContent/'.$data['content']['id'] ?>">
				<span  class="edit"> 
					<span class="glyphicon glyphicon-picture"></span>
				</span>
			</a></p>
		<?php } else if ($data['content']['idContentType'] == TYPE_WEB) { ?>
			<p>Preview: <a href="<?php echo $data['content']['content'] ?>" target="_blank">
				<span  class="edit"> 
					<span class="glyphicon glyphicon-link"></span>
				</span>
			</a></p>
		<?php } else if (($data['content']['idContentType'] == TYPE_YOUTUBE) && ($data['youtube']['type'] == 1)) { ?>
			<p>Preview: <a href="https://www.youtube.com/watch?v=<?php echo $data['youtube']['idYouTube'] ?>" target="_blank">
				<span  class="edit"> 
					<span class="glyphicon glyphicon-link"></span>
				</span>
			</a></p>
		<?php } else if (($data['content']['idContentType'] == TYPE_YOUTUBE) && ($data['youtube']['type'] == 0)) { ?>
			<p>Preview: <a href="https://www.youtube.com/playlist?list=<?php echo $data['youtube']['idYouTube'] ?>" target="_blank">
				<span  class="edit"> 
					<span class="glyphicon glyphicon-link"></span>
				</span>
			</a></p>
		<?php } ?>
		<p>
			<a href="<?php echo FRONT_CONTROLLER.'content/editContent/'.$data['content']['id'] ?>">
				<span class="edit"> 
					<span class="glyphicon glyphicon-pencil"></span>
				</span>
			</a>
			<?php if($data['blocks']==""): ?>
				<a href="#">
					<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $data['content']['name'] ?>" delId="<?php echo $data['content']['id'] ?>"> 
						<span class="glyphicon glyphicon-trash"></span>
					</span>
				</a>
			<?php endif; ?>
		</p>
		<?php if($data['blocks']<>""): ?>
			<p>Bloques:</p>
			<?php foreach ($data['blocks']  as $block):?>
				<p><a href="<?php echo FRONT_CONTROLLER ."schedule/editScheduleBlockItems/".$block['id'] ?>"><?php echo $block['name'] ?></p>
			<?php endforeach ?>
		<?php endif; ?>
  	</div>
</div>
<script>
	$(document).ready(function(){
		$(".delete").click(function(){				
			$("#action_button").attr('delId', $(this).attr("delId"));	
			$("#action_button").attr('delName',$(this).attr("delName"));		
			$("#myModalLabel").html('Eliminar Contenido');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar el contenido " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");		
			$.post('<?php echo FRONT_CONTROLLER ."content/deleteContent" ?>',{id:delId},function(data){
				location.href="<?php  echo FRONT_CONTROLLER ."content/" ?>";
			});
		});
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."content/" ?>";
		});
	});
</script>