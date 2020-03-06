<?php 

class display extends Controller_Abstract{

	public function __construct(){
		parent::__construct();	
		$this->display = new Model_Display;	
		$this->user = new Model_User;
		$this->customer = new Model_Customer;	
		$this->schedule = new Model_Schedule;
		$this->calendar = new Model_Calendar;
		$this->wall = new Model_Wall;
	}

	public function index(){
		$store_admin = true;
		$idStore = $this->user->getStoreByUser($_SESSION['idUser']);
		$data['contents'] = $this->display->getDisplaysByStore($idStore);
		$dataTemplate['allScheduleTemplate'] = $this->schedule->getSchedulesByCustomer($_SESSION['idCustomer']);
		$breadcrumb = array("Displays"); 			
		$data['main_view'] = APPPATH. 'views/display/viewDisplays.php';
		require_once(APPPATH. "views/layout.php");
	}
	
	public function newDisplay($parameters){
		if (isset($_POST['name'])) {
			$data['vertical'] = "false";
			if (isset($_POST['vertical']))
				$data['vertical'] = $_POST['vertical'];
			if (isset($_POST['checkTime']))
				$data['checkTime'] = $_POST['checkTime'];
			if (isset($_POST['selectTemplate']))
				$data['idTemplate'] = $_POST['selectTemplate'];
			if (isset($_POST['reinicio'])) {
				$data['reinicio'] = $_POST['reinicio'];
				$data['selectDays'] = $_POST['selectDays'];
				$data['rebootTime'] = $_POST['rebootTime'];
			}		
			$data['name'] = $_POST['name'];
			$data['idStore'] = $parameters['parameters'];
			$this->display->newDisplay($data);
			$data['alert'] = true;
		}
		$customer_data = $this->customer->getCustomerByStore($parameters['parameters']);
		$data['idCustomer'] = $customer_data['idCustomer'];		
		$data['customerName'] = $customer_data['customerName'];
		$data['zoneId'] = $customer_data['zoneId'];
		$data['zoneName'] = $customer_data['zoneName'];	
		$data['storeName'] = $customer_data['storeName'];
		$dataTemplate['allScheduleTemplate'] = $this->schedule->getSchedulesByCustomer($data['idCustomer']);
		$breadcrumb = array($data['customerName'],$data['zoneName'],$data['storeName'],"Nuevo Display");
		$data['main_view'] = APPPATH.'views/display/newDisplay.php';
		require_once (APPPATH."views/layout.php");	
	}
	
	public function viewDisplay($parameters){
		$idDisplay = $parameters['parameters'];
		$data['display'] = $this->display->getDisplay($idDisplay);
		$customer_data = $this->customer->getCustomerByStore($data['display']['idStore']);
		$ipconnection = $this->display->getIpAndPort($idDisplay);
	    if ($ipconnection<>"")
			$connection = $ipconnection['ip'] . ":" .$ipconnection['port'];
		else
			$connection = "-";
		$data['idCustomer'] = $customer_data['idCustomer'];		
		$data['customerName'] = $customer_data['customerName'];
		$data['zoneId'] = $customer_data['zoneId'];
		$data['zoneName'] = $customer_data['zoneName'];	
		$data['storeName'] = $customer_data['storeName'];
		$dataTemplate['allScheduleTemplate'] = $this->schedule->getSchedulesByCustomer($data['idCustomer']);
		$templateDisplay = $this->display->getCurrentTemplate($idDisplay, date("m"), date("d"), date("w"));	
		$data['nowPlaying'] = $this->display->getNowPlaying($idDisplay);
		if (!isset($data['nowPlaying']))
			$data['nowPlaying'] = "-";
		$data['contents'] = "";
		if (!empty($templateDisplay['id']))
			$data['contents'] = $this->schedule->getBlocksBySchedule($templateDisplay['id']);
		$data['wall'] = "";
		if (($_SESSION['idRole'] == ROLE_ADMIN) || ($_SESSION['idRole'] == ROLE_CUSTOMER_ADMIN)) {
			if ($data['display']['videoWall'] == 1)
				$data['wall'] = $this->wall->getWallDataByMaster($data['display']['idURP']);
		}
		$breadcrumb = array($data['customerName'],$data['zoneName'],$data['storeName'],$data['display']['name']);
		$data['main_view'] = APPPATH.'views/display/viewDisplay.php';
		require_once(APPPATH."views/layout.php");	
	}
	
