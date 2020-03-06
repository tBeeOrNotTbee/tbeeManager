<?php

class Model_Customer extends Model_Abstract {     

	function __construct(){
		$this->db = $this->getConnection();
	}

	function newCustomer($data){
		$stmt = $this->db->prepare("insert into Customer (name, createTime, updateTime) values (?,now(),now())");
		$stmt->execute(array($data['name']));	
		return $this->db->lastInsertId();
	}

	function editCustomer($id,$name){
		$stmt = $this->db->prepare("update Customer set name = ?, updateTime = now() where id = ?");
		$stmt->execute(array($name,$id));
	}

	function deleteCustomer($id){
		$stmt = $this->db->prepare("update Customer set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
	}

	function getCustomers(){
		$rows = array();
		//$rows = "";
		$stmt = $this->db->prepare("select id, name from Customer where deleted = 0 order by name");
		$stmt->execute();
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('id'=>$row['id'],'name'=>$row['name']);
		}
		return $rows;
	}

	function getCustomer($id){
		$stmt = $this->db->prepare("select name from Customer where id = ?");
		$stmt->execute(array($id));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row = array('id'=>$id,'name'=>$rowData['name']);
		return $row;
	}

	function getCustomerByStore($id){
		$stmt = $this->db->prepare("select Store.name, Zone.id as idZone, Zone.name as zoneName, Customer.id as idCustomer, Customer.name as customerName from Store inner join Zone on Zone.id = Store.idZone inner join Customer on Customer.id = Zone.idCustomer where Store.id = ?");
		$stmt->execute(array($id));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC);
		$row = array("storeName"=>$rowData['name'],"zoneId"=>$rowData['idZone'],"zoneName"=>$rowData['zoneName'],"idCustomer"=>$rowData['idCustomer'],"customerName"=>$rowData['customerName']);
		return $row;
	}

	function getCustomerByZone($id){
		$stmt = $this->db->prepare("select Zone.name, Customer.id as idCustomer, Customer.name as customerName from Zone inner join Customer on Customer.id = Zone.idCustomer where Zone.id = ?");
		$stmt->execute(array($id));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row = array("zoneName"=>$rowData['name'],"idCustomer"=>$rowData['idCustomer'],"customerName"=>$rowData['customerName']);
		return $row;
	}

	function getCustomerByURP($id){
		$stmt = $this->db->prepare("select Customer.id, Customer.name from URP inner join Customer on Customer.id = URP.idCustomer where URP.id = ? and URP.deleted = 0");
		$stmt->execute(array($id));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row = array('id'=>$rowData['id'],'name'=>$rowData['name']);
		return $row;
	}

}