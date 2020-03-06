<div id="content-main" class="round dropshadow"> 
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">	
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
		</div>			
		<div class="btn-group" role="group" aria-label="...">	
			<button id="nuevo" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Crear Respuesta</div>
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
	<?php if($data['contents']<>""): ?>
		<ul class="list-group">
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-7"></div> 
					<div class="col-xs-2">Correcta</div>
					<div class="col-xs-3">
						<div class="pull-right">Acciones</div>
					</div>
				</div> 
			</li>
			<?php foreach ($data['contents']  as $content):?>
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-7">
						<?php echo $content['answer'] ?>
					</div> 
					<div class="col-xs-2">
						<?php if($content['correct']):  ?>
							<div class="center_icon"><span class="glyphicon glyphicon-ok"></span></div>
						<?php endif; ?>
					</div>
					<div class="col-xs-3">
						<div class="pull-right">
							<a href='<?php echo FRONT_CONTROLLER ?>trivia/editAnswer/<?php echo $content['id'] ?>'>
								<span  class="edit " edName="<?php echo $content['answer'] ?>" edId="<?php echo $content['id'] ?>"> 
									<span class="glyphicon glyphicon-pencil"></span>
								</span>
							</a>
							<a href="#">
								<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $content['answer'] ?>" delId="<?php echo $content['id'] ?>">
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
			$("#myModalLabel").html('Eliminar Respuesta');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar la respuesta " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");	
			$.post('<?php echo FRONT_CONTROLLER ."trivia/deleteAnswer" ?>',{id:delId},function(data){
				location.reload();
			});
		});
		$("#nuevo").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."trivia/newAnswer/".$parameters['parameters'] ?>";
		});
		$("#volver").click(function(){
			location.href="<?php echo FRONT_CONTROLLER ."trivia/viewTriviaItems/" ?>";
		});
	});
</script>