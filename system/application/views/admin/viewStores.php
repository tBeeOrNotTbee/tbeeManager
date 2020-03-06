<div id="content-main" class="round dropshadow">
	<?php if($data['content']<>""): ?>
		<div class="table-responsive">
			<table class="table table-condensed">
				<tr class="active" style="font-weight:bold">					
					<td>Local</td>
					<td>Latitud</td>
					<td>Longitud</td>
					<td>Instalación</td>							
					<td>
						<div class="pull-right">Acciones</div>
					</td>	
				</tr>
				<?php foreach ($data['content']  as $content):?>
					<tr>
						<td>
							<?php echo $content['cliente'] ?> / <?php echo $content['zona'] ?> / <?php echo $content['local'] ?>
						</td>
						<td>
							<?php echo $content['lat'] ?>
						</td>
						<td>
							<?php echo $content['lng'] ?>
						</td>
						<td>
							<?php echo $content['installDate'] ?>
						</td>
						<td>
							<div class="pull-right">
								<a href='<?php echo FRONT_CONTROLLER ?>admin/editStore/<?php echo $content['id'] ?>'>
									<span  class="edit " edId="<?php echo $content['id'] ?>"> 
										<span class="glyphicon glyphicon-pencil"></span>
									</span>
								</a>
							</div>
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