<?php if (!empty($data['online'])): ?>	
	<div class="table-responsive" >
			<table class="table table-condensed">
				<tr class="active" style="font-weight:bold">
					<td>Cliente</td>
					<td>Zona</td>
					<td>Local</td>
					<td>Display</td>					
					<td>Uptime</td>
					<td>Última Conexión</td>
					<td>Contenido</td>
					<td>IP</td>
					<td>Puerto</td>
					<td>Espacio libre</td>
					<td>Temperatura</td>
					<td>% Memoria</td>
					<td>% CPU</td>	
					<td>Tipo</td>	
					<td>Versión</td>									
				</tr>
					<?php foreach ($data['online']  as $online):?>
					<?php if ($_SESSION['idRole']==ROLE_MONITOR): ?>
						<tr 
					<?php else: ?>
						<tr data-href='<?php echo FRONT_CONTROLLER ?>display/viewDisplay/<?php echo $online['idDisplay'] ?>' 
					<?php endif; ?>	
					<?php if ($online['minutes']>60): ?>
						<?php if ($_SESSION['idRole']==ROLE_MONITOR): ?>
							class="danger"
						<?php else: ?>
							class="clickable_row danger"
						<?php endif; ?>	
					<?php elseif ($online['minutes']>30): ?>
						<?php if ($_SESSION['idRole']==ROLE_MONITOR): ?>
							class="warning"
						<?php else: ?>
							class="clickable_row warning"
						<?php endif; ?>
					<?php else: ?>
						<?php if ($_SESSION['idRole']!=ROLE_MONITOR): ?>
							class="clickable_row"
						<?php endif; ?>
					<?php endif; ?>
					>
						<td>
							<?php echo $online['CustomerName']  ?>
						</td>	
						<td>
							<?php echo $online['ZoneName'] ?>
						</td>
						<td>
							<?php echo $online['StoreName'] ?>
						</td>
						<td>
							<?php echo $online['DisplayName']  ?>
						</td>	
						<td>
							<?php echo $online['uptime'] ?>
						</td>
						<td>
							<?php echo $online['lastUpdate'] ?>
						</td>
						<td>
							<?php echo $online['ContentName'] ?>
						</td>						
						<td>
							<?php echo $online['ip'] ?>
						</td>			
						<td>
							<?php echo $online['port'] ?>
						</td>
						<td>
							<?php echo $online['freeSpace'] ?>
						</td>	
						<td>
							<?php echo $online['temperature'] ?>
						</td>	
						<td>
							<?php echo $online['memPercentUsed'] ?>
						</td>	
						<td>
							<?php echo $online['cpuUsage'] ?>
						</td>	
						<td>
							<?php echo $online['URPType'] ?>
						</td>	
						<td>
							<?php echo $online['URPVersion'] ?>
						</td>											
					</tr>
				<?php endforeach ?>
			</table>
		</div>
<?php else: ?>	
	<div class="row">
		<div class="col-xs-12">
			No hay registros para este cliente.
		</div>
	</div>
<?php endif; ?>