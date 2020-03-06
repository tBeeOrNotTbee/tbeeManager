<style>
	.clickable_row:hover{	
		cursor:pointer;
	}
	.table{	
		text-align:center;
	}
</style>
<div id="content-main" class="round dropshadow">
	<?php if($data['contents']<>""): ?>
		<div class="table-responsive">
			<table class="table table-condensed">
				<tr class="active" style="font-weight:bold">					
					<td>Zona</td>
					<td>Local</td>
					<td>Display</td>
					<td>Última Conexión</td>							
					<td>Espacio libre</td>
					<td>Agenda</td>
					<td>Contenido</td>
					<td>Versión</td>		
				</tr>
					<?php foreach ($data['contents']  as $content):?>
					<tr data-href='<?php echo FRONT_CONTROLLER ?>display/viewDisplay/<?php echo $content['idDisplay'] ?>' 
					<?php if ($content['minutes']>60): ?>
						class="clickable_row danger"
					<?php elseif ($content['minutes']>30): ?>
						class="clickable_row warning"
					<?php else: ?>
						class="clickable_row"
					<?php endif; ?>
					>
						<td>
							<?php echo $content['ZoneName'] ?>
						</td>
						<td>
							<?php echo $content['StoreName'] ?>
						</td>
						<td>
							<?php echo $content['DisplayName']  ?>
						</td>						
						<td>
							<?php echo $content['lastUpdate'] ?>
						</td>
						<td>
							<?php echo $content['freeSpace'] ?>
						</td>
						<td>
							<?php echo $content['scheduleTemplate'] ?>
						</td>	
						<td>
							<?php echo $content['ContentName'] ?>
						</td>			
						<td>
							<?php echo $content['ContentVersion'] ?>
						</td>						
					</tr>
				<?php endforeach ?>
			</table>
		</div>	
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
		$(".clickable_row").click(function(){
			window.document.location = $(this).data("href");
		});
	});
</script>