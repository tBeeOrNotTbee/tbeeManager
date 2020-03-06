<?php 

class admin extends Controller_Abstract{

	public function __construct(){		
		parent::__construct();
		$this->user = new Model_User;
		$this->customer = new Model_Customer;
		$this->store = new Model_Store;
		$this->display = new Model_Display;
		$this->URP = new Model_URP;
		$this->audit = new Model_Audit;
	}

	public function viewUsers($parameters){
		$data['customers'] = $this->customer->getCustomers();	
		$data['main_view'] = APPPATH.'views/admin/viewUsers.php';
		require_once (APPPATH."views/layout.php");
	}

	public function index($parameters){
		$data['customers'] = $this->customer->getCustomers();	
		$data['main_view'] = APPPATH.'views/admin/viewCustomers.php';
		require_once(APPPATH."views/layout.php");
	}

	public function newUser($parameters){				
		if (isset($_POST['email'])) {
			$data['email'] = $_POST['email'];
			$data['role'] = $_POST['usrType'];
			$data['password'] = $_POST['password'];
			if (isset($_POST['usrClient']))
				$data['idCustomer'] = $_POST['usrClient'];
			if (isset($_POST['usrStore']))
				$data['idStore'] = $_POST['usrStore'];
			$this->user->newUser($data);	
			$data['alert'] = true;
		}
		$breadcrumb = array("Nuevo usuario"); 
		$data['main_view'] = APPPATH.'views/admin/newUser.php';
		require_once (APPPATH."views/layout.php");	
	}
	
	public function newCustomer($parameters){
		if (isset($_POST['name'])) {
			$data['name'] = $_POST['name'];
			$id = $this->customer->newCustomer($data);
			$oldmask = umask(0);
			//crea carpeta de contenidos para el cliente creado
			mkdir("../content/".$id,0777);
			umask($oldmask);
			$data['alert'] = true;
		}
		$breadcrumb = array("Nuevo Cliente"); 
		$data['main_view'] = APPPATH.'views/admin/newCustomer.php';
		require_once (APPPATH."views/layout.php");	
	}
	
	public function newURP($parameters){
		if (isset($_POST['name'])) {	
			$data['name'] = $_POST['name'];
			$data['idCustomer'] = $_POST['selectCustomer'];
			$data['macAddress'] = $_POST['mac'];
			if (isset($_POST['videoWall']))
				$data['videoWall'] = 1;
			else
				$data['videoWall'] = 0;
			$data['alert'] = $this->URP->newURP($data);
		}
		$data['customers'] = $this->customer->getCustomers();
		$breadcrumb = array("Nueva URP"); 
		$data['main_view'] = APPPATH.'views/admin/newURP.php';
		require_once (APPPATH."views/layout.php");	
	}

	public function viewWalls($parameters){
		$data['walls'] = $this->URP->getWalls();
		$breadcrumb = array("Walls"); 
		$data['main_view'] = APPPATH.'views/admin/viewWalls.php';
		require_once (APPPATH."views/layout.php");	
	}

	public function viewWall($parameters){
		if (isset($_POST['selectURP'])) {
			$data['idURP'] = $parameters['parameters'];
			$data['idSlave'] = $_POST['selectURP'];
			$this->URP->addURPWall($data);
		}
		$data['wall'] = $this->URP->getWallByURP($parameters['parameters']);
		$customer = $this->customer->getCustomerByURP($parameters['parameters']);
		$data['URPs'] = $this->URP->getURPsFreeByCustomer($customer['id']);
		$breadcrumb = array("Wall"); 
		$data['main_view'] = APPPATH.'views/admin/viewWall.php';
		require_once (APPPATH."views/layout.php");	
	}

	public function removeURPWall($parameters){
		$this->URP->removeURPWall($_POST['id']);
	}
	
	public function deleteUser($parameters){
		$this->user->deleteUser($_POST['id']);
	}
	
	public function deleteCustomer($parameters){
		$this->customer->deleteCustomer($_POST['id']);
	}
	
	public function deleteURP($parameters){
		$this->URP->deleteURP($parameters['parameters']);
	}

	public function deleteURPLink($parameters){
		$this->URP->unlinkURP($parameters['parameters']);
	}

	public function viewURPs($parameters){
		$data['customers'] = $this->customer->getCustomers();
		//parametro cliente, para caso en que se vuelva de detalles
		if (isset($parameters['parameters']))
			$data['idCustomer'] = $parameters['parameters'];
		$data['main_view'] = APPPATH.'views/admin/viewURPs.php';
		require_once(APPPATH."views/layout.php");
	}
	
	public function viewURPDetails($parameters){
		$data = $this->URP->getURP($parameters['parameters']);	
		$breadcrumb = array("URP",$data['name']); 					
		$data['main_view'] = APPPATH.'views/admin/viewURPDetails.php';
		require_once(APPPATH."views/layout.php");
	}
	
