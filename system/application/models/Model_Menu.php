<?php

class Model_Menu extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function newMenu($data){
		$stmt = $this->db->prepare("insert into Menu (idCustomer, name, backgroundImage, idContent, backgroundColor, startHour, createTime, updateTime) values (?,?,?,?,?,?,now(),now())");
		$stmt->execute(array($data['idCustomer'],$data['name'],$data['backgroundImage'],$data['idContent'],$data['backgroundColor'],$data['startHour']));	
		return $this->db->lastInsertId();
	}

	function editMenu($data){ 
		$stmt = $this->db->prepare("update Menu set name = ?, backgroundImage = ?, idContent = ?, backgroundColor = ?, startHour = ?, updateTime = now() where id = ?");
		$stmt->execute(array($data['name'],$data['backgroundImage'],$data['idContent'],$data['backgroundColor'],$data['startHour'],$data['id']));
		$stmt = $this->db->prepare("update MenuDisplay set MenuDisplay.updated = 1, MenuDisplay.updateTime = now() where MenuDisplay.idMenu = ? and MenuDisplay.updated = 0 and MenuDisplay.deleted = 0");
		$stmt->execute(array($data['id']));	
	}

	function deleteMenu($id){
		$stmt = $this->db->prepare("update Menu set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
	}

	function newMenuItem($data){
		$stmt = $this->db->prepare("insert into MenuItem (idCustomer, title, idContent, name, description, price, createTime, updateTime) values (?,?,?,?,?,?,now(),now())");
		$stmt->execute(array($data['idCustomer'],$data['title'],$data['idContent'],$data['name'],$data['description'],$data['price']));	
		return $this->db->lastInsertId();
	}

	function addMenuItem($data){		
		$stmt = $this->db->prepare("select max(MenuContent.order) as orden from MenuContent where idMenu = ? and deleted = 0");
		$stmt->execute(array($data['idMenu']));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 		
		$orden = $rowData['orden'] + 1;
		$stmt = $this->db->prepare("insert into MenuContent (idMenu, idMenuItem, MenuContent.order, createTime, updateTime) values (?,?,?,now(),now())");
		$stmt->execute(array($data['idMenu'],$data['idMenuItem'],$orden));	
		$idMenuContent = $this->db->lastInsertId();
		$stmt = $this->db->prepare("update MenuDisplay set MenuDisplay.updated = 1, MenuDisplay.updateTime = now() where MenuDisplay.idMenu = ? and MenuDisplay.updated = 0 and MenuDisplay.deleted = 0");
		$stmt->execute(array($data['idMenu']));
		return $idMenuContent;
	}

	function getMenuItemsByMenu($idMenu){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select MenuContent.id as idMenuContent, MenuItem.name, MenuItem.title, MenuItem.description, MenuItem.price, MenuContent.order from MenuContent inner join MenuItem on MenuItem.id = MenuContent.idMenuItem and MenuItem.deleted = 0 where MenuContent.idMenu = ? and MenuContent.deleted = 0 order by MenuContent.order");
		$stmt->execute(array($idMenu));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {								
			$rows[] = array('id'=>$rowData['idMenuContent'],'name'=>$rowData['name'],'title'=>$rowData['title'],'description'=>$rowData['description'],'price'=>$rowData['price'],'order'=>$rowData['order'],'idMenu'=>$idMenu);
		}
		return $rows;
	}

	function getMenuItemsByCustomer($idCustomer){
		//$rows = "";
		$rows = array();		
		$stmt = $this->db->prepare("select id, name, title, description, price from MenuItem where idCustomer = ? and deleted = 0");
		$stmt->execute(array($idCustomer));	
		$data = $stmt->fetchAll();
		foreach ($data as $rowData) {							
			$rows[] = array('id'=>$rowData['id'],'name'=>$rowData['name'],'title'=>$rowData['title'],'description'=>$rowData['description'],'price'=>$rowData['price']);		
		};
		return $rows;
	}

	function getMenuItem($id){
		$row = "";		
		$stmt = $this->db->prepare("select name, title, description, price, idContent from MenuItem where id = ?");
		$stmt->execute(array($id));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row = array('id'=>$id,'name'=>$rowData['name'],'title'=>$rowData['title'],'description'=>$rowData['description'],'price'=>$rowData['price'],'idContent'=>$rowData['idContent']);	
		return $row;
	}

	function getMenu($id){
		$row = "";		
		$stmt = $this->db->prepare("select name, backgroundImage, backgroundColor, startHour, idContent from Menu where id = ?");
		$stmt->execute(array($id));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$startHour = str_pad($rowData['startHour'],2,"0",STR_PAD_LEFT).":00";
		$row = array('id'=>$id,'name'=>$rowData['name'],'backgroundImage'=>$rowData['backgroundImage'],'backgroundColor'=>$rowData['backgroundColor'],'startHour'=>$startHour,'idContent'=>$rowData['idContent']);
		return $row;
	}

	function editMenuItem($data){ 
		$stmt = $this->db->prepare("update MenuItem set title = ?, name = ?, description = ?, price = ?, idContent = ?, updateTime = now() where id = ?");
		$stmt->execute(array($data['title'],$data['name'],$data['description'],$data['price'],$data['idContent'],$data['id']));	
		$stmt = $this->db->prepare("update MenuDisplay inner join MenuContent on MenuContent.idMenuItem = ? and MenuContent.deleted = 0 set MenuDisplay.updated = 1, MenuDisplay.updateTime = now() where MenuDisplay.idMenu = MenuContent.idMenu and MenuDisplay.updated = 0 and MenuDisplay.deleted = 0");
		$stmt->execute(array($data['id']));	
	}

	function editMenuContentOrder($order,$id){
		$stmt = $this->db->prepare("update MenuContent set MenuContent.order = ?, updateTime = now() where id = ?");
		$stmt->execute(array($order,$id));
		$stmt = $this->db->prepare("update MenuDisplay inner join MenuContent on MenuContent.id = ? and MenuContent.deleted = 0 set MenuDisplay.updated = 1, MenuDisplay.updateTime = now() where MenuDisplay.idList = MenuContent.idList and MenuDisplay.updated = 0 and MenuDisplay.deleted = 0");
		$stmt->execute(array($id));		
	}

	function deleteMenuItem($id){
		$stmt = $this->db->prepare("update MenuItem set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update MenuDisplay inner join MenuContent on MenuContent.idMenuItem = ? and MenuContent.deleted = 0 set MenuDisplay.updated = 1, MenuDisplay.updateTime = now() where MenuDisplay.idMenu = MenuContent.idMenu and MenuDisplay.updated = 0 and MenuDisplay.deleted = 0");
		$stmt->execute(array($id));	
	}

	function deleteMenuContent($id, $orden, $idMenu){
		// Elimina el elemento
		$stmt = $this->db->prepare("update MenuContent set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		// Actualiza el orden de los elementos restantes
		$stmt = $this->db->prepare("select id from MenuContent where MenuContent.order > ? and idMenu = ? and deleted = 0 order by MenuContent.order");
		$stmt->execute(array($orden,$idMenu));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {
			$sql = $this->db->prepare("update MenuContent set MenuContent.order = MenuContent.order - 1, updateTime = now() where id = ?");
			$sql->execute(array($rowData['id']));						
		}
		$stmt = $this->db->prepare("update MenuDisplay inner join MenuContent on MenuContent.id = ? and MenuContent.deleted = 0 set MenuDisplay.updated = 1, MenuDisplay.updateTime = now() where MenuDisplay.idMenu = MenuContent.idMenu and MenuDisplay.updated = 0 and MenuDisplay.deleted = 0");
		$stmt->execute(array($id));	
	}

	function removeMenuDisplay($id){
		$stmt = $this->db->prepare("update MenuDisplay set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
	}

	function getMenuDisplay($idCustomer){
		$rows="";
		$stmt = $this->db->prepare("select MenuDisplay.id, MenuDisplay.idDisplay, MenuDisplay.idMenu, Display.name as displayName, Menu.name as menuName from MenuDisplay inner join Display on Display.id = MenuDisplay.idDisplay and Display.deleted = 0 inner join Menu on Menu.id = MenuDisplay.idMenu and Menu.deleted = 0 where MenuDisplay.idCustomer = ? and MenuDisplay.deleted = 0");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {		
			$rows[] =array('id'=>$row['id'],'idMenu'=>$row['idMenu'],'idDisplay'=>$row['idDisplay'],'nameMenu'=>$row['menuName'],'nameDisplay'=>$row['displayName']);
		}
		return $rows;
	}	

	function addMenuDisplay($data){
		foreach ($data['displays'] as $display) {
			$stmt = $this->db->prepare("insert into MenuDisplay (idMenu, idDisplay, idCustomer, createTime, updateTime) values (?,?,?,now(),now())");
			$stmt->execute(array($data['idMenu'], $display, $data['idCustomer']));	
		}
		return $this->db->lastInsertId();
	}

	function editMenuDisplay($idMenu,$idDisplay,$id){
		$stmt = $this->db->prepare("update MenuDisplay set idMenu = ?, idDisplay = ?, updateTime = now() where id = ?");
		$stmt->execute(array($idMenu,$idDisplay,$id));
	}

	function getMenusByCustomer($idCustomer){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select id, name, idContent, backgroundImage, backgroundColor, startHour from Menu where idCustomer = ? and deleted = 0");
		$stmt->execute(array($idCustomer));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {
			if ($rowData['backgroundImage'] == 0)
				$background = $rowData['backgroundColor'];
			else {		
				$stmtContent = $this->db->prepare("select name from Content where id = ?");
				$stmtContent->execute(array($rowData['idContent']));
				$rowContentName = $stmtContent->fetch(PDO::FETCH_ASSOC); 
				$background = $rowContentName['name'];
			}
			$rows[] = array('id'=>$rowData['id'],'name'=>$rowData['name'],'idContent'=>$rowData['idContent'],'background'=>$background,'startHour'=>$rowData['startHour']);	
		}	
		return $rows;
	}

}