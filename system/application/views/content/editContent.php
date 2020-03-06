<script src="<?php echo JS_PATH.'bigUpload.js' ?>"></script>
<script src="<?php echo JS_PATH.'crypto-md5.js' ?>"></script>
<script src="<?php echo JS_PATH.'spark.js' ?>"></script>
<link rel="stylesheet" href="<?php echo CSS_PATH.'pick-a-color-1.2.3.min.css'?>"/>
<script>
bigUpload = new bigUpload();
function upload() {
	$(".success").hide();
	$(".error").hide();
	var validation = [];
	var filepath = $("#bigUploadFile").val();
	if (filepath=='') {
		validation['allowed'] = false;
		validation['errorMessage'] = "Al editar un contenido debe cargar un nuevo archivo.";
	} else {
		var extension = filepath.slice((Math.max(0, filepath.lastIndexOf(".")) || Infinity) + 1).toLowerCase();
		var file_parts = filepath.slice(0,filepath.lastIndexOf("."));
		var fileName_parts = file_parts.split("\\");
		var fileName = fileName_parts[2];
		var contentType = parseInt($("#selectContentType").val());
		var includedExtension = [];
		switch (contentType) {
			case <?php echo TYPE_VIDEO ?>:		
				includedExtension = ["mp4","mov","avi"];
				break;
			case <?php echo TYPE_AUDIO ?>:
				includedExtension = ["mp3","wav","ogg","flac"];
				break;
			case <?php echo TYPE_IMAGE ?>:
				includedExtension = ["jpg","png"];
				break;
			case <?php echo TYPE_ICON ?>:
				includedExtension = ["jpg","png"];
				break;
			case <?php echo TYPE_BACKGROUND ?>:
				includedExtension = ["png"];
				break;
			case null: // Handle no file extension
				break;
		}
		if ($.inArray(extension, includedExtension)!==-1)
			validation['allowed'] = true;
		else {
			validation['allowed'] = false;
			validation['errorMessage'] = "El tipo de contenido seleccionado solo admite archivos con las siguientes extensiones: " + includedExtension.join(",");
		}
		if (contentType==<?php echo TYPE_BACKGROUND ?>) {
			if (fileName != "background") {
				validation['allowed'] = false;
				validation['errorMessage'] = "El nombre del archivo debe ser: background.png";
			}
		}
	}
	if (validation["allowed"]==true)
		bigUpload.fire();	
	else {
		$(".error").show();
		$(".error").html(validation["errorMessage"]);	
	}
}
function abort() {
	bigUpload.abortFileUpload();
}
</script>
<?php if($data['content']): ?>
<div id="content-main" class="round dropshadow" >
	<nav class="bottom-buffer">
	    <div class="btn-group" role="group" aria-label="...">
			<button id="volver" type="button" class="navbtn btn btn-default">
				<div class="round button left dropshadow">Volver</div>
			</button>
	    </div>
	</nav>
	<?php echo setBreadcrumb($breadcrumb) ?>
	<div id="error" class="alert-message error selectVideo" style="display:none">
		<a class="close" href="#">x</a>
		<p>Debe seleccionar un video.</p>
	</div>
	<div id="error" class="alert-message error selectImage" style="display:none">
		<a class="close" href="#">x</a>
		<p>Debe seleccionar una imagen.</p>
	</div>
	<div id="error" class="alert-message error labelText" style="display:none">
		<a class="close" href="#">x</a>
		<p>Debe asignar un texto.</p>
	</div>
	<div id="error" class="alert-message error errorTransparencia" style="display:none">
		<a class="close" href="#">x</a>
		<p>La transparencia debe ser entre 0 y 255.</p>
	</div>
	<div id="error" class="alert-message error errorPosition" style="display:none" >
		<a class="close" href="#">x</a>
		<p>Las posiciones deben ser un número.</p>
	</div>
	<div id="error" class="alert-message error errorSize" style="display:none" >
		<a class="close" href="#">x</a>
		<p>El tamaño debe ser un número.</p>
	</div>
	<?php if(isset($data['alert']) and $data['alert'] == true): ?>
		<div class="alert-message success">  
			<a class="close" href="#">x</a>
			<p>Carga correcta.</p>
		</div> 
	<?php endif; ?>
	<div class="alert-message error">
		<a class="close" href="#">x</a>	
	</div>
	<form role="form" action="<?php echo FRONT_CONTROLLER ?>content/editFile/<?php echo $data['content']['id'] ?> " method="post">
		<fieldset>
			<input type="hidden" id="selectContentType" name="selectContentType" value=<?php echo $data['content']['idContentType'] ?>>
			<h4><?php echo $data['content']['contentTypeName'] ?></h4>
			<p id="content"><?php echo $data['content']['content'] ?></p>
			<div id="contentName" class="top-buffer">		
				<label for="name">Nombre:</label>
				<input type="text" class="form-control"  id="name" name="name" />
			</div>
			<div id="fileUpload" class="top-buffer">
				<div class="bigUpload">
					<div class="bigUploadContainer">
					<p class="help-block">No debe superar los 300 MB.</p>
						<form method="post" enctype="multipart/form-data" id="bigUploadForm">
							<input type="file" id="bigUploadFile" class="form-control" name="bigUploadFile" version="<?php echo $data['content']['version'] ?>" customerId="<?php echo $data['idCustomer'] ?>" />
							<input type="button" class="bigUploadButton" value="Subir" id="bigUploadSubmit" onclick="upload()" />
							<input type="button" class="bigUploadButton bigUploadAbort" value="Cancelar" onclick="abort()" />
						</form>
						<div id="bigUploadProgressBarContainer">
							<div id="bigUploadProgressBarFilled"></div>
						</div>
						<div id="bigUploadTimeRemaining"></div>
						<div id="bigUploadResponse"></div>
					</div>
				</div>
			</div>
			<div id="youtube" class="top-buffer">		
				<label for="youtubeID">ID:</label>
				<input type="text" class="form-control"  id="youtubeID" name="youtubeID" />
				<p class="help-block">Solo el ID de video o playlist, no la url completa.</p>
				<label class="top-buffer" for="youtubePlaylist">Playlist:</label>
				<input class="form-control" type="checkbox" name="youtubePlaylist" id="youtubePlaylist" value="true" <?php if(!$data['youtube']['type']){ echo "checked"; } ?>/>		
			</div>
			<div id="webURL" class="top-buffer">		
				<label for="url">URL:</label>
				<input type="text" class="form-control"  id="url" name="url" />		
			</div>
			<div id="label" class="top-buffer">
				<label for="video_label">Video:</label>
				<select id="video_label" class="form-control" name="video_label" style="float:left">
					<option value=>Seleccione video</option>
					<?php if(isset($data['videos'])): ?>
						<?php if($data['videos']<>""): ?>
							<?php foreach($data['videos'] as $dataContent): ?>
								<option value=<?php echo $dataContent['id'] ?>><?php echo $dataContent['name'] ?></option>
							<?php endforeach ?>
						<?php endif; ?>
					<?php endif; ?>
				</select>
				<p>		
				<label for="text_label">Texto:</label>
				<input type="text" class="form-control"  id="text_label" name="text_label" />
				<button id="enterButton" name="enterButton" type="button" class="btn btn-default">Agregar salto de línea</button>
				<p>
				<label for="position_x_label">Posición X (pixel):</label>
				<input type="number" class="form-control"  id="position_x_label" name="position_x_label" />
				<label for="position_y_label">Posición Y (pixel):</label>
				<input type="number" class="form-control"  id="position_y_label" name="position_y_label" />
				<label for="opacity_label">Transparencia (0 a 255):</label>
				<input type="number" class="form-control"  id="opacity_label" name="opacity_label" />
				<label for="colorPicker_label">Color:</label>	
				<input type="text" id="colorPicker_label" name="colorPicker_label" class="pick-a-color form-control">
				<label for="size_label">Tamaño:</label>
				<input type="number" class="form-control"  id="size_label" name="size_label" />				
			</div>
			<div id="watermark" class="top-buffer">
				<label for="video_watermark">Video:</label>
				<select id="video_watermark" class="form-control" name="video_watermark" style="float:left">
					<option value=>Seleccione video</option>
					<?php if(isset($data['videos'])): ?>
						<?php if($data['videos']<>""): ?>
							<?php foreach($data['videos'] as $dataVideo): ?>
								<option value=<?php echo $dataVideo['id'] ?>><?php echo $dataVideo['name'] ?></option>
							<?php endforeach ?>
						<?php endif; ?>
					<?php endif; ?>
				</select>
				<p>
				<label for="image_watermark">Imagen:</label>
				<select id="image_watermark" class="form-control" name="image_watermark" style="float:left">
					<option value=>Seleccione imagen</option>
					<?php if(isset($data['images'])): ?>
						<?php if($data['images']<>""): ?>
							<?php foreach($data['images'] as $dataImage): ?>
								<option value=<?php echo $dataImage['id'] ?>><?php echo $dataImage['name'] ?></option>
							<?php endforeach ?>
						<?php endif; ?>
					<?php endif; ?>
				</select>
				<p>
				<label for="position_x_watermark">Posición X (pixel):</label>
				<input type="number" class="form-control"  id="position_x_watermark" name="position_x_watermark" />
				<label for="position_y_watermark">Posición Y (pixel):</label>
				<input type="number" class="form-control"  id="position_y_watermark" name="position_y_watermark" />
				<label for="opacity_watermark">Transparencia (0 a 255):</label>
				<input type="number" class="form-control"  id="opacity_watermark" name="opacity_watermark" />			
			</div>			
			<button id="submit" class="form-control btn btn-info top-buffer" type="submit" name="submit">Editar</button>
			<input type hidden id="computed_hash" name="computed_hash">				
		  </fieldset>
    </form>