	public function editCustomer($parameters){
		if (isset($_POST['name'])) { 
			$this->customer->editCustomer($_POST['id'],$_POST['name']);		
			$this->index($parameters);
		} else {
			$data['customer'] = $this->customer->getCustomer($parameters['parameters']);	
			$breadcrumb = array($data['customer']['name'],"Editar Cliente");
			$data['main_view'] = APPPATH.'views/admin/editCustomer.php';
			require_once(APPPATH."views/layout.php");
		}
	}

	public function editURP($parameters){	
		if (isset($_POST['name'])) {	
			$data['id'] = $_POST['idUrp'];
			$data['name'] = $_POST['name'];
			$data['idCustomer'] = $_POST['selectCustomer'];
			$data['macAddress'] = $_POST['mac'];
			if (isset($_POST['videoWall']))
				$data['videoWall'] = 1;
			else
				$data['videoWall'] = 0;
			$result = $this->URP->editURP($data);
			$data['alert'] = true;
			$this->viewURPDetails($parameters);
		} else {
			$data['urp'] = $this->URP->getURP($parameters['parameters']);
			$data['customers'] = $this->customer->getCustomers();
			$breadcrumb = array($data['urp']['name'],"Editar URP");
			$data['main_view'] = APPPATH.'views/admin/editURP.php';
			require_once(APPPATH."views/layout.php");
		}
	}	
	
	public function newURPLink($parameters){
		$data['customers'] = $this->customer->getCustomers();
		if (isset($_POST['selectURPsNotUsed'])) {
			$data['idUrp'] = $_POST['selectURPsNotUsed'];
			$data['idDisplay'] = $_POST['selectDisplayNotUser'];
			$this->URP->setLinkURP($data);	
			$data['alert'] = true;
		}
		//parametro cliente, para caso en que se vuelva de detalles
		if (isset($parameters['parameters']))
			$data['idCustomer'] = $parameters['parameters'];
		$breadcrumb = array("Nuevo enlace URP"); 
		$data['main_view'] = APPPATH.'views/admin/newURPLink.php';
		require_once(APPPATH."views/layout.php");
	}

	public function getCustomers($parameters){
		echo json_encode($this->customer->getCustomers());			
	}
	
	public function getCustomerURPs($parameters){
		$data['urps'] = $this->URP->getURPsByCustomer($parameters['parameters']);
		$data['main_view'] = APPPATH.'views/admin/viewURPsTable.php';
		include ($data['main_view']);
	}
	
	public function getCustomerUsers($parameters){
		$data['users'] = $this->user->getUsersByCustomer($parameters['parameters']);
		$data['idUser'] = $_SESSION['idUser'];
		$data['main_view'] = APPPATH.'views/admin/viewUsersTable.php';
		include ($data['main_view']);
	}
	
	public function getRoleUsers($parameters){
		$data['users'] = $this->user->getUsersByRole($parameters['parameters']);
		$data['idUser'] = $_SESSION['idUser'];
		$data['main_view'] = APPPATH.'views/admin/viewUsersTable.php';
		include ($data['main_view']);
	}

	public function getCustomerURPsNotUsed($parameters){
		echo json_encode($this->URP->getURPsFreeByCustomer($parameters['parameters']));
	}
	
	public function getCustomerDisplaysNotUsed($parameters){
		echo json_encode($this->display->getDisplaysFreeByCustomer($parameters['parameters']));
	}

	public function viewStores($parameters){
		$data['content'] = $this->store->getStores($parameters['parameters']);		
		$data['main_view'] = APPPATH. 'views/admin/viewStores.php';
		require_once(APPPATH."views/layout.php");
	}
	
	public function editStore($parameters){
		if (isset($_POST['lat'])) {	
			$this->store->editStoreAdmin($_POST['id'], $_POST['lat'], $_POST['lng'], $_POST['installDate']);		
			exit();
		} else {
			$data['local'] = $this->store->getStore($parameters['parameters']);
			$breadcrumb = array($data['local']['name'],"Editar Local"); 			
			$data['main_view'] = APPPATH.'views/admin/editStore.php';
			require_once(APPPATH."views/layout.php");
		}
	}

	public function viewAuditLogin($parameters){
		$data['auditLogin'] = $this->audit->getAuditLogin();
		$breadcrumb = array("Auditoría Login"); 		
		$data['main_view'] = APPPATH.'views/admin/viewAuditLogin.php';
		require_once(APPPATH."views/layout.php");
	}

	public function viewAuditContent($parameters){
		$data['customers'] = $this->customer->getCustomers();
		$breadcrumb = array("Auditoría Contenidos"); 		
		$data['main_view'] = APPPATH.'views/admin/viewAuditContent.php';
		require_once(APPPATH."views/layout.php");
	}

	public function getAuditContent($parameters){
		$data['auditContent'] = $this->audit->getAuditContent();
		$data['main_view'] = APPPATH.'views/admin/viewAuditContentTable.php';
		include ($data['main_view']);
	}

	public function getAuditContentByCustomer($parameters){
		$data['auditContent'] = $this->audit->getAuditContentByCustomer($parameters['parameters']);
		$data['main_view'] = APPPATH.'views/admin/viewAuditContentTable.php';
		include ($data['main_view']);
	}
	
}