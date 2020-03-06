<?php

class schedule extends Controller_Abstract{

	public function __construct(){
		parent::__construct();
		$this->schedule = new Model_Schedule;
		$this->content = new Model_Content;
	}

	public function newScheduleBlock($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		//Vista de creación de bloque
		if (!isset($_POST['name']) && !isset($_POST['idContent'])) {
			$data['contents'] = $this->content->getContentsForBlocks($data['idCustomer']);
			$data['blockItems'] = "";		
			$data['idBlock'] = "";	
			$data['blockName'] = "";
			$breadcrumb = array("Agenda","Nuevo Bloque");	
			$data['main_view'] = APPPATH.'views/schedule/newScheduleBlock.php';
			require_once(APPPATH."views/layout.php");				
		} else {
			//Crea bloque y redirecciona a edición
			$args=array("idCustomer"=>$data['idCustomer'],"name"=>$_POST['name']);
			$idBlock = $this->schedule->newBlock($args);
			$parameters['parameters'] = $idBlock;
			$this->editScheduleBlockItems($parameters);				
		}	
	}

	public function newScheduleTemplate($parameters){
		if (isset($_POST['name'])) {
			$data['idCustomer'] = $_SESSION['idCustomer'];		
			$data['name'] = $_POST['name'];
			$parameters['parameters'] = $this->schedule->newSchedule($data);
			$this->viewScheduleContent($parameters);
		} else {
			$breadcrumb = array("Agenda","Nueva Agenda"); 						
			$data['main_view'] = APPPATH.'views/schedule/newScheduleTemplate.php';
			require_once(APPPATH."views/layout.php");	
		}
	}

	public function viewScheduleTemplate($parameters){
		$idCustomer = $_SESSION['idCustomer'];
		$data['template'] = $this->schedule->getSchedulesByCustomerWithDelete($idCustomer);
		$breadcrumb = array("Agendas");
		$data['main_view'] = APPPATH.'views/schedule/viewScheduleTemplate.php';
		require_once(APPPATH."views/layout.php");	
	}
	
