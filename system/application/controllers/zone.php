<?php 

class zone extends Controller_Abstract{
	
	public function __construct(){
		parent::__construct();
		$this->zone = new Model_Zone;
		$this->customer = new Model_Customer;
	}
 
	public function getCustomerData($parameters){
		if ($_SESSION['idRole'] == ROLE_ADMIN) {
			$data['admin'] = true;		
			$data['idCustomer'] = $parameters['parameters'];
		} else if ($_SESSION['idRole'] == ROLE_CUSTOMER_ADMIN) {						
			$data['customerAdmin'] = true;	
			$customer_admin = true;			
			$data['idCustomer'] = $_SESSION['idCustomer'];							
		}
		$customer_data = $this->customer->getCustomer($data['idCustomer']);		
		$data['customerName'] = $customer_data['name'];
		return $data;
	}

	public function newZone($parameters){
		$data = $this->getCustomerData($parameters);
		if (isset($_POST['name'])) {
			$this->zone->newZone($_POST['name'],$data['idCustomer']);
			$data['alert'] = true;
		}
		$breadcrumb = array($data['customerName'],"Nueva Zona");
		$data['main_view'] = APPPATH.'views/zone/newZone.php';
		require_once (APPPATH."views/layout.php");	
	}
	
	public function viewZones($parameters){
		$data = $this->getCustomerData($parameters);
		$data['contents'] = $this->zone->getZonesByCustomer($data['idCustomer']);
		$breadcrumb = array($data['customerName'],"Zonas");
		$data['main_view'] = APPPATH.'views/zone/viewZones.php';
		require_once(APPPATH."views/layout.php");
	}
	
	public function editZone($parameters){
		if (isset($_POST['name'])) {	
			$this->zone->editZone($_POST['id'],$_POST['name']);		
			exit();
		} else {
			$data['zone'] = $this->zone->getZone($parameters['parameters']);
			$breadcrumb = array($data['zone']['customerName'],$data['zone']['name'],"Editar Zona");
			$data['main_view'] = APPPATH.'views/zone/editZone.php';
			require_once(APPPATH."views/layout.php");
		}
	}
	
	public function deleteZone($parameters){
		$this->zone->deleteZone($_POST['id']);
	}	
	
}