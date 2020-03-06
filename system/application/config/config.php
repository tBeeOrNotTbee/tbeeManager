<?php
if(!defined('BASEPATH')) exit();
/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
*/
define('BASE_FOLDER', "manager/");
//define('BASE_SERVER',"http://www.tbee.tv/");
//ESTE DEFINE FUNCIONA CON mysite
define('BASE_SERVER', "http://mysite/");
//define('BASE_SERVER', "http://192.168.1.39/t-bee/");

define('BASE_URL',BASE_SERVER.BASE_FOLDER);

define('CSS_PATH',BASE_URL.'css/');
define('JS_PATH',BASE_URL.'js/');
define('IMG_PATH',BASE_URL.'img/');
/*
define('BASE_MANAGER', "./");
define('CSS_PATH',BASE_MANAGER.'css/');
define('JS_PATH',BASE_MANAGER.'js/');
define('IMG_PATH',BASE_MANAGER.'img/');
*/
/*
|--------------------------------------------------------------------------
| APP CONSTANTS
|--------------------------------------------------------------------------
*/
//ID Weekday
define('ALL_DAYS',0);
define('SUNDAY',1);
define('MONDAY',2);
define('TUESDAY',3);
define('WEDNESDAY',4);
define('THURSDAY',5);
define('FRIDAY',6);
define('SATURDAY',7);
//ID Role
define('ROLE_ADMIN',1);
define('ROLE_CUSTOMER_ADMIN',2);
define('ROLE_STORE_ADMIN',3);
define('ROLE_CONTENT_MANAGER',4);
define('ROLE_MONITOR',5);
//ID Content Type
define('TYPE_VIDEO',1);
define('TYPE_IMAGE',2);
define('TYPE_WEB',3);
define('TYPE_MENU',4);
define('TYPE_LIST',5);
define('TYPE_SHIFTS',6);
define('TYPE_TRIVIA',7);
define('TYPE_ICON',8);
define('TYPE_TEXT',9);
define('TYPE_BACKGROUND',10);
define('TYPE_YOUTUBE',11);
define('TYPE_PLAYLIST',12);
define('TYPE_AUDIO',13);
define('TYPE_LABEL',14);
define('TYPE_WATERMARK',15);
//ID Status
define('STATUS_WRONG_EMAIL',1);
define('STATUS_WRONG_PASSWORD',2);
define('STATUS_LOGIN_SUCCESS',3);
define('STATUS_NEW_CONTENT',4);
define('STATUS_EDIT_CONTENT',5);
define('STATUS_DELETE_CONTENT',6);
