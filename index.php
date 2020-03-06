<?php
date_default_timezone_set('America/Buenos_Aires');
/*
|---------------------------------------------------------------
| REPORTE DE ERRORES
|---------------------------------------------------------------
*/
error_reporting(E_ALL);
/*
|---------------------------------------------------------------
| RUTA DEL SERVER
|---------------------------------------------------------------
*/			
if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
	$system_folder = realpath(dirname(__FILE__)).'/system';

/*
|---------------------------------------------------------------
| DEFINE CONSTANTES DE APP
|---------------------------------------------------------------
| BASEPATH	- The full server path to the "system" folder
| APPPATH	- The full server path to the "application" folder
*/
define('BASEPATH', $system_folder.'/');


define('APPPATH', BASEPATH.'application/');


//obtiene la url base configurada
//require_once( APPPATH . "config/config.php");
// VAMOS A FORZAR TODAS LAS CONCURRENCIAS DE APPPATH y BASEPATH
// CREO QUE HAY UN ERROR EN COMO PHP EJECUTA LAS RUTAS
require_once(APPPATH.'config/config.php');
//require_once('system/application/config/config.php');
define('FRONT_CONTROLLER', BASE_URL);
/*
|---------------------------------------------------------------
| CARGA EL FRONT CONTROLLER
|---------------------------------------------------------------
*/	
/*
//BASPATH = c:wamp64/www/t-bee/manager/system/
echo BASEPATH.'-/////';
//APPPATH = c:wamp64/www/t-bee/manager/system/application/
echo 'Esto es APPPATH = '.APPPATH.'-/////';
// BASEURL = c:/wamp64/www/t-bee/manager/
echo BASE_URL.'-/////';*/
require_once(BASEPATH . "libraries/Autoload.php");
//require_once('system/libraries/Autoload.php');
//require_once('system/libraries/FrontController.php');
//require_once('system/application/controllers/Controller_Abstract.php');
$app = new FrontController;
$app->application();