</div>
<script src="<?php echo JS_PATH?>tinycolor-0.9.15.min.js"></script>
<script src="<?php echo JS_PATH?>pick-a-color-1.2.3.min.js"></script>
<script>
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
function hideFormElements(){
	$("#fileUpload").hide();
	$("#webURL").hide();
	$("#youtube").hide();
	$("#label").hide();
	$("#watermark").hide();
	$("#contentName").hide();
	$("#submit").hide();
	$(".error").hide();
}
$(document).ready(function(){
	hideFormElements();
	$("#submit").hide();
	$("#formContent").submit(function(e){
		if (formType == <?php echo TYPE_LABEL ?>) {
			if ($("#video_label").val()=="") {
				$(".selectVideo").css("display","block");				
				e.preventDefault();
			}
			if ($("#text_label").val()=="") {
				$(".labelText").css("display","block");				
				e.preventDefault();
			}
			if ($("#opacity_label").val()=="" || $("#opacity_label").val()<0 ||  $("#opacity_label").val()>255 || !isNumber($("#opacity_label").val())) {
				$(".errorTransparencia").css("display","block");				
				e.preventDefault();
			}
			if ($("#size_label").val()=="" || !isNumber($("#size_label").val())) {
				$(".errorSize").css("display","block");				
				e.preventDefault();
			}
			if ($("#position_x_label").val()=="" || !isNumber($("#position_x_label").val())) {
				$(".errorPosition").css("display","block");				
				e.preventDefault();
			}
			if ($("#position_y_label").val()=="" || !isNumber($("#position_y_label").val())) {
				$(".errorPosition").css("display","block");				
				e.preventDefault();
			}
		} else if (formType == <?php echo TYPE_WATERMARK ?>) {
			if ($("#video_watermark").val()=="") {
				$(".selectVideo").css("display","block");				
				e.preventDefault();
			}
			if ($("#image_watermark").val()=="") {
				$(".selectImage").css("display","block");				
				e.preventDefault();
			}
			if ($("#opacity_watermark").val()=="" || $("#opacity_watermark").val()<0 ||  $("#opacity_watermark").val()>255 || !isNumber($("#opacity_watermark").val())) {
				$(".errorTransparencia").css("display","block");				
				e.preventDefault();
			}
			if ($("#position_x_watermark").val()=="" || !isNumber($("#position_x_watermark").val())) {
				$(".errorPosition").css("display","block");				
				e.preventDefault();
			}
			if ($("#position_y_watermark").val()=="" || !isNumber($("#position_y_watermark").val())) {
				$(".errorPosition").css("display","block");				
				e.preventDefault();
			}
		}
	});
	file_name = "<?php echo $data['content']['name'] ?>";
	formType = <?php echo $data['content']['idContentType'] ?>;
	$("#selectContentType").val(formType);
	$("#name").val(file_name);
	if (formType == <?php echo TYPE_WEB ?>) {
		$("#contentName").show();
		$("#url").val("<?php echo $data['content']['content'] ?>");
		$("#webURL").show();
		$("#submit").show();
	} else if (formType == <?php echo TYPE_YOUTUBE ?>) {
			$("#content").hide();
			$("#contentName").show();
			$("#youtubeID").val("<?php echo $data['youtube']['idYouTube'] ?>");
			$("#youtube").show();
			$("#submit").show();
	} else if (formType == <?php echo TYPE_LABEL ?>) {
			$("#content").hide();
			$("#contentName").show();
			$("#text_label").val("<?php echo str_replace('\\', '\\\\', $data['label']['text']) ?>");
			$("#video_label").val("<?php echo $data['label']['idVideo'] ?>");
			$("#position_x_label").val("<?php echo $data['label']['position_x'] ?>");
			$("#position_y_label").val("<?php echo $data['label']['position_y'] ?>");
			$("#opacity_label").val("<?php echo $data['label']['opacity'] ?>");
			$("#colorPicker_label").val("<?php echo $data['label']['color'] ?>");
			$(".pick-a-color").pickAColor();
			$("#size_label").val("<?php echo $data['label']['size'] ?>");
			$("#label").show();
			$("#submit").show();
	} else if (formType == <?php echo TYPE_WATERMARK ?>) {
			$("#content").hide();
			$("#contentName").show();
			$("#video_watermark").val("<?php echo $data['watermark']['idVideo'] ?>");
			$("#image_watermark").val("<?php echo $data['watermark']['idImage'] ?>");
			$("#position_x_watermark").val("<?php echo $data['watermark']['position_x'] ?>");
			$("#position_y_watermark").val("<?php echo $data['watermark']['position_y'] ?>");
			$("#opacity_watermark").val("<?php echo $data['watermark']['opacity'] ?>");
			$("#watermark").show();
			$("#submit").show();
	} else if (formType == <?php echo TYPE_VIDEO ?> || formType == <?php echo TYPE_AUDIO ?> || formType == <?php echo TYPE_IMAGE ?> || formType == <?php echo TYPE_ICON ?>) {
		$("#contentName").show();
		$("#fileUpload").show();
	} else if (formType == <?php echo TYPE_MENU ?> || formType == <?php echo TYPE_LIST ?> || formType == <?php echo TYPE_TRIVIA ?> || formType == <?php echo TYPE_TEXT ?>) {
		$("#contentName").show();								
		$("#submit").show();			
	} else if (formType == <?php echo TYPE_BACKGROUND ?>) {
		$("#fileUpload").show();
	}
	$("#enterButton").click(function(){
		$("#text_label").val($("#text_label").val()+'\\n');
		$("#text_label").focus();
	});
	$("#volver").click(function(){
		location.href="<?php  echo FRONT_CONTROLLER ."content/" ?>";
	});
});
</script>
<?php endif; ?>	