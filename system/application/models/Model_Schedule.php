<?php

class Model_Schedule extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function newSchedule($data){
		$stmt = $this->db->prepare("insert into ScheduleTemplate (name, idCustomer, createTime, updateTime) values (?,?,now(),now())");
		$stmt->execute(array($data['name'],$data['idCustomer']));
		return $this->db->lastInsertId();
	}

	function newBlock($data){
		$stmt = $this->db->prepare("insert into ScheduleBlock (idCustomer, name, createTime, updateTime) values (?,?,now(),now())");
		$resultquery = $stmt->execute(array($data['idCustomer'],$data['name']));		
		return $this->db->lastInsertId();
	}

	function addBlockItem($data){	
		$stmt = $this->db->prepare("select max(ScheduleBlockItem.order) as orden from ScheduleBlockItem where idScheduleBlock = ? and deleted = 0");
		$stmt->execute(array($data['idBlock']));	
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 		
		$orden = $rowData['orden'] + 1;
		$stmt = $this->db->prepare("insert into ScheduleBlockItem (idScheduleBlock, idContent, length, ScheduleBlockItem.order, createTime, updateTime) values (?,?,?,?,now(),now())");
		$resultquery = $stmt->execute(array($data['idBlock'],$data['idContent'],$data['duracion'],$orden));
		return $this->db->lastInsertId();
	}

	function editScheduleName($id,$name){
		$stmt = $this->db->prepare("update ScheduleTemplate set name = ?, updateTime = now() where id = ?");
		$stmt->execute(array($name,$id));	
	}

	function editBlockName($id,$name){
		$stmt = $this->db->prepare("update ScheduleBlock set name = ?, updateTime = now() where id = ?");
		$stmt->execute(array($name,$id));	
	}

	function addScheduleBlock($data){
		$stmt = $this->db->prepare("insert into ScheduleBlockTemplate (idScheduleTemplate, idScheduleBlock, startHour, startMinute, createTime, updateTime) values (?,?,?,?,now(),now())");
		$stmt->execute(array($data['idScheduleTemplate'],$data['idScheduleBlock'],$data['startHour'],$data['startMinute']));
		return $this->db->lastInsertId();
	}

	function deleteSchedule($id){
		$stmt = $this->db->prepare("update ScheduleTemplate set deleted = 1, updateTime = now() where id = ?");	
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update ScheduleBlockTemplate set deleted = 1, updateTime = now() where idScheduleTemplate = ?");	
		$stmt->execute(array($id));
	}

	function deleteBlock($id){
		$stmt = $this->db->prepare("update ScheduleBlock set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update ScheduleBlockItem set deleted = 1, updateTime = now() where idScheduleBlock = ?");
		$stmt->execute(array($id));
	}

	function deleteBlockItem($id, $orden, $idScheduleBlock){
		// Elimina el elemento
		$stmt = $this->db->prepare("update ScheduleBlockItem set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		// Actualiza el orden de los elementos restantes
		$stmt = $this->db->prepare("select id from ScheduleBlockItem where ScheduleBlockItem.order > ? and ScheduleBlockItem.idScheduleBlock = ? and ScheduleBlockItem.deleted = 0 order by ScheduleBlockItem.order");
		$stmt->execute(array($orden,$idScheduleBlock));	
		$data = $stmt->fetchAll(); 
		foreach ($data as $rowData) {
			$sql = $this->db->prepare("update ScheduleBlockItem SET ScheduleBlockItem.order = ScheduleBlockItem.order - 1, updateTime = now() where id = ?");
			$sql->execute(array($rowData['id']));						
		}
	}	

	function removeBlockFromSchedule($id){
		$stmt = $this->db->prepare("update ScheduleBlockTemplate set deleted = 1 , updateTime = now() where id = ?");
		$stmt->execute(array($id));	
	}

	function editScheduleBlockItemLength($length,$id){
		$stmt = $this->db->prepare("update ScheduleBlockItem set length = ?, updateTime = now() where id = ?");
		$stmt->execute(array($length,$id));	
	}

	function editScheduleBlockItemOrder($order,$id){
		$stmt = $this->db->prepare("update ScheduleBlockItem set ScheduleBlockItem.order = ?, updateTime = now() where id = ?");
		$stmt->execute(array($order,$id));	
	}

	function getScheduleName($id){
		$stmt = $this->db->prepare("select name from ScheduleTemplate where id = ?");
		$stmt->execute(array($id));
		$rowContent = $stmt->fetch(PDO::FETCH_ASSOC);
		return $rowContent['name'];	
	}

	function getBlockName($idBlock){
		$stmt = $this->db->prepare("select name from ScheduleBlock where id = ?");
		$stmt->execute(array($idBlock));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC);	
		return $rowData['name'];
	}

	function getSchedulesByCustomer($idCustomer){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select id, name from ScheduleTemplate where idCustomer = ? and deleted = 0 order by name");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $rowTemplate) {
			$rows[] = array('id'=>$rowTemplate['id'],'name'=>$rowTemplate['name']);
		}
		return $rows;
	}

	function getSchedulesByCustomerWithDelete($idCustomer){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select ScheduleTemplate.id, ScheduleTemplate.name, ScheduleCalendar.id as idScheduleCalendar, ScheduleWeek.id as idScheduleWeek, ScheduleTemplateDefault.id as idScheduleTemplateDefault from ScheduleTemplate left join ScheduleCalendar on ScheduleCalendar.id = (select ScheduleCalendar.id from ScheduleCalendar where ScheduleCalendar.idScheduleTemplate = ScheduleTemplate.id and ScheduleCalendar.deleted = 0 limit 1) left join ScheduleWeek on ScheduleWeek.id = (select ScheduleWeek.id from ScheduleWeek where ScheduleWeek.idScheduleTemplate = ScheduleTemplate.id and ScheduleWeek.deleted = 0 limit 1) left join ScheduleTemplateDefault on ScheduleTemplateDefault.id = (select ScheduleTemplateDefault.id from ScheduleTemplateDefault where ScheduleTemplateDefault.idScheduleTemplate = ScheduleTemplate.id and ScheduleTemplateDefault.deleted = 0 limit 1) where ScheduleTemplate.idCustomer = ? and ScheduleTemplate.deleted = 0 order by ScheduleTemplate.name");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $rowTemplate) {
			// Valida que el Template no este siendo utilizado en ScheduleCalendar, ScheduleTemplateDefault y ScheduleWeek
			$delete = false;
			if (is_null($rowTemplate['idScheduleCalendar']) && is_null($rowTemplate['idScheduleWeek']) && is_null($rowTemplate['idScheduleTemplateDefault'])) 
				$delete = true;
			$rows[] = array('id'=>$rowTemplate['id'],'name'=>$rowTemplate['name'],'delete'=>$delete);
		}
		return $rows;
	}

	function getBlocksBySchedule($id){
		$rows =array();
		$stmt = $this->db->prepare("select ScheduleBlockTemplate.id as idScheduleBlockTemplate, ScheduleBlock.id as idScheduleBlock, ScheduleBlock.name, ScheduleBlockTemplate.startHour, ScheduleBlockTemplate.startMinute from ScheduleBlockTemplate inner join ScheduleBlock on ScheduleBlock.id = ScheduleBlockTemplate.idScheduleBlock and ScheduleBlock.deleted = 0 where ScheduleBlockTemplate.idScheduleTemplate = ? and ScheduleBlockTemplate.deleted = 0 order by ScheduleBlockTemplate.startHour, ScheduleBlockTemplate.startMinute");
		$stmt->execute(array($id));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] =array('id'=>$row['idScheduleBlock'],'idScheduleBlockTemplate'=>$row['idScheduleBlockTemplate'],'name'=>$row['name'],'startHour'=>str_pad($row['startHour'], 2, "0", STR_PAD_LEFT),'startMinute'=>str_pad($row['startMinute'], 2, "0", STR_PAD_LEFT));
		}
		return $rows;
	}

	function getBlocksByContent($id){
		$rows =array();
		$stmt = $this->db->prepare("select distinct ScheduleBlock.id as idScheduleBlock, ScheduleBlock.name as nameScheduleBlock from ScheduleBlock inner join ScheduleBlockItem on ScheduleBlock.id = ScheduleBlockItem.idScheduleblock and ScheduleBlock.deleted = 0 where ScheduleBlockItem.idContent = ? and ScheduleBlockItem.deleted = 0");
		$stmt->execute(array($id));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] =array('id'=>$row['idScheduleBlock'],'name'=>$row['nameScheduleBlock']);
		}
		return $rows;
	}

	function getUniqueBlocksBySchedule($id){
		$rows =array();
		$stmt = $this->db->prepare("select ScheduleBlock.id as idScheduleBlock, ScheduleBlock.name from ScheduleBlockTemplate inner join ScheduleBlock on ScheduleBlock.id = ScheduleBlockTemplate.idScheduleBlock and ScheduleBlock.deleted = 0 where ScheduleBlockTemplate.idScheduleTemplate = ? and ScheduleBlockTemplate.deleted = 0 group by ScheduleBlock.id order by ScheduleBlock.name");
		$stmt->execute(array($id));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] =array('id'=>$row['idScheduleBlock'],'name'=>$row['name']);
		}
		return $rows;
	}

	function getBlocksByCustomerWithDelete($id){
		$rows =array();
		$stmt = $this->db->prepare("select ScheduleBlock.id, ScheduleBlock.name, ScheduleBlockTemplate.id as idScheduleBlockTemplate from ScheduleBlock left join ScheduleBlockTemplate on ScheduleBlockTemplate.id = (select ScheduleBlockTemplate.id from ScheduleBlockTemplate where ScheduleBlockTemplate.idScheduleBlock = ScheduleBlock.id and ScheduleBlockTemplate.deleted = 0 limit 1) where ScheduleBlock.idCustomer = ? and ScheduleBlock.deleted = 0 order by ScheduleBlock.name");
		$stmt->execute(array($id));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $rowScheduleBlock) {
			$delete = false;
			if (is_null($rowScheduleBlock['idScheduleBlockTemplate'])) 
				$delete = true;
			$rows[] = array('id'=>$rowScheduleBlock['id'],'name'=>$rowScheduleBlock['name'],'delete'=>$delete);
		}
		return $rows;
	}

	function getBlocksByCustomer($id){
		$rows =array();
		$stmt = $this->db->prepare("select id, name from ScheduleBlock where idCustomer = ? and deleted = 0 order by name");
		$stmt->execute(array($id));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('id'=>$row['id'],'name'=>$row['name']);
		}
		return $rows;
	}

	function getBlockItemsByBlock($idBlock){
		$rows=array();
		$stmt = $this->db->prepare("select ScheduleBlockItem.id, Content.name, ScheduleBlockItem.idContent, ScheduleBlockItem.length, ScheduleBlockItem.order, Content.updateTime from ScheduleBlockItem inner join Content on Content.id=ScheduleBlockItem.idContent and Content.deleted = 0 where idScheduleBlock = ? and ScheduleBlockItem.deleted = 0 order by ScheduleBlockItem.order");
		$stmt->execute(array($idBlock));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {		
			$rows[] =array('id'=>$row['id'],'name'=>$row['name'],'idContent'=>$row['idContent'],'length'=>$row['length'],'order'=>$row['order'],'contentUpdate'=>$row['updateTime']);
		}
		return $rows;
	}

	function removeScheduleBlocks($idScheduleTemplate){
		$stmt = $this->db->prepare("update ScheduleBlockTemplate set deleted = 1, updateTime = now() where idScheduleTemplate = ? and deleted = 0");	
		$stmt->execute(array($idScheduleTemplate));
	}

	function replaceScheduleBlock($idScheduleTemplate,$idBlock,$idNewBlock){
		$stmt = $this->db->prepare("update ScheduleBlockTemplate set idScheduleBlock = ? where idScheduleTemplate = ? and idScheduleBlock = ? and deleted = 0");	
		$stmt->execute(array($idNewBlock,$idScheduleTemplate,$idBlock));
	}

	function getFullSchedule($idScheduleTemplate){
		$rows=array();
		// Recupera bloques de la agenda
		$stmt = $this->db->prepare("select ScheduleTemplate.id as idAgenda, ScheduleBlock.id as idBloque, ScheduleTemplate.name, ScheduleBlock.name, ScheduleBlockTemplate.startHour, ScheduleBlockTemplate.startMinute, count(ScheduleBlockItem.id) as contents from ScheduleTemplate inner join ScheduleBlockTemplate on ScheduleBlockTemplate.idScheduleTemplate = ScheduleTemplate.id and ScheduleBlockTemplate.deleted = 0 inner join ScheduleBlock on ScheduleBlock.id = ScheduleBlockTemplate.idScheduleBlock and ScheduleBlock.deleted = 0 inner join ScheduleBlockItem on ScheduleBlockItem.idScheduleBlock = ScheduleBlock.id and ScheduleBlockItem.deleted = 0 where ScheduleTemplate.id = ? and ScheduleTemplate.deleted = 0 group by ScheduleTemplate.id, ScheduleBlock.id, ScheduleTemplate.name, ScheduleBlock.name, ScheduleBlockTemplate.startHour, ScheduleBlockTemplate.startMinute order by ScheduleBlockTemplate.startHour, ScheduleBlockTemplate.startMinute");
		$stmt->execute(array($idScheduleTemplate));
		$blocks = $stmt->fetchAll(); 
		$maxBlocks = count($blocks);
		for ($i=0;$i<$maxBlocks; $i++) {
			$blockItemsArray = array();
			// Recupera items del bloque
			$stmt = $this->db->prepare("select Content.id as idContent, Content.name as contentName, ScheduleBlockItem.length, ScheduleBlockItem.order from ScheduleBlock inner join ScheduleBlockItem on ScheduleBlockItem.idScheduleBlock = ScheduleBlock.id and ScheduleBlockItem.deleted = 0 inner join Content on Content.id = ScheduleBlockItem.idContent and Content.deleted = 0 where ScheduleBlock.id = ? and ScheduleBlock.deleted = 0 order by ScheduleBlockItem.order");
			$stmt->execute(array($blocks[$i]['idBloque']));
			$blockItems = $stmt->fetchAll(); 
			//Si la agenda tiene un solo bloque con un solo contenido
			if (($maxBlocks==1)&&(count($blockItems)==1))
				$rows[] = array('idAgenda'=>$blocks[0]['idAgenda'],'name'=>$blockItems[0]['contentName'],'startHour'=>str_pad($blocks[0]['startHour'], 2, "0", STR_PAD_LEFT),'startMinute'=>str_pad($blocks[0]['startMinute'], 2, "0", STR_PAD_LEFT));
			else if (count($blockItems)>=1) {
				//Si el bloque tiene un solo contenido
				if (count($blockItems)==1)
					$rows[] = array('idAgenda'=>$blocks[$i]['idAgenda'],'name'=>$blockItems[0]['contentName'],'startHour'=>str_pad($blocks[$i]['startHour'], 2, "0", STR_PAD_LEFT),'startMinute'=>str_pad($blocks[$i]['startMinute'], 2, "0", STR_PAD_LEFT));
				else {
					//Múltiples contenidos en bloque
					foreach ($blockItems as $blockItem) {
						$blockItemsArray[] = array('idContent'=>$blockItem['idContent'],'contentName'=>$blockItem['contentName'],'length'=>$blockItem['length'],'order'=>$blockItem['order']);	
					}
					//duracion total posible en minutos	
					$datetime1 = new DateTime($blocks[$i]['startHour'].":".$blocks[$i]['startMinute']);
					$endHour = 23;
					$endMinute = 59;
					if ($i+1!=$maxBlocks) {
							$endHour = $blocks[$i+1]['startHour'];
							$endMinute = $blocks[$i+1]['startMinute'];
					}
					$datetime2 = new DateTime($endHour.":".$endMinute);
					$interval = $datetime2->diff($datetime1);
					$hours = $interval->format('%h'); 
					$minutes = $interval->format('%i');
					$TiempoTotalPosible = ($hours * 60) + $minutes;
					$duracionTotalPosible = $TiempoTotalPosible;
					if ($blocks[$i]['startHour'] > $endHour)
						$duracionTotalPosible = 1440 - $TiempoTotalPosible;
					$minutosAcumulados = 0;
					$lengthContenido = 0;
					$j = 0;
					$numBlockItems = $blocks[$i]['contents'];
					$startInitial = $blocks[$i]['startHour'];
					$startMinuteInitial = $blocks[$i]['startMinute'];
					//hasta cuando se repite el contenido
					while ($minutosAcumulados < $duracionTotalPosible) {		
						//si llego al final, volver a empezar
						if ($numBlockItems < $j + 1)		
							$j = 0;
						$lengthContenido = $blockItemsArray[$j]['length'];
						$idContent = $blockItemsArray[$j]['idContent'];		
						$prevStartMinute = $startMinuteInitial + (($minutosAcumulados) % 60);				
						$startHour = ($startInitial + intval(floor($minutosAcumulados / 60)) + intval(floor($prevStartMinute / 60)))%24;
						$startHour = str_pad($startHour, 2, "0", STR_PAD_LEFT);
						$startMinute = ($startMinuteInitial + $minutosAcumulados) % 60;		
						$startMinute = str_pad($startMinute, 2, "0", STR_PAD_LEFT);
						$rows[] = array('idAgenda'=>$blocks[$i]['idAgenda'],'name'=>$blockItemsArray[$j]['contentName'],'startHour'=>$startHour,'startMinute'=>$startMinute);	
						$minutosAcumulados = $minutosAcumulados + $lengthContenido;
						$j++;
					}
				}
			}
		}
		return $rows;
	}

}