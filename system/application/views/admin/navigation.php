<ul class="nav navbar-nav">	 		 			
	<li 
	<?php if ($parameters['action'] == '' or ($parameters['controller'] == 'admin' and $parameters['action'] == 'editCustomer') or ($parameters['controller'] == 'admin' and $parameters['action'] == 'newCustomer') or $parameters['controller'] == 'store' or $parameters['controller'] == 'zone' or $parameters['controller'] == 'display')
		echo 'class="active"';
	?>>
		<a href="<?php echo FRONT_CONTROLLER ."admin/"?>">Clientes</a>
	</li>
	<li 
	<?php if ($parameters['action'] == 'viewURPs' or $parameters['action'] == 'newURP' or $parameters['action'] == 'editURP' or $parameters['action'] == 'newURPLink' or $parameters['action'] == 'viewURPDetails') 
		echo 'class="active"';
	?>>
		<a href="<?php echo FRONT_CONTROLLER ."admin/viewURPs" ?>">URPs</a>
	</li>
	<li 
	<?php if ($parameters['action'] == 'viewUsers' or $parameters['action'] == 'newUser')
		echo 'class="active"';
	?>>
		<a href= "<?php echo FRONT_CONTROLLER ."admin/viewUsers" ?>">Usuarios</a>
	</li>
	<li 
	<?php if ($parameters['controller'] == 'online')
		echo 'class="active"';
	?>>
		<a href="<?php echo FRONT_CONTROLLER ."online/index"?>">Online</a>
	</li>
	<li 
	<?php if (($parameters['controller'] == 'admin' and $parameters['action'] == 'viewStores') or ($parameters['controller'] == 'admin' and $parameters['action'] == 'editStore'))
		echo 'class="active"';
	?>>
		<a href="<?php echo FRONT_CONTROLLER ."admin/viewStores"?>">Locales</a>
	</li>
	<li role="presentation" class="dropdown">
		<li 
		<?php if (($parameters['controller'] == 'admin' and $parameters['action'] == 'viewAuditLogin') or ($parameters['controller'] == 'admin' and $parameters['action'] == 'viewAuditContent'))
			echo 'class="active"';
		?>>
		    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
		      Auditoría <span class="caret"></span>
		    </a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
				<li 
				<?php if ($parameters['controller'] == 'admin' and $parameters['action'] == 'viewAuditLogin')
					echo 'class="active"';
				?>>
					<a href="<?php echo FRONT_CONTROLLER ."admin/viewAuditLogin"?>">Login</a>
				</li>
				<li 
				<?php if ($parameters['controller'] == 'admin' and $parameters['action'] == 'viewAuditContent')
					echo 'class="active"';
				?>>
					<a href="<?php echo FRONT_CONTROLLER ."admin/viewAuditContent"?>">Contenidos</a>
				</li>
			</ul>
		</li>
	</li>
</ul>
