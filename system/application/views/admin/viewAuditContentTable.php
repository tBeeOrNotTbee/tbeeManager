<?php if (!empty($data['auditContent'])): ?>	
	<div class="table-responsive">
		<table class="table table-condensed">
			<tr class="active" style="font-weight:bold">					
				<td>Cliente</td>
				<td>Usuario</td>
				<td>Acción</td>
				<td>Tipo</td>
				<td>Contenido</td>
				<td>Fecha</td>
			</tr>
			<?php foreach ($data['auditContent']  as $audit):?>
				<tr>
					<td>
						<?php echo $audit['customer'] ?>
					</td>
					<td>
						<?php echo $audit['user'] ?>
					</td>
					<td>
						<?php echo $audit['status'] ?>
					</td>
					<td>
						<?php echo $audit['contentType'] ?>
					</td>
					<td>
						<?php echo $audit['content'] ?>
					</td>
					<td>
						<?php echo $audit['date'] ?>
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