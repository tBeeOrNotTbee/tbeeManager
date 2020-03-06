<?php 

class store extends Controller_Abstract{

	public function __construct(){
		parent::__construct();
		$this->store = new Model_Store;
		$this->customer = new Model_Customer;
		$this->zone = new Model_Zone;
	}

	public function viewStores($parameters){
		$data['content'] = $this->store->getStoresByZone($parameters['parameters']);
		$customer_data = $this->customer->getCustomerByZone($parameters['parameters']);		
		$data['customerName'] = $customer_data['customerName'];
		$data['idCustomer'] = $customer_data['idCustomer'];
		$data['zoneName'] = $customer_data['zoneName'];
		$breadcrumb = array($data['customerName'],$data['zoneName'],"Locales");
		$data['main_view'] = APPPATH.'views/store/viewStores.php';
		require_once(APPPATH."views/layout.php");
	}
	
	public function editStore($parameters){
		if (isset($_POST['name'])) {	
			$this->store->editStore($_POST['id'],$_POST['name'],$_POST['zona']);		
			exit();
		}
		$data['store'] = $this->store->getStore($parameters['parameters']);
		$data['zoneId'] = $data['store']['zoneId'];		
		$data['customerName'] = $data['store']['customerName'];
		$data['idCustomer'] = $data['store']['idCustomer'];
		$data['zoneName'] = $data['store']['zoneName'];
		$data['zones'] = $this->zone->getZonesByCustomer($data['idCustomer']);
		$breadcrumb = array($data['customerName'],$data['zoneName'],$data['store']['name'],"Editar Local");
		$data['main_view'] = APPPATH.'views/store/editStore.php';
		require_once(APPPATH."views/layout.php");
	}
	
	public function newStore($parameters){
		if(isset($_POST['name'])){
			$data['name'] = $_POST['name'];
			$data['idZone'] = $parameters['parameters'];
			$this->store->newStore($data);
			$data['alert'] = true;
		}
		$customer_data = $this->customer->getCustomerByZone($parameters['parameters']);		
		$data['customerName'] = $customer_data['customerName'];
		$data['idCustomer'] = $customer_data['idCustomer'];
		$data['zoneName'] = $customer_data['zoneName'];
		$breadcrumb = array($data['customerName'],$data['zoneName'],"Nuevo Local");
		$data['main_view'] = APPPATH.'views/store/newStore.php';
		require_once (APPPATH."views/layout.php");	
	}
	
	public function deleteStore($parameters){
		$this->store->deleteStore($_POST['id']);
	}
	
	public function getStores($parameters){
		echo json_encode($this->store->getStoresByCustomer($parameters['parameters']));
	}
	
}