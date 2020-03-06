<?php

class Model_Trivia extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function newTrivia($data){
		$stmt = $this->db->prepare("insert into Trivia (idCustomer, name, createTime, updateTime) values (?,?,now(),now())");
		$stmt->execute(array($data['idCustomer'],$data['name']));	
		return $this->db->lastInsertId();
	}	

	function editTrivia($data){ 
		$stmt = $this->db->prepare("update Trivia set name = ?, updateTime = now() where id = ?");
		$stmt->execute(array($data['name'],$data['id']));
	}

	function deleteTrivia($id){
		$stmt = $this->db->prepare("update Trivia set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update TriviaContent set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
	}	

	function getTrivia($id){
		$row = "";		
		$stmt = $this->db->prepare("select name from Trivia where id = ?");
		$stmt->execute(array($id));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row = array('id'=>$id,'name'=>$rowData['name']);
		return $row;
	}	

	function getTriviasByCustomer($idCustomer){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select id, name from Trivia where idCustomer = ? and deleted = 0 order by name");
		$stmt->execute(array($idCustomer));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {		
			$rows[] = array('id'=>$rowData['id'],'name'=>$rowData['name']);		
		}
		return $rows;
	}

	function getTriviaContent($id){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select TriviaContent.id as id, TriviaQuestion.question as question, TriviaContent.order as orden, TriviaContent.disabled as disabled, TriviaContent.idTrivia as idTrivia from TriviaContent inner join TriviaQuestion on TriviaQuestion.id  = TriviaContent.idTriviaQuestion and TriviaQuestion.deleted = 0 where TriviaContent.idTrivia = ? and TriviaContent.deleted = 0 order by orden");
		$stmt->execute(array($id));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {
			$rows[] = array('id'=>$rowData['id'],'name'=>$rowData['question'],'order'=>$rowData['orden'],'disabled'=>$rowData['disabled'],'idTrivia'=>$rowData['idTrivia']);
		}
		return $rows;
	}

	function addTriviaItem($data){   
		$stmt = $this->db->prepare("insert into TriviaQuestion (idCustomer, question, createTime, updateTime) values (?,?,now(),now())");
		$stmt->execute(array($data['idCustomer'],$data['name']));			
		return $this->db->lastInsertId();
	}

	function deleteTriviaItem($id){
		$stmt = $this->db->prepare("update TriviaQuestion set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update TriviaAnswer set deleted = 1, updateTime = now() where idTriviaQuestion = ?");
		$stmt->execute(array($id));
	}

	function editTriviaItem($data){ 
		$stmt = $this->db->prepare("update TriviaQuestion set question = ?, updateTime = now() where id = ?");
		$stmt->execute(array($data['name'],$data['id']));
	}	

	function getTriviaQuestions($id){
		//$rows = "";		
		$rows = array();
		$stmt = $this->db->prepare("select question from TriviaQuestion where id = ? and deleted = 0");
		$stmt->execute(array($id));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$rows = array('id'=>$id,'name'=>$rowData['question']);
		return $rows;
	}

	function getTriviaQuestionsByCustomer($idCustomer){
		//$rows = "";		
		$rows = array();
		$stmt = $this->db->prepare("select TriviaQuestion.id as id, TriviaQuestion.question as name from TriviaQuestion where TriviaQuestion.idCustomer = ? and TriviaQuestion.deleted = 0");
		$stmt->execute(array($idCustomer));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {		
			$rows[] = array('id'=>$rowData['id'],'name'=>$rowData['name']);
		}
		return $rows;
	}

	function getQuestionsNotInTriviaByCustomer($idCustomer,$idTrivia){
		//$rows = "";		
		$rows = array();
		$stmt = $this->db->prepare("select id, question from TriviaQuestion where idCustomer = ? and deleted=0 and id not in (select idTriviaQuestion from TriviaContent where idTrivia = ? and deleted = 0)");
		$stmt->execute(array($idCustomer,$idTrivia));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {			
			$rows[] = array('id'=>$rowData['id'],'name'=>$rowData['question']);
		}		
		return $rows;
	}	

	function addTriviaContent($data){   		
		$stmt = $this->db->prepare("select max(TriviaContent.order) as orden from TriviaContent where idTrivia = ? and deleted = 0");
		$stmt->execute(array($data['idTrivia']));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 		
		$orden = $rowData['orden'] + 1;
		$stmt = $this->db->prepare("insert into TriviaContent (idTrivia, idTriviaQuestion, TriviaContent.order, createTime, updateTime) values (?,?,?,now(),now())");			
		$stmt->execute(array($data['idTrivia'],$data['idTriviaItem'],$orden));
		return $this->db->lastInsertId();
	}

	function deleteTriviaContent($id, $orden, $idTrivia){
		// Elimina el elemento
		$stmt = $this->db->prepare("update TriviaContent set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		// Actualiza el orden de los elementos restantes
		$stmt = $this->db->prepare("select id from TriviaContent where TriviaContent.order > ? and TriviaContent.idTrivia = ? and TriviaContent.deleted = 0 order by TriviaContent.order");
		$stmt->execute(array($orden,$idTrivia));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {
			$sql = $this->db->prepare("update TriviaContent SET TriviaContent.order = TriviaContent.order - 1, updateTime = now() where id = ?");
			$sql->execute(array($rowData['id']));						
		}
	}	

	function getAnswers($id_question){
		//$rows = "";		
		$rows = array();
		$stmt = $this->db->prepare("select id, answer, correct from TriviaAnswer where idTriviaQuestion = ? and deleted = 0");
		$stmt->execute(array($id_question));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {
			$rows[] = array('id'=>$rowData['id'],'answer'=>$rowData['answer'],'correct'=>$rowData['correct']);
		}
		return $rows;
	}	

	function getAnswer($id){
		$row = "";		
		$stmt = $this->db->prepare("select answer, correct, idTriviaQuestion from TriviaAnswer where id = ?");
		$stmt->execute(array($id));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row = array('id'=>$id,'answer'=>$rowData['answer'],'correct'=>$rowData['correct'],'idTriviaQuestion'=>$rowData['idTriviaQuestion']);
		return $row;
	}	

	function editAnswer($data){ 
		$stmt = $this->db->prepare("update TriviaAnswer set answer = ?, correct = ?, updateTime = now() where id = ?");
		$stmt->execute(array($data['answer'],$data['correct'],$data['id']));
	}		

	function deleteAnswer($id){
		$stmt = $this->db->prepare("update TriviaAnswer set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
	}	

	function addAnswer($data){
		$stmt = $this->db->prepare("insert into TriviaAnswer (idTriviaQuestion, answer, correct, createTime, updateTime) values (?,?,?,now(),now())");
		$stmt->execute(array($data['idTriviaQuestion'],$data['answer'],$data['correct']));	
		return $this->db->lastInsertId();
	}

	function getTriviaDisplays($data){
		$rows="";
		$stmt = $this->db->prepare("select TriviaDisplay.id, Trivia.id as idTrivia, Display.id as idDisplay, Trivia.name as triviaName, Display.name as displayName from TriviaDisplay inner join Display on Display.id = TriviaDisplay.idDisplay and Display.deleted = 0 inner join Trivia on Trivia.id = TriviaDisplay.idTrivia and Trivia.deleted = 0 where TriviaDisplay.idCustomer = ? and TriviaDisplay.deleted = 0");
		$stmt->execute(array($data['idCustomer']));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] =array('id'=>$row['id'], 'idTrivia'=>$row['idTrivia'], 'idDisplay'=>$row['idDisplay'], 'nameTrivia'=>$row['triviaName'], 'nameDisplay'=>$row['displayName']);
		}
		return $rows;
	}	

	function addDisplay($data){   
		foreach ($data['displays'] as $display) {
			$stmt = $this->db->prepare("insert into TriviaDisplay (idTrivia, idDisplay, idCustomer, createTime, updateTime) values (?,?,?,now(),now())");
			$stmt->execute(array($data['idTrivia'],$display,$data['idCustomer']));	
		}
		return $this->db->lastInsertId();
	}

	function removeTriviaDisplay($id){
		$stmt = $this->db->prepare("update TriviaDisplay set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
	}	

	function editTriviaDisplay($idTrivia,$idDisplay,$id){
		$stmt = $this->db->prepare("update TriviaDisplay set idTrivia = ?, idDisplay = ? where id = ?");
		$stmt->execute(array($idTrivia,$idDisplay,$id));
	}	

	function editTriviaContentOrder($order,$id){
		$stmt = $this->db->prepare("update TriviaContent set TriviaContent.order = ?, updateTime = now() where id = ?");
		$stmt->execute(array($order,$id));	
	}

}