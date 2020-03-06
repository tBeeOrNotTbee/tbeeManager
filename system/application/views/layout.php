<html> 
	<head>		
		<title>T-Bee - Manager</title> 
		<link rel="icon" href="<?php echo IMG_PATH.'favicon.ico'?>" type="image/x-icon"/>
		<meta name="description" content="T-Bee - Manager"/>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
		<meta http-equiv="content-language" content="es"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
	    <link rel="stylesheet" href="<?php echo CSS_PATH.'bootstrap.min.css'?>"/>
		<link rel="stylesheet" href="<?php echo CSS_PATH.'bootstrap-navbar-static-top.css'?>"/>
		<link rel="stylesheet" href="<?php echo CSS_PATH.'custom_layout.css'?>"/>
		<link rel="stylesheet" href="<?php echo CSS_PATH.'alerts.css'?>"/>
		<script src="<?php echo JS_PATH.'jquery-1.12.3.min.js'?>"></script>
		<script src="<?php echo JS_PATH.'bootstrap.min.js'?>"></script>
		<link rel="shortcut icon" href="<?php echo IMG_PATH.'logo57.png'?>" />
		<link rel="apple-touch-icon" href="<?php echo IMG_PATH.'logo57.png'?>" />
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo IMG_PATH.'logo72.png'?>" />
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo IMG_PATH.'logo114.png'?>" />
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo IMG_PATH.'logo144.png'?>" />
	</head>
	<body>
		<nav class="navbar navbar-inverse navbar-static-top">
			 <div class="container">
				<div class="navbar-header">
					 <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					  </button>
					  <a class="navbar-brand" href="#">T-Bee Manager</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<?php include($this->data['navigation']) ?>	
					<ul class="nav navbar-nav navbar-right">
						<li><a href="<?php echo FRONT_CONTROLLER."user/account" ?>"><?php echo $_SESSION['email'] ?></a></li>
						<li><a href="<?php echo FRONT_CONTROLLER."main/logout" ?>">Log Out</a></li>
					</ul>							
				</div>
			</div>
		</nav>
		<!-- Modal confirmar -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			  </div>
			  <div id="delete_message" class="modal-body">
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button id="action_button" delName="" delId="" type="button" class="btn btn-primary"></button>
			  </div>
			</div>
		  </div>
		</div>	
		<!-- Modal alerta-->		  
		<div id="AlertModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title alert-modal-title"></h4>
					</div>
					<div class="modal-body">
						<p id="alert-modal-message"></p>							
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal Copiar -->
		<div class="modal fade" id="copyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="copyModalLabel"></h4>
			  </div>
			  <div id="copy_message" class="modal-body">
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button id="copy_button" delName="" delId="" type="button" class="btn btn-primary"></button>
			  </div>
			</div>
		  </div>
		</div>	
		<!-- Modal Reemplazar -->
		<div class="modal fade" id="replaceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="replaceModalLabel"></h4>
			  </div>
			  <div id="replace_message" class="modal-body">
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button id="replace_button" delName="" delId="" type="button" class="btn btn-primary"></button>
			  </div>
			</div>
		  </div>
		</div>
		<div class="container">	
			<?php include($data['main_view']) ?>
		</div>
	</body>
</html>