	public function viewDisplayContent($parameters){
		$idDisplay = $parameters['parameters'];
		$data['display'] = $this->display->getDisplay($idDisplay);
		$currentTemplate = $this->display->getCurrentTemplate($data['display']['id'], date("m"), date("d"), date("w"));	
		$customer_data = $this->customer->getCustomerByStore($data['display']['idStore']);
		$data['idCustomer'] = $customer_data['idCustomer'];		
		$data['customerName'] = $customer_data['customerName'];
		$data['zoneId'] = $customer_data['zoneId'];
		$data['zoneName'] = $customer_data['zoneName'];	
		$data['storeName'] = $customer_data['storeName'];
		$data['contents'] = "";
		if (!empty($currentTemplate['id']))
			$data['contents'] = $this->schedule->getFullSchedule($currentTemplate['id']);
		$breadcrumb = array($data['customerName'],$data['zoneName'],$data['storeName'],$data['display']['name'],"Agenda completa");
		$data['main_view'] = APPPATH.'views/display/viewDisplayCompleteSchedule.php';
		require_once(APPPATH."views/layout.php");	
	}

	public function viewDisplays($parameters){
		$store_admin = false;
		if ($_SESSION['idRole']==ROLE_STORE_ADMIN)
			$store_admin = true;
		$idStore = $parameters['parameters'];
		$data['contents'] = $this->display->getDisplaysByStore($idStore);
		$customer_data = $this->customer->getCustomerByStore($idStore);
		$data['idCustomer'] = $customer_data['idCustomer'];		
		$data['customerName'] = $customer_data['customerName'];
		$data['zoneId'] = $customer_data['zoneId'];
		$data['zoneName'] = $customer_data['zoneName'];	
		$data['storeName'] = $customer_data['storeName'];
		$dataTemplate['allScheduleTemplate'] = $this->schedule->getSchedulesByCustomer($data['idCustomer']);
		if (!$store_admin)
			$breadcrumb = array($data['customerName'],$data['zoneName'],$data['storeName'],"Displays");
		else
			$breadcrumb = array("Displays");
		$data['main_view'] = APPPATH.'views/display/viewDisplays.php';
		require_once(APPPATH."views/layout.php");
	}

	public function editDisplayDefaultTemplate($parameters){
		if ($_POST['idTemplate']<>0 && $_POST['idDisplay']<>0)
			$this->display->editTemplateDefault($_POST['idTemplate'],$_POST['idDisplay']);
	}
	
	public function deleteDisplay($parameters){
		$this->calendar->deleteCalendarByDisplay($_POST['id']);
		$this->display->deleteScheduleWeekByDisplay($_POST['id']);
		$this->display->deleteDisplay($_POST['id']);
	}
	
	public function editDisplay($parameters){
		if (isset($_POST['name'])) {	
			$displayData = new stdclass;
			$displayData->id = $_POST['id'];
			$displayData->name = $_POST['name'];
			$displayData->vertical = $_POST['vertical'];		
			$displayData->reinicio = $_POST['reinicio'];
			$displayData->selectDays = $_POST['selectDays'];
			$displayData->rebootTime = $_POST['rebootTime'];
			$displayData->checkTime = $_POST['checkTime'];
			$templateData['idDisplay'] = $_POST['id'];
			$templateData['idTemplate'] = $_POST['selectTemplate'];
			$this->display->editTemplateDefault($templateData['idTemplate'],$displayData->id);					
			$result = $this->display->editDisplay($displayData);
			exit();
		}
		$data['displays'] = $this->display->getDisplay($parameters['parameters']);
		$rebootTime = str_pad($data['displays']['rebootHour'],2,"0", STR_PAD_LEFT) .":". str_pad($data['displays']['rebootMinute'],2,"0", STR_PAD_LEFT);
		$store = $this->display->getStoreByDisplay($parameters['parameters']);
		$data['storeId'] = $store['id'];
		$data['idCustomer'] = $store['idCustomer'];		
		$data['customerName'] = $store['customerName'];
		$data['zoneId'] = $store['idZone'];
		$data['zoneName'] = $store['zoneName'];	
		$data['storeName'] = $store['name'];
		$rowTemplateName = $this->display->getDefaultTemplate($data['displays']['id']);			
		$templateDefault = $rowTemplateName['name'];	
		$idTemplateDefault = $rowTemplateName['id'];
		$dataTemplate['allScheduleTemplate'] = $this->schedule->getSchedulesByCustomer($data['idCustomer']);
		$breadcrumb = array($data['customerName'],$data['zoneName'],$data['storeName'],$data['displays']['name'],"Editar Display");
		$data['main_view'] = APPPATH.'views/display/editDisplay.php';
		require_once(APPPATH."views/layout.php");
	}
	
