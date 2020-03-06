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
  		<?php if ($data['content']['idContentType'] == TYPE_VIDEO){ ?>
			<video style="max-height:95%;max-width:95%;" controls>
		  	<source src="<?php echo BASE_SERVER.'content/'.$data['idCustomer'].'/'.$data['content']['content'] ?>" type="video/mp4">
		  	Your browser does not support HTML5 video.
			</video>
		<?php } else if (($data['content']['idContentType'] == TYPE_IMAGE)||($data['content']['idContentType'] == TYPE_BACKGROUND)||($data['content']['idContentType'] == TYPE_ICON)){ ?>
			<img src="<?php echo BASE_SERVER.'content/'.$data['idCustomer'].'/'.$data['content']['content'] ?>" style="max-height:100%;max-width:100%;">
		<?php }  ?>
  	</div>
</div>
<script>
	$("#volver").click(function(){
		location.href="<?php  echo FRONT_CONTROLLER.'content/infoContent/'.$data['id'] ?>";
	});
</script>