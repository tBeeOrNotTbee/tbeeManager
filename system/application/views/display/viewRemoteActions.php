<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<button type="button" class="btn btn-info">
		<span id="remote_restart" data-toggle="modal" data-target="#myModal">Reiniciar</span>
	</button>
	<button type="button" class="btn btn-info">
		<span id="remote_background" data-toggle="modal" data-target="#myModal">Actualizar Background</span>
	</button>
</div>	
<script>
$(document).ready(function(){
	function sendCommand(accion){
		if (accion=="remote_restart"){
			$.post('<?php echo FRONT_CONTROLLER ."display/remoteRestart/".$idDisplay."" ?>',function(data){
				location.reload();
			});
		}
		else if (accion=="remote_background"){
			$.post('<?php echo FRONT_CONTROLLER ."display/remoteBackground/".$idDisplay."" ?>',function(data){
				location.reload();
			});
		}
	}
	$("#volver").click(function(){
		location.href="<?php  echo FRONT_CONTROLLER ."display/viewDisplay/".$idDisplay ?>";
	});	
	$("#remote_restart").click(function(){
		accion = $(this).attr("id");
		$("#myModalLabel").html('Reiniciar display');	
		$("#action_button").html('Reiniciar');	
		$("#delete_message").html("Está seguro que desea reiniciar el display?");		
	});
	$("#remote_background").click(function(){
		accion = $(this).attr("id");
		$("#myModalLabel").html('Actualizar Background');	
		$("#action_button").html('Actualizar');	
		$("#delete_message").html("Está seguro que desea actualizar el background del display?");		
	});
	$("#action_button").click(function(){	
		sendCommand(accion);
	});
});
</script>