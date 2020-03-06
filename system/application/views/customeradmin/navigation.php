<ul class="nav navbar-nav">	 				
	<li 
	<?php if (($parameters['action'] == '' and $parameters['controller'] == 'customer') or $parameters['controller'] == 'store' or $parameters['controller'] == 'zone' or $parameters['controller'] == 'display')
		echo 'class="active"';
	?>>
		<a href="<?php echo FRONT_CONTROLLER ."customer/"?>">Zonas</a>
	</li>
	<li 
	<?php if ($parameters['controller'] == 'schedule')
		echo 'class="active"';
	?>>
		<a href= "<?php echo FRONT_CONTROLLER ."schedule/viewScheduleTemplate" ?>">Agenda</a>
	</li>
	<li 
	<?php if ($parameters['controller'] == 'calendar')
		echo 'class="active"';
	?>>
		<a href= "<?php echo FRONT_CONTROLLER ."calendar/viewCalendar" ?>">Calendario</a>
	</li>
	<li 
	<?php if ($parameters['action'] == 'viewUsers' or $parameters['action'] == 'newUser')
		echo 'class="active"';
	?>>
		<a href= "<?php echo FRONT_CONTROLLER ."customer/viewUsers" ?>">Usuarios</a>
	</li>
	<li role="presentation" class="dropdown">	 				
		<li 
		<?php if ($parameters['controller'] == 'content' or $parameters['controller'] == 'menu' or $parameters['controller'] == 'lista' or $parameters['controller'] == 'trivia')
			echo 'class="active"';
		?>>
			<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
		      Contenidos <span class="caret"></span>
		    </a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
					<li 
					<?php if ($parameters['controller'] == 'content')
						echo 'class="active"';
					?>>
						<a href="<?php echo FRONT_CONTROLLER ."content/"?>">Contenido</a>
					</li>
					<li 
					<?php if ($parameters['controller'] == 'menu')
						echo 'class="active"';
					?>>
						<a href= "<?php echo FRONT_CONTROLLER ."menu/viewMenus" ?>">Menú</a>
					</li>
					<li 
					<?php if ($parameters['controller'] == 'lista')
						echo 'class="active"';
					?>>
						<a href= "<?php echo FRONT_CONTROLLER ."lista/" ?>">Lista</a>
					</li>
					<li 
					<?php if ($parameters['controller'] == 'trivia')
						echo 'class="active"';
					?>>
						<a href= "<?php echo FRONT_CONTROLLER ."trivia/" ?>">Trivia</a>
					</li>
					<li 
					<?php if ($parameters['controller'] == 'playlist')
						echo 'class="active"';
					?>>
						<a href= "<?php echo FRONT_CONTROLLER ."playlist/" ?>">Playlist</a>
					</li>
			  </ul>
		</li>
	</li>
	<li 
	<?php if ($parameters['controller'] == 'online')
		echo 'class="active"';
	?>>
		<a href="<?php echo FRONT_CONTROLLER ."online/index"?>">Online</a>
	</li>
</ul>