<?php

class online extends Controller_Abstract{

	public function __construct(){
		parent::__construct();
		$this->online = new Model_Online;
		$this->customer = new Model_Customer;
	}

	public function index($parameters){
		if (($_SESSION['idRole'] == ROLE_ADMIN) || ($_SESSION['idRole'] == ROLE_MONITOR)) {
			$data['customers'] = $this->customer->getCustomers();	
			$data['main_view'] = APPPATH. 'views/online/admin.php';
		} else if ($_SESSION['idRole'] == ROLE_CUSTOMER_ADMIN) {
			$data['contents'] = $this->online->getCustomerAdminInfo($_SESSION['idCustomer']);	
			$data['main_view'] = APPPATH.'views/online/customer_admin.php';			
		}
		require_once(APPPATH."views/layout.php");
	}

	public function getAdminOnlineByCustomer($parameters){
		$data['online'] = $this->online->getAdminInfo($parameters['parameters']);
		$data['main_view'] = APPPATH.'views/online/viewAdminOnlineTable.php';
		include ($data['main_view']);
	}

	public function getAdminOnline($parameters){
		$data['online'] = $this->online->getAdminInfo();
		$data['main_view'] = APPPATH.'views/online/viewAdminOnlineTable.php';
		include ($data['main_view']);
	}
	
}