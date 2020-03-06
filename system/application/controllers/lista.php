<?php 

class lista extends Controller_Abstract{ 

	public function __construct(){
		parent::__construct();
		$this->lista = new Model_Lista;
		$this->content = new Model_Content;
		$this->display = new Model_Display;
	}

	public function index($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];	
		$data['contents'] = $this->lista->getListsByCustomer($data['idCustomer']);
		$breadcrumb = array("Listas");
		$data['main_view'] = APPPATH.'views/lista/viewListas.php';
		require_once(APPPATH."views/layout.php");
	}

	public function newLista($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];	
		if (isset($_POST['name'])) {
			$data['name'] = $_POST['name'];	
			$this->lista->newLista($data);
			$data['alert'] = true;	
		}
		$breadcrumb = array("Listas","Nueva Lista");
		$data['main_view'] = APPPATH.'views/lista/newLista.php';			
		require_once(APPPATH."views/layout.php");	
	}

	public function deleteLista($parameters){
		$this->lista->deleteLista($_POST['id']);
	}

	public function editLista($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset($_POST['name'])) {
			$data['id'] = $parameters['parameters'];
			$data['name'] = $_POST['name'];
			$this->lista->editLista($data);
			$data['alert'] = true;
		}
		$data_rx = $this->lista->getLista($parameters['parameters']);
		$breadcrumb = array("Listas","Editar Lista"); 
		$data['main_view'] = APPPATH.'views/lista/editLista.php';			
		require_once(APPPATH."views/layout.php");
	}

	public function viewTipoItems($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];		
		$data['contents'] = $this->lista->getTipoItemsByCustomer($data['idCustomer']);
		$breadcrumb = array("Listas","Tipo Items");
		$data['main_view'] = APPPATH.'views/lista/viewTipoItems.php';
		require_once(APPPATH."views/layout.php");
	}

	public function newTipoItem($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset($_POST['name'])) {
			$data['name'] = $_POST['name'];	
			$data['backgroundColor'] = $_POST['backgroundColor'];	
			$data['fontColor'] = $_POST['fontColor'];	
			$this->lista->newTipoItem($data);
			$data['alert'] = true;	
		}
		$breadcrumb = array("Listas","Tipo Items","Nuevo Tipo Item"); 
		$data['main_view'] = APPPATH.'views/lista/newTipoItem.php';			
		require_once(APPPATH."views/layout.php");	
	}

	public function deleteTipoItem($parameters){
		$this->lista->deleteTipoItem($_POST['id']);
	}

	public function editTipoItem($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset($_POST['name'])) {
			$data['id'] = $parameters['parameters'];
			$data['name'] = $_POST['name'];	 
			$data['backgroundColor'] = $_POST['backgroundColor'];	
			$data['fontColor'] = $_POST['fontColor'];
			$this->lista->editTipoItem($data);
			$data['alert'] = true;	
		}
		$data_rx = $this->lista->getTipoItem($parameters['parameters']);
		$breadcrumb = array("Listas","Tipo Items","Editar Tipo Item");
		$data['main_view'] = APPPATH.'views/lista/editTipoItem.php';			
		require_once(APPPATH."views/layout.php");	
	}

	public function viewListItems($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];				
		$data['contents'] = $this->lista->getListItemsByCustomer($data['idCustomer']);
		$breadcrumb = array("Listas","List Items");
		$data['main_view'] = APPPATH.'views/lista/viewListItems.php';
		require_once(APPPATH."views/layout.php");
	}

	public function newListItem($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset($_POST['name'])) {
			$data['name'] = $_POST['name'];	
			$data['idTipoItem'] = $_POST['idTipoItem'];	
			$data['selectIconos'] = "";
			if (isset($_POST['selectIconos']))
				$data['selectIconos'] = $_POST['selectIconos'];
			$this->lista->addListItem($data);
			$data['alert'] = true;	
		}
		$data['tipositem'] = $this->lista->getTipoItemsByCustomer($data['idCustomer']);
		$data['iconos'] = $this->content->getContentsByType($data['idCustomer'],TYPE_ICON);
		$breadcrumb = array("Listas","List Items","Nuevo List Item"); 
		$data['main_view'] = APPPATH.'views/lista/newListItem.php';
		require_once(APPPATH."views/layout.php");	
	}

	public function deleteListItem($parameters){
		$this->lista->deleteListItem($_POST['id']);
	}

	public function editListItem($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset($_POST['name'])) {
			$data['id'] = $parameters['parameters'];
			$data['name'] = $_POST['name'];	 
			$data['idTipoItem'] = $_POST['idTipoItem'];
			$data['selectIconos'] = "";
			if (isset($_POST['selectIconos']))
				$data['selectIconos'] = $_POST['selectIconos'];
			$this->lista->editListItem($data);
			$data['alert'] = true;
		}
		$data_rx = $this->lista->getListItems($parameters['parameters']);
		$data['tipositem'] = $this->lista->getTipoItemsByCustomer($data['idCustomer']);
		$data['iconos'] = $this->content->getContentsByType($data['idCustomer'],TYPE_ICON);
		$breadcrumb = array("Listas","List Items","Editar List Item");
		$data['main_view'] = APPPATH.'views/lista/editListItem.php';			
		require_once(APPPATH."views/layout.php");
	}

	public function viewListContent($parameters){
		$idLista = $parameters['parameters'];
		if (isset($_POST['idSelectedContent'])) {
			$rx_data['idList'] = $idLista;
			$rx_data['idListItem'] = $_POST['idSelectedContent']; 
			$this->lista->addListContent($rx_data);		
		}
		$data['idCustomer'] = $_SESSION['idCustomer'];
		$data['allListItems'] = $this->lista->getListItemsByCustomer($data['idCustomer']);
		$data['contents'] = $this->lista->getListContent($idLista);
		$data['lista'] = $this->lista->getLista($idLista);
		$breadcrumb = array("Listas",$data['lista']['name'],"List Content");
		$data['main_view'] = APPPATH.'views/lista/viewListContent.php';
		require_once(APPPATH."views/layout.php");
	}

	public function deleteListContent($parameters){
		$this->lista->deleteListContent($_POST['id'],$_POST['orden'],$_POST['idList']);
	}

	public function editListContentOrder($parameters){
		$this->lista->editListContentOrder($_POST['order'],$_POST['id']);
	}

	public function editListContentDisabled($parameters){
		$this->lista->editListContentDisabled($_POST['disabled'],$_POST['id']);
	}

	public function viewListDisplay($parameters){
		if (isset($_POST['display']) and !empty($_POST['display'])) {
			$data['displays'] = $_POST['display'];	
			$data['idList'] = $_POST['lista'];		
			$data['idCustomer'] = $_SESSION['idCustomer'];	
			$this->lista->addListDisplay($data);		
		}
		$breadcrumb = array("Asignar Lista");
		$select_content['displays'] = $this->display->getDisplaysByCustomer($_SESSION['idCustomer']);	
		$select_content['listas'] = $this->lista->getListsByCustomer($_SESSION['idCustomer']);
		$data['contents'] = $this->lista->getListDisplay($_SESSION['idCustomer']);
		$data['main_view'] = APPPATH.'views/lista/viewListDisplay.php';
		require_once(APPPATH."views/layout.php");
	}

	public function deleteListDisplay($parameters){
		$this->lista->removeListDisplay($_POST['id']);		
	}

	public function editListDisplay($parameters){
		$this->lista->editListDisplay($_POST['idList'],$_POST['idDisplay'],$_POST['id']);
	}

}