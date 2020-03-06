<?php 

class menu extends Controller_Abstract{ 

	public function __construct(){
		parent::__construct();
		$this->menu = new Model_Menu;
		$this->display = new Model_Display;
		$this->content = new Model_Content;
	}

	public function addMenuDisplay($parameters){
		$data['idMenu'] = $_POST['selMenu'];	
		$data['idDisplay'] = $_POST['selDisplay'];
		$this->menu->addMenuDisplay($data);		
	}

	public function viewMenus($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];	
		$data['contents'] = $this->menu->getMenusByCustomer($data['idCustomer']);
		$breadcrumb = array("Menús");
		$data['main_view'] = APPPATH.'views/menu/viewMenus.php';		
		require_once(APPPATH."views/layout.php");
	} 

	public function viewMenuItems($parameters){
		$idMenu = $parameters['parameters'];
		if (isset($_POST['idSelectedContent'])) {
			$rx_data['idMenu'] = $idMenu;
			$rx_data['idMenuItem'] = $_POST['idSelectedContent']; 
			$this->menu->addMenuItem($rx_data);		
		}
		$data['idCustomer'] = $_SESSION['idCustomer'];
		$data['allMenuItems'] = $this->menu->getMenuItemsByCustomer($data['idCustomer']);
		$data['contents'] = $this->menu->getMenuItemsByMenu($idMenu);
		$data['menu'] = $this->menu->getMenu($idMenu);
		$breadcrumb = array("Menús",$data['menu']['name'],"Menú Content");
		$data['main_view'] = APPPATH.'views/menu/viewMenuItems.php';		
		require_once(APPPATH."views/layout.php");	
	}

	public function editMenuContentOrder($parameters){
		$this->menu->editMenuContentOrder($_POST['order'],$_POST['id']);
	}

	public function listMenuItems($parameters){
		$idMenu = $parameters['parameters'];
		$data['idCustomer'] = $_SESSION['idCustomer'];
		$data['contents'] = $this->menu->getMenuItemsByCustomer($data['idCustomer']);
		$breadcrumb = array("Menús","Menú Item");
		$data['main_view'] = APPPATH.'views/menu/listMenuItems.php';		
		require_once(APPPATH."views/layout.php");	
	}

	public function deleteMenuItem($parameters){
		$this->menu->deleteMenuItem($_POST['id']);
	}

	public function deleteMenuItemContent($parameters){
		$this->menu->deleteMenuContent($_POST['id'],$_POST['orden'],$_POST['idMenu']);
	}

	public function newMenu($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset( $_POST['name'])) {
			$data['name'] = $_POST['name'];
			$data['idContent'] = null;
			$data['backgroundColor'] = null;	
			$data['startHour'] = $_POST['startHour'];
			if (isset($_POST['selectImage'])) {
				$data['idContent'] = $_POST['selectImage'];	
				$data['backgroundImage'] = 1;
			} else {		
				$data['backgroundColor'] = $_POST['colorPicker'];
				$data['backgroundImage'] = 0;		
			}
			$this->menu->newMenu($data);
			$data['alert'] = true;
		}
		$data['customerImages'] = $this->content->getContentsByType($data['idCustomer'],TYPE_IMAGE);
		$breadcrumb = array("Nuevo Menú");
		$data['main_view'] = APPPATH.'views/menu/newMenu.php';			
		require_once(APPPATH."views/layout.php");
	}

	public function editMenu($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];	
		if (isset($_POST['name'])) {
			$data['id'] = $parameters['parameters'];
			$data['name'] = $_POST['name'];	 
			$data['idContent'] = null;
			$data['backgroundColor'] = null;	
			$data['startHour'] = $_POST['startHour'];
			if (isset($_POST['selectImage']) and $_POST['selectImage']<>0) {
				$data['idContent'] = $_POST['selectImage'];	
				$data['backgroundImage'] = 1;
			} else {		
				$data['backgroundColor'] = $_POST['colorPicker'];
				$data['backgroundImage'] = 0;		
			}
			$this->menu->editMenu($data);
			$data['alert'] = true;
		}
		$data_rx = $this->menu->getMenu($parameters['parameters']);
		$data['customerImages'] = $this->content->getContentsByType($data['idCustomer'],TYPE_IMAGE);
		$breadcrumb = array("Menús","Editar Menú");
		$data['main_view'] = APPPATH.'views/menu/editMenu.php';			
		require_once(APPPATH."views/layout.php");	
	}

	public function newMenuItem($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset($_POST['name'])) {
			$tx_data['idCustomer'] = $data['idCustomer'];
			$tx_data['title'] = $_POST['title'];		
			$tx_data['name'] = $_POST['name'];
			$tx_data['description'] = $_POST['description'];
			$tx_data['price'] = $_POST['price'];
			$tx_data['idContent'] = $_POST['selectImage'];
			$this->menu->newMenuItem($tx_data);
			$data['alert'] = true;
		}
		$data['customerImages'] = $this->content->getContentsByType($data['idCustomer'],TYPE_IMAGE);
		$breadcrumb = array("Menús","Nuevo Menú Item");
		$data['main_view'] = APPPATH.'views/menu/newMenuItem.php';				
		require_once(APPPATH."views/layout.php");
	}

	public function editMenuItem($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset($_POST['name'])) {
			$tx_data['id'] = $parameters['parameters'];
			$tx_data['idCustomer'] = $data['idCustomer'];
			$tx_data['title'] = $_POST['title'];		
			$tx_data['name'] = $_POST['name'];
			$tx_data['description'] = $_POST['description'];
			$tx_data['price'] = $_POST['price'];
			$tx_data['idContent'] = $_POST['selectImage'];
			$this->menu->editMenuItem($tx_data);
			$data['alert'] = true;
		}
		$data_rx = $this->menu->getMenuItem($parameters['parameters']);
		$data['customerImages'] = $this->content->getContentsByType($data['idCustomer'],TYPE_IMAGE);
		$breadcrumb = array("Menús","Editar Menú Item");
		$data['main_view'] = APPPATH.'views/menu/editMenuItem.php';			
		require_once(APPPATH."views/layout.php");
	}

	public function deleteMenu($parameters){
		$this->menu->deleteMenu($_POST['id']);
	}

	public function viewMenuDisplay($parameters){
		if (isset($_POST['display']) and !empty($_POST['display'])) {
			$data['displays'] = $_POST['display'];	
			$data['idMenu'] = $_POST['menu'];
			$data['idCustomer'] = $_SESSION['idCustomer'];				
			$this->menu->addMenuDisplay($data);		
		}	
		$breadcrumb = array("Asignar Menú"); 	
		$select_content['displays'] = $this->display->getDisplaysByCustomer($_SESSION['idCustomer']);	
		$select_content['menus'] = $this->menu->getMenusByCustomer($_SESSION['idCustomer']);
		$data['contents'] = $this->menu->getMenuDisplay($_SESSION['idCustomer']);
		$data['main_view'] = APPPATH.'views/menu/viewMenuDisplay.php';
		require_once(APPPATH."views/layout.php");
	}	

	public function removeMenuDisplay($parameters){
		$this->menu->removeMenuDisplay($_POST['id']);		
	}
	
	public function editMenuDisplay($parameters){
		$this->menu->editMenuDisplay($_POST['idMenu'],$_POST['idDisplay'],$_POST['id']);
	}

}