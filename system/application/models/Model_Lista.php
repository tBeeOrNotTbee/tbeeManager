<?php

class Model_Lista extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function newLista($data){   
		$stmt = $this->db->prepare("insert into List (idCustomer, name, createTime, updateTime) values (?,?,now(),now())");
		$stmt->execute(array($data['idCustomer'],$data['name']));	
		return $this->db->lastInsertId();
	}

	function deleteLista($id){
		$stmt = $this->db->prepare("update List set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
	}

	function getLista($id){
		$row = "";		
		$stmt = $this->db->prepare("select name from List where id = ?");
		$stmt->execute(array($id));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row = array('id'=>$id,'name'=>$rowData['name']);
		return $row;
	}

	function editLista($data){ 
		$stmt = $this->db->prepare("update List set name = ?, updateTime = now() where id = ?");
		$stmt->execute(array($data['name'],$data['id']));
	}

	function newTipoItem($data){   
		$stmt = $this->db->prepare("insert into ListItemType (idCustomer, name, backgroundColor, fontColor, createTime, updateTime) values (?,?,?,?,now(),now())");
		$stmt->execute(array($data['idCustomer'],$data['name'],$data['backgroundColor'],$data['fontColor']));	
		return $this->db->lastInsertId();
	}

	function deleteTipoItem($id){
		$stmt = $this->db->prepare("update ListItemType set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update ListDisplay inner join ListItem on ListItem.idListItemType = ? and ListItem.deleted = 0 inner join ListContent on ListContent.idListItem = ListItem.id and ListContent.deleted = 0 set ListDisplay.updated = 1, ListDisplay.updateTime = now() where ListDisplay.idList = ListContent.idList and ListDisplay.updated = 0 and ListDisplay.deleted = 0");
		$stmt->execute(array($id));
	}

	function getTipoItem($id){
		$row = "";		
		$stmt = $this->db->prepare("select name, backgroundColor, fontColor from ListItemType where id = ?");
		$stmt->execute(array($id));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row = array('id'=>$id,'name'=>$rowData['name'],'backgroundColor'=>$rowData['backgroundColor'],'fontColor'=>$rowData['fontColor']);
		return $row;
	}

	function getTipoItemsByCustomer($idCustomer){
		$rows = array();		
		$stmt = $this->db->prepare("select id, name, backgroundColor, fontColor from ListItemType where idCustomer = ? and deleted = 0");
		$stmt->execute(array($idCustomer));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {		
			$rows[] = array('id'=>$rowData['id'],'name'=>$rowData['name'],'backgroundColor'=>$rowData['backgroundColor'],'fontColor'=>$rowData['fontColor']);
		}
		return $rows;
	}

	function editTipoItem($data){ 
		$stmt = $this->db->prepare("update ListItemType set name = ?, backgroundColor = ?, fontColor = ?, updateTime = now() where id = ?");
		$stmt->execute(array($data['name'],$data['backgroundColor'],$data['fontColor'],$data['id']));
		$stmt = $this->db->prepare("update ListDisplay inner join ListItem on ListItem.idListItemType = ? and ListItem.deleted = 0 inner join ListContent on ListContent.idListItem = ListItem.id and ListContent.deleted = 0 set ListDisplay.updated = 1, ListDisplay.updateTime = now() where ListDisplay.idList = ListContent.idList and ListDisplay.updated = 0 and ListDisplay.deleted = 0");
		$stmt->execute(array($data['id']));
	}

	function addListItem($data){   
		$stmt = $this->db->prepare("insert into ListItem (idCustomer, idListItemType, name, createTime, updateTime) values (?,?,?,now(),now())");
		$stmt->execute(array($data['idCustomer'],$data['idTipoItem'],$data['name']));	
		$idListItem = $this->db->lastInsertId();
		// Agrega iconos asociados		
		if ($data['selectIconos'] <> "") {
			foreach ($data['selectIconos'] as $icono) {
				$stmt = $this->db->prepare("insert into ListItemIcon (idListItem, idContent, createTime, updateTime) values (?,?,now(),now())");
				$stmt->execute(array($idListItem, $icono));	
			}	
		}
		return $idListItem;
	}

	function deleteListItem($id){
		$stmt = $this->db->prepare("update ListItem set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update ListItemIcon set deleted = 1, updateTime = now() where idListItem = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update ListDisplay inner join ListContent on ListContent.idListItem = ? and ListContent.deleted = 0 set ListDisplay.updated = 1, ListDisplay.updateTime = now() where ListDisplay.idList = ListContent.idList and ListDisplay.updated = 0 and ListDisplay.deleted = 0");
		$stmt->execute(array($id));	
	}

	function getListItems($id){
		$rows = array();		
		$stmt = $this->db->prepare("select name, idListItemType from ListItem where id = ?");
		$stmt->execute(array($id));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$stmt = $this->db->prepare("select idContent from ListItemIcon where idListItem = ? and deleted = 0");
		$stmt->execute(array($id));
		$data = $stmt->fetchAll(); 
		$icons = [];
		foreach ($data as $rowDataIcons) {
			array_push($icons, $rowDataIcons['idContent']);					
		}
		$rows = array('id'=>$id, 'name'=>$rowData['name'], 'idListItemType'=>$rowData['idListItemType'], 'icons'=>$icons);					
		return $rows;
	}

	function getListItemsByCustomer($idCustomer){
		$rows = array();		
		$stmt = $this->db->prepare("select ListItem.id as id, ListItem.name as name, ListItemType.name as tipoitem from ListItem inner join ListItemType on ListItemType.id = ListItem.idListItemType where ListItem.idCustomer = ? and ListItem.deleted = 0 order by ListItemType.id, ListItem.name");
		$stmt->execute(array($idCustomer));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {	
			$rows[] = array('id'=>$rowData['id'], 'name'=>$rowData['name'], 'tipoitem'=>$rowData['tipoitem']);		
		}
		return $rows;
	}

	function getListItemsNotInListByCustomer($idCustomer,$idList){
		$rows = array();		
		$stmt = $this->db->prepare("select id, name from ListItem where idCustomer = ? and deleted = 0 and id not in (select idListItem from ListContent where idList = ? and deleted = 0)");
		$stmt->execute(array($idCustomer,$idList));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {
			$rows[] = array('id'=>$rowData['id'],'name'=>$rowData['name']);
		}
		return $rows;
	}

	function editListItem($data){ 
		$stmt = $this->db->prepare("update ListItem set name = ?, idListItemType = ?, updateTime = now() where id = ?");
		$stmt->execute(array($data['name'],$data['idTipoItem'],$data['id']));
		// Actualiza iconos asociados
		$stmt = $this->db->prepare("update ListItemIcon set deleted = 1, updateTime = now() where idListItem = ?");
		$stmt->execute(array($data['id']));	
		if ($data['selectIconos'] <> ""){
			foreach ($data['selectIconos'] as $icono) {
				$stmt = $this->db->prepare("insert into ListItemIcon (idListItem, idContent, createTime, updateTime) values (?,?,now(),now())");
				$stmt->execute(array($data['id'], $icono));	
			}	
		}
		$stmt = $this->db->prepare("update ListDisplay inner join ListContent on ListContent.idListItem = ? and ListContent.deleted = 0 set ListDisplay.updated = 1, ListDisplay.updateTime = now() where ListDisplay.idList = ListContent.idList and ListDisplay.updated = 0 and ListDisplay.deleted = 0");
		$stmt->execute(array($data['id']));		
	}

	function getListContent($id){
		$rows = array();		
		$stmt = $this->db->prepare("select ListContent.id as id, ListItem.name as name, ListContent.order as orden, ListContent.disabled as disabled, ListContent.idList as idList from ListContent inner join ListItem on ListContent.idListItem = ListItem.id where ListContent.idList = ? and ListContent.deleted = 0 order by orden");
		$stmt->execute(array($id));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {		
			$rows[] = array('id'=>$rowData['id'],'name'=>$rowData['name'],'order'=>$rowData['orden'],'disabled'=>$rowData['disabled'],'idList'=>$id);
		}
		return $rows;
	}
	
	function deleteListContent($id, $orden, $idList){
		// Elimina el elemento de la lista
		$stmt = $this->db->prepare("update ListContent set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		// Actualiza el orden de los elementos restantes de la lista
		$stmt = $this->db->prepare("select id from ListContent where ListContent.order > ? and idlist = ? and deleted = 0 order by ListContent.order");
		$stmt->execute(array($orden,$idList));	
		$data = $stmt->fetchAll(); 
		foreach($data as $rowData){
			$sql = $this->db->prepare("update ListContent set ListContent.order = ListContent.order - 1, updateTime = now() where id = ?");
			$sql->execute(array($rowData['id']));						
		}
		$stmt = $this->db->prepare("update ListDisplay inner join ListContent on ListContent.id = ? and ListContent.deleted = 0 set ListDisplay.updated = 1, ListDisplay.updateTime = now() where ListDisplay.idList = ListContent.idList and ListDisplay.updated = 0 and ListDisplay.deleted = 0");
		$stmt->execute(array($id));	
	}

	function editListContentOrder($order,$id){
		$stmt = $this->db->prepare("update ListContent set ListContent.order = ?, updateTime = now() where id = ?");
		$stmt->execute(array($order,$id));	
		$stmt = $this->db->prepare("update ListDisplay inner join ListContent on ListContent.id = ? and ListContent.deleted = 0 set ListDisplay.updated = 1, ListDisplay.updateTime = now() where ListDisplay.idList = ListContent.idList and ListDisplay.updated = 0 and ListDisplay.deleted = 0");
		$stmt->execute(array($id));	
	}

	function editListContentDisabled($disabled,$id){
		$stmt = $this->db->prepare("update ListContent set disabled = ?, updateTime = now() where id = ?");
		$stmt->execute(array($disabled,$id));
		$stmt = $this->db->prepare("update ListDisplay inner join ListContent on ListContent.id = ? and ListContent.deleted = 0 set ListDisplay.updated = 1, ListDisplay.updateTime = now() where ListDisplay.idList = ListContent.idList and ListDisplay.updated = 0 and ListDisplay.deleted = 0");
		$stmt->execute(array($id));		
	}

	function addListContent($data){		
		$stmt = $this->db->prepare("select max(ListContent.order) as orden from ListContent where idList = ? and deleted = 0");
		$stmt->execute(array($data['idList']));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 		
		$orden = $rowData['orden'] + 1;
		$stmt = $this->db->prepare("insert into ListContent (idList, idListItem, ListContent.order, createTime, updateTime) values (?,?,?,now(),now())");
		$stmt->execute(array($data['idList'], $data['idListItem'], $orden));
		$idListContent = $this->db->lastInsertId();
		$stmt = $this->db->prepare("update ListDisplay set ListDisplay.updated = 1, ListDisplay.updateTime = now() where ListDisplay.idList = ? and ListDisplay.updated = 0 and ListDisplay.deleted = 0");
		$stmt->execute(array($data['idList']));	
		return $idListContent;
	}

	function getListDisplay($idCustomer){
		$rows="";
		$stmt = $this->db->prepare("select ListDisplay.id, ListDisplay.idList, ListDisplay.idDisplay, List.name as listName, Display.name as displayName from ListDisplay inner join Display on Display.id = ListDisplay.idDisplay and Display.deleted = 0 inner join List on List.id = ListDisplay.idList and List.deleted = 0 where ListDisplay.idCustomer = ? and ListDisplay.deleted = 0");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach($rowData as $row){		
			$rows[] =array('id'=>$row['id'], 'idList'=>$row['idList'], 'idDisplay'=>$row['idDisplay'], 'nameList'=>$row['listName'], 'nameDisplay'=>$row['displayName']);
		}
		return $rows;
	}	

	function addListDisplay($data){   
		foreach ($data['displays'] as $display) {
			$stmt = $this->db->prepare("insert into ListDisplay (idList, idDisplay, idCustomer, createTime, updateTime) VALUES (?,?,?,now(),now())");
			$stmt->execute(array($data['idList'], $display, $data['idCustomer']));	
		}
		return $this->db->lastInsertId();
	}

	function removeListDisplay($id){		
		$stmt = $this->db->prepare("update ListDisplay set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
	}	

	function editListDisplay($idList,$idDisplay,$id){
		$stmt = $this->db->prepare("update ListDisplay set idList = ?, idDisplay = ?, updateTime = now() where id = ?");
		$stmt->execute(array($idList,$idDisplay,$id));
	}	

	function getListsByCustomer($idCustomer){
		$rows = "";		
		$stmt = $this->db->prepare("select id, name from List where idCustomer = ? and deleted= 0 order by name");
		$stmt->execute(array($idCustomer));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {		
			$rows[] = array('id'=>$rowData['id'],'name'=>$rowData['name']);		
		}
		return $rows;
	}	

}