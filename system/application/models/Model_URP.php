<?php

class Model_URP extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function getURPs(){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select URP.id as idURP, URP.name, URP.macAddress, URPDisplay.idDisplay from URP left join URPDisplay on URPDisplay.idURP = URP.id and URPDisplay.deleted = 0 where URP.deleted = 0");
		$stmt->execute();
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('id'=>$row['idURP'],'name'=>$row['name'],'macAddress'=>$row['macAddress'],'idDisplay'=>$row['idDisplay']);
		}
		return $rows;
	}

	function getWalls(){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select URP.id as idURP, URP.name, Customer.id as idCustomer, Customer.name as customer, Zone.name as zone, Store.name as store, Display.name as display from URP left join URPDisplay on URPDisplay.idURP = URP.id and URPDisplay.deleted = 0 left join Display on Display.id = URPDisplay.idDisplay and Display.deleted = 0 left join Store on Store.id = Display.idStore and Store.deleted = 0 left join Zone on Zone.id = Store.idZone and Zone.deleted = 0 left join Customer on Customer.id = URP.idCustomer where URP.videoWall = 1 and URP.deleted = 0");
		$stmt->execute();
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('idURP'=>$row['idURP'],'name'=>$row['name'],'idCustomer'=>$row['idCustomer'],'customer'=>$row['customer'],'zone'=>$row['zone'],'store'=>$row['store'],'display'=>$row['display']);
		}
		return $rows;
	}

	function getWallByURP($idURP){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select Wall.id, URP.id as idURP, URP.name from Wall inner join URP on URP.id = Wall.idSlave where Wall.idURP = ? and Wall.deleted = 0");
		$stmt->execute(array($idURP));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('id'=>$row['id'],'idURP'=>$row['idURP'],'name'=>$row['name']);
		}
		return $rows;
	}

	function getURPsByCustomer($idCustomer){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select URP.id as idURP, URP.name as URPName, URP.macAddress, Display.name as displayName from URP left join URPDisplay on URPDisplay.idURP = URP.id and URPDisplay.deleted = 0 left join Display on Display.id = URPDisplay.idDisplay where URP.idCustomer = ? and URP.deleted = 0");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('id'=>$row['idURP'],'name'=>$row['URPName'],'macAddress'=>$row['macAddress'],'displayName'=>$row['displayName']);
		}
		return $rows;
	}

	function getURPsFreeByCustomer($idCustomer){
		//$rows = "";
		$rows = array();
		//$stmt = $this->db->prepare("select URP.id, URP.name, URP.macAddress from URP where URP.id not in (select idURP from URPDisplay where deleted = 0) and URP.id not in (select idSlave from Wall where deleted = 0) and deleted = 0 and idCustomer = ?");
		$stmt = $this->db->prepare("select URP.id, URP.name, URP.macAddress from URP where URP.id not in (select idURP from URPDisplay where deleted = 0) and URP.id not in (select idSlave from Wall where deleted = 0) and deleted = 0");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('id'=>$row['id'],'name'=>$row['name'],'macAddress'=>$row['macAddress']);
		}
		return $rows;
		//*********************************************************************************************************** */
	}

	function getURP($idURP){
		$result = "";
		$stmt = $this->db->prepare("select URP.name, URP.macAddress, Customer.id as idCustomer, Customer.name as customer, Zone.name as zone, Store.name as store, Display.name as display, URP.videoWall from URP left join Customer on Customer.id = URP.idCustomer left join URPDisplay on URPDisplay.idURP = URP.id and URPDisplay.deleted = 0 left join Display on Display.id = URPDisplay.idDisplay and Display.deleted = 0 left join Store on Store.id = Display.idStore and Store.deleted = 0 left join Zone on Zone.id = Store.idZone and Zone.deleted = 0 where URP.id = ?");
		$stmt->execute(array($idURP));
		$row = $stmt->fetch(PDO::FETCH_ASSOC); 
		$result = array('id'=>$idURP,'name'=>$row['name'],'macAddress'=>$row['macAddress'],'idCustomer'=>$row['idCustomer'],'customer'=>$row['customer'],'zone'=>$row['zone'],'store'=>$row['store'],'display'=>$row['display'],'videoWall'=>$row['videoWall']);
		return $result;
	}

	function getDisplayByURP($idURP){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select idDisplay from URPDisplay where idURP = ? and deleted = 0");
		$stmt->execute(array($idURP));
		$row = $stmt->fetch(PDO::FETCH_ASSOC); 
		return $row['idDisplay'];
	}

	function newURP($data){
		$stmt = $this->db->prepare("select id from URP where macAddress = ? and deleted = 0");
		$stmt->execute(array($data['macAddress']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (empty($row)) {
			$stmt = $this->db->prepare("insert into URP (name, idCustomer, macAddress, videoWall, createTime, updateTime) values (?,?,?,?,now(),now())");
			$stmt->execute(array($data['name'],$data['idCustomer'],$data['macAddress'],$data['videoWall']));
			return true;
		}
		else {
			return false;
		}
	}

	function editURP($data){
		$stmt = $this->db->prepare("update URP set name = ?, idCustomer = ?, macAddress = ?, videoWall = ? where id = ?");
		$stmt->execute(array($data['name'],$data['idCustomer'],$data['macAddress'],$data['videoWall'], $data['id']));	
	}

	function deleteURP($id){
		$stmt = $this->db->prepare("update URP set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		$this->unlinkURP($id);
	}

	function unlinkURP($id){
		$stmt = $this->db->prepare("update URPDisplay set deleted = 1, updateTime = now() where idURP = ?");
		$stmt->execute(array($id));
	}

	function setLinkURP($data){
		$stmt = $this->db->prepare("insert into URPDisplay (idURP, idDisplay, createTime, updateTime) values (?,?,now(),now())");
		$stmt->execute(array($data['idUrp'],$data['idDisplay']));
	}

	function addURPWall($data){
		$stmt = $this->db->prepare("insert into Wall (idURP, idSlave, createTime, updateTime) values (?,?,now(),now())");
		$stmt->execute(array($data['idURP'],$data['idSlave']));
	}

	function removeURPWall($id){
		$stmt = $this->db->prepare("update Wall set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
	}

}