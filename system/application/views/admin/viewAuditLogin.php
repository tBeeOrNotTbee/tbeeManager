<div id="content-main" class="round dropshadow">
	<?php echo setBreadcrumb($breadcrumb) ?>
	<?php if($data['auditLogin']<>""): ?>
		<div class="table-responsive">
			<table class="table table-condensed">
				<tr class="active" style="font-weight:bold">					
					<td>Email</td>
					<td>Status</td>
					<td>Fecha</td>
				</tr>
				<?php foreach ($data['auditLogin']  as $audit):?>
					<tr>
						<td>
							<?php echo $audit['email'] ?>
						</td>
						<td>
							<?php echo $audit['status'] ?>
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
				No se obtuvieron resultados
			</div>
		</div>
	<?php endif; ?>
</div>