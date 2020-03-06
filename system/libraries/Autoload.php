<?php 

function custom_autoload($class) {
	$path = array('controllers','models','libraries');
    foreach($path as $dir) {
			if (file_exists(APPPATH.$dir.'/'.$class.'.php'))
			include_once(APPPATH.$dir.'/'.$class.'.php');
		else if (file_exists(BASEPATH.$dir.'/'.$class.'.php'))
			include_once(BASEPATH.$dir.'/'.$class.'.php');
    }
}
spl_autoload_register('custom_autoload');