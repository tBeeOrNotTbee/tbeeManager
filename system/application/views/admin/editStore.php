<link rel="stylesheet" href="<?php echo CSS_PATH.'bootstrap-datetimepicker.css'?>"/>
<div id="content-main" class="round dropshadow">
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>	
	<form>					
		<label for="lat">Latitud:</label>
		<input type="text" class="form-control" required id="lat" name="lat" value="<?php echo $data['local']['lat'] ?>" />
		<label class="top-buffer" for="lng">Longitud:</label>
		<input type="text" class="form-control" required id="lng" name="lng" value="<?php echo $data['local']['lng'] ?>" />
		<label class="top-buffer" for="installDate">Fecha instalación:</label>
        <div class='input-group date' id='datetimepicker'>
            <input id="installDate" name="installDate" type="text" class="form-control" value="<?php echo $data['local']['installDate']  ?>" />
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-time"></span>
            </span>
        </div>
		<input class="form-control btn btn-info top-buffer" type="submit" name="editar" id="editar" value="Editar" />
	</form>	
</div>
<script src="<?php echo JS_PATH?>moment-with-locales.js"></script>
<script src="<?php echo JS_PATH?>bootstrap-datetimepicker.js"></script>
<script>
    $(function () {
        $('#datetimepicker').datetimepicker({format: 'YYYY:MM:DD'});        
    });
	$(document).ready(function(){
		$("#volver").click(function(){
			location.href="<?php  echo FRONT_CONTROLLER ."admin/viewStores" ?>";
		});
		$("form").submit(function(e){
			e.preventDefault();
			lat = $("#lat").val();
			lng = $("#lng").val();
			installDate = $("#installDate").val();
			$.post('<?php echo FRONT_CONTROLLER ."admin/editStore" ?>',{lat: lat, lng:lng, installDate: installDate, id:<?php echo $data['local']['id'] ?>},function(data){
				location.href="<?php  echo FRONT_CONTROLLER ."admin/viewStores" ?>";
			});	
		});
	});
</script>