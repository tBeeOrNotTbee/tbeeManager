<?php

class Model_Zone extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function getZone($id){
		$stmt = $this->db->prepare("select Zone.name, Customer.id as idCustomer, Customer.name as customerName from Zone inner join Customer on Customer.id = Zone.idCustomer where Zone.id = ?");
		$stmt->execute(array($id));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row = array('id'=>$id,'name'=>$rowData['name'],'idCustomer'=>$rowData['idCustomer'],'customerName'=>$rowData['customerName']);
		return $row;
	}

	function newZone($name,$idCustomer){
		$stmt = $this->db->prepare("insert into Zone (name, idCustomer, createTime, updateTime) values (?,?,now(),now())");
		$stmt->execute(array($name,$idCustomer));
	}

	function editZone($id,$name){
		$stmt = $this->db->prepare("update Zone set name = ?, updateTime = now() where id = ?");
		$stmt->execute(array($name,$id));	
	}

	function deleteZone($id){		
		$stmt = $this->db->prepare("update Zone set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
	}

	function getZonesByCustomer($id){
		//$rows="";
		$rows = array();
		$stmt = $this->db->prepare("select id, name from Zone where idCustomer = ? and deleted = 0 order by name");
		$stmt->execute(array($id));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $rowZone) {
			$rows[] =array('id'=>$rowZone['id'],'name'=>$rowZone['name']);
		}
		return $rows;
	}
	
}