	public function viewRemoteActions($parameters){
		$idDisplay = $parameters['parameters'];
		$data['display'] = $this->display->getDisplay($idDisplay);	
		$customer_data = $this->customer->getCustomerByStore($data['display']['idStore']);	
		$data['idCustomer'] = $customer_data['idCustomer'];		
		$data['customerName'] = $customer_data['customerName'];
		$data['zoneId'] = $customer_data['zoneId'];
		$data['zoneName'] = $customer_data['zoneName'];	
		$data['storeName'] = $customer_data['storeName'];
		$breadcrumb = array($data['customerName'],$data['zoneName'],$data['storeName'],$data['display']['name'],"Acciones remotas"); 			
		$data['main_view'] = APPPATH.'views/display/viewRemoteActions.php';
		require_once (APPPATH."views/layout.php"); 
	}

	public function remoteRestart($parameters){		
		$this->display->remoteRestart($parameters['parameters']);
	}

	public function remoteBackground($parameters){		
		$this->display->remoteBackground($parameters['parameters']);
	}
	
	public function viewBlockContents($parameters){	
		$idBlock  = $parameters['parameters'];
		$idDisplay = $parameters['parameters2'];
		$data['display'] = $this->display->getDisplay($idDisplay);						
		$customer_data = $this->customer->getCustomerByStore($data['display']['idStore']);
		$data['idCustomer'] = $customer_data['idCustomer'];		
		$data['customerName'] = $customer_data['customerName'];
		$data['zoneId'] = $customer_data['zoneId'];
		$data['zoneName'] = $customer_data['zoneName'];	
		$data['storeName'] = $customer_data['storeName'];	
		$data['blockName'] = $this->schedule->getBlockName($idBlock);		
		$data['contents'] = $this->schedule->getBlockItemsByBlock($idBlock);
		$breadcrumb = array($data['customerName'],$data['zoneName'],$data['storeName'],$data['display']['name'],$data['blockName']);
		$data['main_view'] = APPPATH.'views/display/viewDisplayBlocks.php';
		require_once(APPPATH."views/layout.php");
	}

	public function viewAgendaSemanal($parameters){	
		$idDisplay = $parameters['parameters'];
		$data['display'] = $this->display->getDisplay($idDisplay);	
		$customer_data = $this->customer->getCustomerByStore($data['display']['idStore']);
		$data['idCustomer'] = $customer_data['idCustomer'];
		$agendaSemanal = $this->display->getAgendaSemanal($idDisplay);
		$dataTemplate['allScheduleTemplate'] = $this->schedule->getSchedulesByCustomer($data['idCustomer']);
		$arrDisplays = json_encode($this->display->getDisplaysWithScheduleWeek($data['idCustomer']));
		$data['customerName'] = $customer_data['customerName'];
		$data['zoneId'] = $customer_data['zoneId'];
		$data['zoneName'] = $customer_data['zoneName'];	
		$data['storeName'] = $customer_data['storeName'];
		$breadcrumb = array($data['customerName'],$data['zoneName'],$data['storeName'],$data['display']['name'],"Agenda Semanal");
		$data['main_view'] = APPPATH.'views/display/viewAgendaSemanal.php';
		require_once(APPPATH."views/layout.php");
	}	
		
	public function editAgendaSemanal($parameters){
		$this->display->editAgendaSemanal($_POST);
	}	

	public function deleteAgendaSemanal($parameters){
		$this->display->deleteAgendaSemanal($_POST);
	}

	public function emptyScheduleWeek($parameters){
		$this->display->deleteScheduleWeekByDisplay($_POST['id']);
	}

	public function copyScheduleWeek($parameters){
		$this->display->deleteScheduleWeekByDisplay($_POST['selectedDisplay']);
		$week_copy = $this->display->getAgendaSemanal($_POST['copyDisplay']);
		foreach ($week_copy as $week) {
			if ($week['idScheduleTemplate']!=NULL){
				$data['idDisplay'] = $_POST['selectedDisplay'];
				$data['idTemplate'] = $week['idScheduleTemplate'];	
				$data['idWeekday'] = $week['idWeekday'];
				$this->display->editAgendaSemanal($data);
			}
		}
	}	

}