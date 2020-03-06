<?php

class Model_Audit extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function getAuditLogin(){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select AuditLogin.email, Status.name, AuditLogin.date from AuditLogin inner join Status on Status.id = AuditLogin.idStatus order by AuditLogin.date");
		$stmt->execute();
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('email'=>$row['email'],'status'=>$row['name'],'date'=>$row['date']);
		}
		return $rows;
	}

	function getAuditContent(){
		$rows = array();
		$stmt = $this->db->prepare("select Customer.name as customer, User.email as user, Status.name as status, ContentType.name as contentType, Content.name as content, AuditContent.date from AuditContent inner join User on User.id = AuditContent.idUser inner join UserCustomer on UserCustomer.idUser = AuditContent.idUser inner join Customer on Customer.id = UserCustomer.idCustomer inner join Status on Status.id = AuditContent.idStatus inner join Content on Content.id = AuditContent.idContent inner join ContentType on ContentType.id = Content.idContentType order by AuditContent.date");
		$stmt->execute();
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('customer'=>$row['customer'],'user'=>$row['user'],'status'=>$row['status'],'contentType'=>$row['contentType'],'content'=>$row['content'],'date'=>$row['date']);
		}
		return $rows;
	}

	function getAuditContentByCustomer($idCustomer){
		$rows = array();
		$stmt = $this->db->prepare("select Customer.name as customer, User.email as user, Status.name as status, ContentType.name as contentType, Content.name as content, AuditContent.date from Customer inner join UserCustomer on UserCustomer.idCustomer = Customer.id inner join User on User.id = UserCustomer.idUser inner join AuditContent on AuditContent.idUser = User.id inner join Status on Status.id = AuditContent.idStatus inner join Content on Content.id = AuditContent.idContent inner join ContentType on ContentType.id = Content.idContentType where Customer.id = ? order by AuditContent.date");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('customer'=>$row['customer'],'user'=>$row['user'],'status'=>$row['status'],'contentType'=>$row['contentType'],'content'=>$row['content'],'date'=>$row['date']);
		}
		return $rows;
	}

}