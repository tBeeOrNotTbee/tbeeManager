<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="nuevo" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Nuevo usuario</div>
			</button>
	    </div>
	</nav>
	<?php if($data['users']<>""): ?>
		<ul class="list-group top-buffer">
			<li class="list-group-item">
				<div class="row">	
					<div class="col-xs-4">
						Nombre
					</div> 
					<div class="col-xs-4">
						Tipo
					</div>
					<div class="col-xs-4">
						<div class="pull-right">Acciones</div>
					</div>
				</div>		
			</li>
			<?php foreach ($data['users']  as $user):?>
			<li class="list-group-item">
				<div class="row">
					<div class="col-xs-4">
						<?php echo $user['email'] ?>
					</div>
					<div class="col-xs-4">
						<?php echo $user['roleName'] ?>
					</div>
					<div class="col-xs-4">
						<div class="pull-right">
							<a href='<?php echo FRONT_CONTROLLER ?>user/account/<?php echo $user['id'] ?>'>
								<span  class="edit" edName="<?php echo $user['email'] ?>" edId="<?php echo $user['id'] ?>"> 
									<span class="glyphicon glyphicon-pencil"></span>
								</span>
							</a>
							<?php if ($user['id'] != $data['idUser']):  ?>
							<a href="#">
								<span class="delete" data-toggle="modal" data-target="#myModal" delName="<?php echo $user['email'] ?>" delId="<?php echo $user['id'] ?>"> 
									<span class="glyphicon glyphicon-trash"></span>
								</span>
							</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</li>
		<?php endforeach ?>
	</ul>	
	<?php else: ?>
		<div class="row top-buffer">	
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
			$("#myModalLabel").html('Eliminar Usuario');	
			$("#action_button").html('Eliminar');	
			$("#delete_message").html("Está seguro que desea eliminar el usuario " + $(this).attr("delName") + "?");		
		});
		$("#action_button").click(function(){
			delId = $(this).attr("delId");			
			$.post('<?php echo FRONT_CONTROLLER ."customer/deleteUser" ?>',{id:delId},function(data){
				location.reload();
			});
		});
		$("#nuevo").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."customer/newUser" ?>";
		});
	});
</script>