<?php

class Model_Store extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function newStore($data){
		$stmt = $this->db->prepare("insert into Store (name, idZone, createTime, updateTime) values (?,?,now(),now())");
		$stmt->execute(array($data['name'],$data['idZone']));
	}

	function editStore($id,$name,$zona){
		$stmt = $this->db->prepare("update Store set name = ?, idZone = ?, updateTime = now() where id = ?");
		$stmt->execute(array($name,$zona,$id));	
	}

	function editStoreAdmin($id, $lat, $lng, $installDate){
		$stmt = $this->db->prepare("update Store set lat = ?, lng = ?, installDate = ?, updateTime = now() where id = ?");
		$stmt->execute(array($lat, $lng, $installDate, $id));	
	}

	function deleteStore($id){
		$stmt = $this->db->prepare("update Store set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
	}

	function getStores(){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select Store.id, Customer.name as cliente, Zone.name as zona, Store.name as local, Store.lat, Store.lng, Store.installDate from Store inner join Zone on Zone.id = Store.idZone inner join Customer ON Customer.id = Zone.idCustomer where Store.deleted = 0 order by Customer.name, Zone.name, Store.name");
		$stmt->execute();
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $data) {	
			$rows[] = array('id'=>$data['id'],'cliente'=>$data['cliente'],'zona'=>$data['zona'],'local'=>$data['local'],'lat'=>$data['lat'],'lng'=>$data['lng'],'installDate'=>$data['installDate']);
		}
		return $rows;
	}

	function getStoresByZone($id){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select id, name from Store where idZone = ? and deleted = 0 order by name");
		$stmt->execute(array($id));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $data) {	
			$rows[] = array('id'=>$data['id'],'name'=>$data['name']);
		}
		return $rows;
	}

	function getStoresByCustomer($idCustomer){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select Store.id, Store.name from Store inner join Zone on Zone.idCustomer = ? and Zone.deleted = 0 where Store.idZone = Zone.id and Store.deleted = 0 order by Store.name");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('id'=>$row['id'],'name'=>$row['name']);
		}
		return $rows;
	}

	function getStore($id){
		$stmt = $this->db->prepare("select Store.name, Store.lat, Store.lng, Store.installDate, Zone.id as idZone, Zone.name as zoneName, Customer.id as idCustomer, Customer.name as customerName from Store inner join Zone on Zone.id = Store.idZone inner join Customer on Customer.id = Zone.idCustomer where Store.id = ?");
		$stmt->execute(array($id));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row = array('id'=>$id,'name'=>$rowData['name'],'lat'=>$rowData['lat'],'lng'=>$rowData['lng'],'installDate'=>$rowData['installDate'],"zoneId"=>$rowData['idZone'],"zoneName"=>$rowData['zoneName'],"idCustomer"=>$rowData['idCustomer'],"customerName"=>$rowData['customerName']);
		return $row;
	}	

}