	public function viewScheduleContent($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset($_POST['idScheduleBlock']) and (!isset($_POST['borrado']))) {
			$data['idScheduleBlock'] = $_POST['idScheduleBlock'];
			$data['idScheduleTemplate'] = $parameters['parameters'];
			$horario = $_POST['timepicker'];
			$horario_array = explode(":",$horario);
			$hora = $horario_array[0];
			$minutos = $horario_array[1];			
			$data['startHour'] = $hora;
			$data['startMinute'] = $minutos;
			$data['contents'] = $this->schedule->getBlockItemsByBlock($_POST['idScheduleBlock']);
			if (!empty($data['contents']))
				$this->schedule->addScheduleBlock($data);
		}
		$data['contents'] = $this->schedule->getBlocksBySchedule($parameters['parameters']);
		$data['templateName'] = $this->schedule->getScheduleName($parameters['parameters']);
		//para el cuadro desplegable
		$data['newcontents'] = $this->schedule->getBlocksByCustomer($data['idCustomer']);
		$arrSchedules = json_encode($this->schedule->getSchedulesByCustomer($data['idCustomer'])); 
		$arrBlocks = json_encode($this->schedule->getUniqueBlocksBySchedule($parameters['parameters']));
		$arrScheduleBlocks = json_encode($data['newcontents']);
		$breadcrumb = array("Agenda",$data['templateName'],"Bloques");
		$data['main_view'] = APPPATH.'views/schedule/viewScheduleContent.php';
		require_once(APPPATH."views/layout.php");
	}
	
	public function viewCustomerScheduleBlocks($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];		
		$data['contents'] = $this->schedule->getBlocksByCustomerWithDelete($data['idCustomer']);
		$breadcrumb = array("Agenda","Bloques");
		$data['main_view'] = APPPATH.'views/schedule/viewCustomerScheduleBlocks.php';
		require_once(APPPATH."views/layout.php");
	}
	
	public function viewBlockContent($parameters){
		$data['template'] = $parameters['parameters2'];
		$data['templateName'] = $this->schedule->getScheduleName($parameters['parameters2']);
		$data['contents'] = $this->schedule->getBlockItemsByBlock($parameters['parameters']);	
		$data['blockName'] = $this->schedule->getBlockName($parameters['parameters']);
		$breadcrumb = array("Agenda",$data['templateName'],"Bloques",$data['blockName']);
		$data['main_view'] = APPPATH.'views/schedule/viewBlockContent.php';
		require_once(APPPATH."views/layout.php");
	}

	public function viewScheduleBlockItems($parameters){
		$data['blockName'] = $this->schedule->getBlockName($parameters['parameters']);
		$data['blockItems'] = $this->schedule->getBlockItemsByBlock($parameters['parameters']);
		$breadcrumb = array("Agenda","Bloques",$data['blockName']);
		$data['main_view'] = APPPATH.'views/schedule/viewScheduleBlockItems.php';
		require_once(APPPATH."views/layout.php");		
	}

	public function viewFullScheduleContent($parameters){
		$data['contents'] = $this->schedule->getFullSchedule($parameters['parameters']);
		$data['templateName'] = $this->schedule->getScheduleName($parameters['parameters']);
		$data['idCustomer'] = $_SESSION['idCustomer'];
		$breadcrumb = array("Agenda",$data['templateName'],"Agenda completa");
		$data['main_view'] = APPPATH.'views/schedule/viewFullScheduleContent.php';
		require_once(APPPATH."views/layout.php");
	}	

	public function getBlockContents($parameters){
		echo json_encode($this->schedule->getBlockItemsByBlock($parameters['parameters']));
	}

	public function editScheduleBlockItemLength($parameters){
		$this->schedule->editScheduleBlockItemLength($_POST['length'],$_POST['id']);
	}

	public function editScheduleBlockItemOrder($parameters){
		$this->schedule->editScheduleBlockItemOrder($_POST['order'],$_POST['id']);
	}

	public function editScheduleBlockItems($parameters){
		$data['idBlock'] = $parameters['parameters'];		
		$data['blockName'] = $this->schedule->getBlockName($parameters['parameters']);
		$data['blockItems'] = $this->schedule->getBlockItemsByBlock($parameters['parameters']);
		$data['idCustomer'] = $_SESSION['idCustomer'];		
		$data['contents'] = $this->content->getContentsForBlocks($data['idCustomer']);
		if (isset($_POST['idContent'])) {
			$idBlock = $parameters['parameters'];
			$data['idBlock'] = $idBlock;	
			$data['idContent'] = $_POST['idContent'];	
			$data['duracion'] = $_POST['duracion'];
			$this->schedule->addBlockItem($data);
			// busca block e items creados
			$data['blockItems'] = $this->schedule->getBlockItemsByBlock($idBlock);	
			$data['blockName'] = $this->schedule->getBlockName($idBlock);					
		}
		$breadcrumb = array("Agenda","Bloques","Editar Bloque",$data['blockName']);	
		$data['main_view'] = APPPATH.'views/schedule/editScheduleBlockItems.php';
		require_once(APPPATH."views/layout.php");		
	}

	public function editScheduleTemplate($parameters){
		if (isset($_POST['name'])) {
			$this->schedule->editScheduleName($_POST['id'],$_POST['name']);		
			exit();
		}
		$idScheduleTemplate = $parameters['parameters'];
		$data['id'] = $idScheduleTemplate;		
		$data['name'] = $this->schedule->getScheduleName($idScheduleTemplate);
		$breadcrumb = array("Agenda","Editar Agenda");
		$data['main_view'] = APPPATH.'views/schedule/editScheduleTemplate.php';
		require_once(APPPATH."views/layout.php");
	}

	public function editScheduleBlock($parameters){
		$idBlock = $parameters['parameters'];
		if (isset($_POST['name'])) {	
			$this->schedule->editBlockName($idBlock, $_POST['name']);
			$data['alert'] = true;
		}
		$breadcrumb = array("Agenda","Bloques","Editar Bloque");
		$data['blockName'] = $this->schedule->getBlockName($idBlock);		
		$data['main_view'] = APPPATH.'views/schedule/editScheduleBlock.php';
		require_once(APPPATH."views/layout.php");
	}

	public function deleteScheduleTemplate($parameters){
		$this->schedule->deleteSchedule($_POST['id']);
	}

	public function deleteBlockItem($parameters){
		$id = $_POST['id'];	
		$orden = $_POST['orden'];
		$idScheduleBlock = $_POST['idScheduleBlock'];	
		$this->schedule->deleteBlockItem($id, $orden, $idScheduleBlock);
	}

	public function deleteBlock($parameters){
		$this->schedule->deleteBlock($_POST['id']);
	}

	public function removeBlockFromSchedule($parameters){
		$this->schedule->removeBlockFromSchedule($_POST['id']);
	}

	public function removeScheduleBlocks($parameters){
		$this->schedule->removeScheduleBlocks($_POST['id']);
	}

	public function copySchedule($parameters){
		$this->schedule->removeScheduleBlocks($_POST['selectedSchedule']);
		$schedule_copy = $this->schedule->getBlocksBySchedule($_POST['copySchedule']);
		foreach ($schedule_copy as $schedule) {
			$schedule_data['idScheduleTemplate'] = $_POST['selectedSchedule'];
			$schedule_data['idScheduleBlock'] = $schedule['id'];	
			$schedule_data['startHour'] = $schedule['startHour'];
			$schedule_data['startMinute'] = $schedule['startMinute'];	
			$this->schedule->addScheduleBlock($schedule_data);
		}
	}

	public function replaceScheduleBlock($parameters){
		$this->schedule->replaceScheduleBlock($_POST['selectedSchedule'],$_POST['idBlock'],$_POST['idNewBlock']);
	}
	
}