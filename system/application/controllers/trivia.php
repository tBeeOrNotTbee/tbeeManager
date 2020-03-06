<?php 

class trivia extends Controller_Abstract{ 

	public function __construct(){
		parent::__construct();	
		$this->trivia = new Model_Trivia;
		$this->display = new Model_Display;
	}

	public function index($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];		
		$data['contents'] = $this->trivia->getTriviasByCustomer($data['idCustomer']);
		$breadcrumb = array("Trivias"); 	
		$data['main_view'] = APPPATH.'views/trivia/viewTrivias.php';
		require_once(APPPATH."views/layout.php");	
	}

	public function deleteTrivia($parameters){	
		$this->trivia->deleteTrivia($_POST['id']);
	}
	
	public function newTrivia($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset( $_POST['name'])) {
			$data['name'] = $_POST['name'];	
			$this->trivia->newTrivia($data);
			$data['alert'] = true;	
		}
		$breadcrumb = array("Trivias","Nueva Trivia"); 	
		$data['main_view'] = APPPATH.'views/trivia/newTrivia.php';			
		require_once(APPPATH."views/layout.php");	
	}
	
	public function editTrivia($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset($_POST['name'])) {
			$data['id'] = $parameters['parameters'];
			$data['name'] = $_POST['name'];
			$this->trivia->editTrivia($data);
			$data['alert'] = true;
		}
		$data_rx = $this->trivia->getTrivia($parameters['parameters']);
		$breadcrumb = array("Trivias","Editar Trivia");
		$data['main_view'] = APPPATH.'views/trivia/editTrivia.php';			
		require_once(APPPATH."views/layout.php");
	}
	
	public function viewTriviaItems($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];				
		$data['contents'] = $this->trivia->getTriviaQuestionsByCustomer($data['idCustomer']);
		$breadcrumb = array("Trivias","Preguntas");
		$data['main_view'] = APPPATH.'views/trivia/viewTriviaItems.php';
		require_once(APPPATH."views/layout.php");
	}	
	
	public function viewAnswers($parameters){
		$data['contents'] = $this->trivia->getAnswers($parameters['parameters']);
		$breadcrumb = array("Trivias","Preguntas","Respuestas"); 	
		$data['main_view'] = APPPATH.'views/trivia/viewAnswers.php';
		require_once(APPPATH."views/layout.php");
	}
	
	public function newAnswer($parameters){
		$data['idTriviaQuestion'] = $parameters['parameters'];
		if (isset( $_POST['answer'])) {				
			$data['answer'] = $_POST['answer'];
			if (isset($_POST['correct']))
				$data['correct'] = 1;
			else
				$data['correct'] = 0;
			$this->trivia->addAnswer($data);
			$data['alert'] = true;	
		}
		$breadcrumb = array("Trivias","Preguntas","Respuestas","Nueva Respuesta"); 	
		$data['main_view'] = APPPATH.'views/trivia/newAnswer.php';
		require_once(APPPATH."views/layout.php");
	}

	public function deleteAnswer($parameters){
		$this->trivia->deleteAnswer($_POST['id']);
	}
	
	public function editAnswer($parameters){
		if (isset($_POST['answer'])) {
			$data['id'] = $parameters['parameters'];
			$data['answer'] = $_POST['answer'];
			if (isset($_POST['correct']))
				$data['correct'] = 1;
			else
				$data['correct'] = 0;
			$this->trivia->editAnswer($data);
			$data['alert'] = true;
		}
		$data_rx = $this->trivia->getAnswer($parameters['parameters']);
		$breadcrumb = array("Trivias","Preguntas","Respuestas","Editar Respuesta");
		$data['main_view'] = APPPATH.'views/trivia/editAnswer.php';			
		require_once(APPPATH."views/layout.php");
	}		
	
	public function viewTriviaContent($parameters){
		$_POST['idTriviaItem'] = "";
		$data['idCustomer'] = $_SESSION['idCustomer'];				
		$data['contents'] = $this->trivia->getTriviaContent($parameters['parameters']);
		$data['data'] = $this->trivia->getTrivia($parameters['parameters']);				
		$data['listitems'] = $this->trivia->getQuestionsNotInTriviaByCustomer($data['idCustomer'],$parameters['parameters']);
		$breadcrumb = array("Trivias",$data['data']['name'],"Trivia Content");
		$data['main_view'] = APPPATH.'views/trivia/viewTriviaContent.php';
		require_once(APPPATH."views/layout.php");
	}

	public function newTriviaContent($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset( $_POST['idTriviaItem'])) {
			$data['idTriviaItem'] = $_POST['idTriviaItem'];	
			$data['idTrivia'] = $parameters['parameters'];	
			$this->trivia->addTriviaContent($data);	
		}
		$_POST['idTriviaItem'] = "";	
		$this->viewTriviaContent($parameters);
	}	
	
	public function newTriviaItem($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset( $_POST['name'])) {
			$data['name'] = $_POST['name'];
			$this->trivia->addTriviaItem($data);
			$data['alert'] = true;	
		}
		$breadcrumb = array("Trivias","Preguntas","Nueva Pregunta"); 
		$data['main_view'] = APPPATH.'views/trivia/newTriviaItem.php';
		require_once(APPPATH."views/layout.php");	
	}	
	
	public function editTriviaItem($parameters){
		$data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset($_POST['name'])) {
			$data['id'] = $parameters['parameters'];
			$data['name'] = $_POST['name'];
			$this->trivia->editTriviaItem($data);
			$data['alert'] = true;
		}
		$data_rx = $this->trivia->getTriviaQuestions($parameters['parameters']);
		$breadcrumb = array("Trivias","Preguntas","Editar Pregunta");
		$data['main_view'] = APPPATH.'views/trivia/editTriviaItem.php';			
		require_once(APPPATH."views/layout.php");
	}	

	public function deleteTriviaItem($parameters){
		$this->trivia->deleteTriviaItem($_POST['id']);
	}
	
	public function deleteTriviaContent($parameters){
		$this->trivia->deleteTriviaContent($_POST['id'],$_POST['orden'],$_POST['idTrivia']);
	}
	
	public function viewTriviaDisplay($parameters){
		if (isset($_POST['display']) and !empty($_POST['display'])) {	
			$data['displays'] = $_POST['display'];	
			$data['idTrivia'] = $_POST['trivia'];
			$data['idCustomer'] = $_SESSION['idCustomer'];			
			$this->trivia->addDisplay($data);		
		}	
		$breadcrumb = array("Asignar Trivia"); 	
		$select_content['displays'] = $this->display->getDisplaysByCustomer($_SESSION['idCustomer']);	
		$select_content['trivias'] = $this->trivia->getTriviasByCustomer($_SESSION['idCustomer']);
		$data['contents'] = $this->trivia->getTriviaDisplays($data);
		$data['main_view'] = APPPATH.'views/trivia/viewTriviaDisplay.php';
		require_once(APPPATH."views/layout.php");
	}	
	
	public function deleteDisplay($parameters){
		$this->trivia->removeTriviaDisplay($_POST['id']);		
	}
	
	public function editDisplay($parameters){	
		$this->trivia->editTriviaDisplay($_POST['idTrivia'],$_POST['idDisplay'],$_POST['id']);
	}	
	
	public function editTriviaContentOrder($parameters){
		$this->trivia->editTriviaContentOrder($_POST['order'],$_POST['id']);
	}

}