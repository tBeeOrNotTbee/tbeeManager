<?php 

class customer extends Controller_Abstract{

	public function __construct(){
		parent::__construct();	
		$this->user = new Model_User;
		$this->customer = new Model_Customer;	
		$this->zone = new Model_Zone;
	}

	public function index($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		$customer_data = $this->customer->getCustomer($data['idCustomer']);		
		$data['customerName'] = $customer_data['name'];
		$data['contents'] = $this->zone->getZonesByCustomer($data['idCustomer']);
		$breadcrumb = array($data['customerName'],"Zonas"); 
		$data['main_view'] = APPPATH.'views/zone/viewZones.php';
		require_once(APPPATH."views/layout.php");	
	}

	public function newUser($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];	
		if (isset($_POST['email'])) {
			$data['email'] = $_POST['email'];
			$data['role'] = $_POST['usrType'];
			$data['password'] = $_POST['password'];
			if (isset($_POST['usrStore']))
				$data['idStore'] = $_POST['usrStore'];
			$this->user->newUser($data);
			$data['alert'] = true;
		}
		$breadcrumb = array("Nuevo usuario");
		$data['main_view'] = APPPATH.'views/customeradmin/newUser.php';
		require_once (APPPATH."views/layout.php");
	}

	public function deleteUser($parameters){
		$this->user->deleteUser($_POST['id']);
	}

	public function viewUsers($parameters){
		$data['users'] = $this->user->getUsersByCustomer($_SESSION['idCustomer']);
		$data['idUser'] = $_SESSION['idUser'];
		$data['main_view'] = APPPATH.'views/customeradmin/viewUsers.php';
		require_once (APPPATH."views/layout.php");
	}

	public function getCustomers($parameters){
		echo json_encode($this->customer->getCustomers());
	}
	
}