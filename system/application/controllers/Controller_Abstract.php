<?php

abstract class Controller_Abstract {

     public function __construct() {           
		header('Content-Type: text/html; charset=utf-8');
		include_once(APPPATH.'helpers/views_helper.php');
		if (isset($_SESSION['idRole'])) {
			switch ($_SESSION['idRole']) {
				case ROLE_ADMIN:	
					$this->data['navigation'] = APPPATH.'views/admin/navigation.php';	
				break;
				case ROLE_CUSTOMER_ADMIN:
					$this->data['navigation'] = APPPATH.'views/customeradmin/navigation.php';	
				break;
				case ROLE_STORE_ADMIN:
					$this->data['navigation'] = APPPATH.'views/storeadmin/navigation.php';
				break;
				case ROLE_CONTENT_MANAGER:
					$this->data['navigation'] = APPPATH.'views/contentadmin/navigation.php';
				break;
				case ROLE_MONITOR:
					$this->data['navigation'] = APPPATH.'views/monitor/navigation.php';
				break;
			}
		}
    }
	             
}