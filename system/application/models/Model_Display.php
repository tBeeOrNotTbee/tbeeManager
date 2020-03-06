<?php

class Model_Display extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function getDisplaysByStore($id){
		$rows=array();
		$stmt = $this->db->prepare("select Display.id, Display.name, Display.vertical, URP.id as idURP, Online.ip as ip, Online.port as port, Online.uptime as uptime, Online.lastUpdate as lastUpdate, ScheduleTemplate.id as idScheduleTemplate, ScheduleTemplate.name as scheduleTemplateName from Display left join ScheduleTemplateDefault on ScheduleTemplateDefault.idDisplay = Display.id and ScheduleTemplateDefault.deleted = 0 left join ScheduleTemplate on ScheduleTemplate.id = ScheduleTemplateDefault.idScheduleTemplate and ScheduleTemplate.deleted = 0 left join URPDisplay on URPDisplay.idDisplay = Display.id and URPDisplay.deleted = 0 left join URP on URP.id = URPDisplay.idURP and URP.deleted = 0 left join Online on Online.idURP = URP.id where Display.idStore = ? and Display.deleted = 0 order by Display.name");
		$stmt->execute(array($id));
		$rowData = $stmt->fetchAll(); 
		if (!empty($rowData)) {
			foreach ($rowData as $data) {				
				if (!$data['lastUpdate'])
					$lastUpdate = "";
				else
					$lastUpdate = date('d-m H:i',strtotime($data['lastUpdate']));
				if (!$data['uptime'])
					$uptime = "";
				else {		
					$date = new DateTime('@'.$data['uptime']);
					$epoch = new DateTime("@0");
					$diff = $date->diff($epoch);
					if ($diff->days ==0)
						$uptime = gmdate("H:i:s",$data['uptime']);
					else if ($diff->days==1)
						$uptime = $diff->days . " día " . gmdate("H:i:s",$data['uptime']);
					else
						$uptime = $diff->days . " días " . gmdate("H:i:s",$data['uptime']);
				}
				$rows[] = array('id'=>$data['id'],'name'=>$data['name'],'vertical'=>$data['vertical'],'lastUpdate'=>$lastUpdate,'uptime'=>$uptime,'templateDefault'=>$data['scheduleTemplateName'],'idTemplateDefault'=>$data['idScheduleTemplate']);
			}
		}
		return $rows;
	}

	function getDefaultTemplate($idDisplay){
		$stmt = $this->db->prepare("select ScheduleTemplate.id, ScheduleTemplate.name from ScheduleTemplateDefault inner join ScheduleTemplate on ScheduleTemplate.id = ScheduleTemplateDefault.idScheduleTemplate and ScheduleTemplate.deleted = 0 where ScheduleTemplateDefault.idDisplay = ? and ScheduleTemplateDefault.deleted = 0");
		$stmt->execute(array($idDisplay));
		$rowTemplate = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row = array('id'=>$rowTemplate['id'],'name'=>$rowTemplate['name']);
		return $row;	
	}

	function getCurrentTemplate($idDisplay, $month, $day, $weekDay){
		/*
		Ajustael dia de la semana para que coincida con lo registrado en la tabla Weekday
		Domingo = 1
		lunes = 2 
		Martes = 3 
		Miercoles = 4 
		Jueves = 5 
		Viernes = 6 
		Sabado = 7 
		*/
		$weekDay ++;
		if ($weekDay == 8) 
			$weekDay = 1;
		$stmtId = $this->db->prepare("select ScheduleCalendar.idScheduleTemplate as scheduleCalendar, ScheduleWeek.idScheduleTemplate as scheduleWeek, ScheduleTemplateDefault.idScheduleTemplate as scheduleDefault from ScheduleTemplateDefault left join ScheduleWeek on ScheduleWeek.idDisplay = ? and ScheduleWeek.idWeekday = ? and ScheduleWeek.deleted = 0 left join ScheduleCalendar on ScheduleCalendar.idDisplay = ? and ScheduleCalendar.day = ? and ScheduleCalendar.month = ? and ScheduleCalendar.deleted = 0 where ScheduleTemplateDefault.idDisplay = ? and ScheduleTemplateDefault.deleted = 0");
		$stmtId->execute(array($idDisplay,$weekDay,$idDisplay,$day,$month,$idDisplay));
		$rowId = $stmtId->fetch(PDO::FETCH_ASSOC); 
		$idResult = $rowId['scheduleCalendar'];
		$id = -1;
		// Como primer opcion busca el template segun el calendario mensual (ScheduleCalendar)
		if ($rowId['scheduleCalendar']) 
			$id = $rowId['scheduleCalendar'];
		// Como segunda opcion busca el template segun el calendario semanal (ScheduleWeek)
		else if ($rowId['scheduleWeek']) 
			$id = $rowId['scheduleWeek'];
		// Como primer opcion busca el template default (scheduleDefault)
		else if ($rowId['scheduleDefault']) 
			$id = $rowId['scheduleDefault'];
		$stmtTemplate = $this->db->prepare("select id, name from ScheduleTemplate where id = ?");
		$stmtTemplate->execute(array($id));
		$rowTemplate = $stmtTemplate->fetch(PDO::FETCH_ASSOC); 
		$row = array('id'=>$rowTemplate['id'],'name'=>$rowTemplate['name']);
		return $row;
	}

	function getIpAndPort($id){
		$row = "";
		$stmt = $this->db->prepare("select Online.ip, Online.port from Display inner join URPDisplay on URPDisplay.idDisplay = Display.id and URPDisplay.deleted = 0 inner join URP on URP.id = URPDisplay.idURP and URP.deleted = 0 inner join Online on Online.idURP = URP.id where Display.id = ? and Display.deleted = 0");
		$stmt->execute(array($id));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		$row = array('port'=>$rowData['port'],'ip'=>$rowData['ip']);
		return $row;
	}

	function getDisplaysByCustomer($idCustomer){
		$rows = array();
		$stmt = $this->db->prepare("select Display.id, Display.name as displayName, Store.name as storeName, Zone.name as zoneName from Zone inner join Store on Store.idZone = Zone.id and Store.deleted = 0 inner join Display on Display.idStore = Store.id and Display.deleted = 0 where Zone.idCustomer = ? and Zone.deleted = 0 order by Zone.name, Store.name, Display.name");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('id'=>$row['id'],'name'=>$row['displayName'],'storeName'=>$row['storeName'],'zoneName'=>$row['zoneName']);
		}
		return $rows;
	}

	function getDisplaysFreeByCustomer($idCustomer){
		$rows = array();
		$stmt = $this->db->prepare("select Display.id as displayId, Display.name as displayName, Store.name as storeName, Zone.name as zoneName from Zone inner join Store on Store.idZone = Zone.id and Store.deleted = 0 inner join Display on Display.idStore = Store.id and Display.deleted = 0 and Display.id not in (select idDisplay from URPDisplay where deleted = 0) where Zone.idCustomer = ? and Zone.deleted = 0");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('id'=>$row['displayId'],'name'=>$row['displayName'],'storeName'=>$row['storeName'],'zoneName'=>$row['zoneName']);
		}
		return $rows;
	}

	function deleteDisplay($id){
		$stmt = $this->db->prepare("update Display set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update ScheduleTemplateDefault set deleted = 1, updateTime = now() where idDisplay = ?");
		$stmt->execute(array($id));
	}

	function getStoreByDisplay($id){
		$stmt = $this->db->prepare("select Store.id, Store.name, Zone.id as idZone, Zone.name as zoneName, Customer.id as idCustomer, Customer.name as customerName from Display inner join Store on Store.id = Display.idStore inner join Zone on Zone.id = Store.idZone inner join Customer on Customer.id = Zone.idCustomer where Display.id = ?");
		$stmt->execute(array($id));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC);
		$row = array('id'=>$rowData['id'],'name'=>$rowData['name'],'idZone'=>$rowData['idZone'],'zoneName'=>$rowData['zoneName'],'idCustomer'=>$rowData['idCustomer'],'customerName'=>$rowData['customerName']);
		return $row;
	}

	function getDisplay($id){
		$stmt = $this->db->prepare("select Display.name, Display.vertical, Display.idStore, Display.autoReboot, Display.rebootDay, Display.rebootHour, Display.rebootMinute, Display.checkTime, ScheduleTemplate.id as idScheduleTemplate, ScheduleTemplate.name as scheduleTemplateName, URP.id as idURP, URP.videoWall from Display left join ScheduleTemplateDefault on ScheduleTemplateDefault.idDisplay = Display.id and ScheduleTemplateDefault.deleted = 0 left join ScheduleTemplate on ScheduleTemplate.id = ScheduleTemplateDefault.idScheduleTemplate and ScheduleTemplate.deleted = 0 inner join URPDisplay on URPDisplay.idDisplay = Display.id and URPDisplay.deleted = 0 inner join URP on URP.id = URPDisplay.idURP and URP.deleted = 0 where Display.id = ? and Display.deleted = 0");
		$stmt->execute(array($id));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 	
		$row = array('id'=>$id,'name'=>$rowData['name'],'vertical'=>$rowData['vertical'],'idStore'=>$rowData['idStore'],'autoReboot'=>$rowData['autoReboot'],'rebootDay'=>$rowData['rebootDay'],'rebootHour'=>$rowData['rebootHour'],'rebootMinute'=>$rowData['rebootMinute'],'checkTime'=>$rowData['checkTime'],'templateDefault'=>$rowData['scheduleTemplateName'],'idTemplateDefault'=>$rowData['idScheduleTemplate'],'idURP'=>$rowData['idURP'], 'videoWall'=>$rowData['videoWall']);
		return $row;
	}

	function getNowPlaying($id){
		$stmt = $this->db->prepare("select Content.name from Display inner join URPDisplay on URPDisplay.idDisplay = Display.id and URPDisplay.deleted = 0 inner join URP on URP.id = URPDisplay.idURP and URP.deleted = 0 inner join Online on Online.idURP = URP.id inner join Content on Content.id = Online.idContent where Display.id = ? and Display.deleted = 0");
		$stmt->execute(array($id));
		$rowData = $stmt->fetch(PDO::FETCH_ASSOC); 
		return $rowData['name'];
	}

	function editDisplay($data){
		if ($data->vertical=='true')
			$intVertical = 1;
		else
			$intVertical = 0;
		if ($data->reinicio=='true')
			$intReinicio = 1;
		else
			$intReinicio = 0;
		$rebootHour = 0;
		$rebootMinute = 0;
		if (isset($data->rebootTime) && !empty($data->rebootTime)) {
			$hora = explode(":",$data->rebootTime);
			$rebootHour = $hora[0];
			$rebootMinute = $hora[1];
		} else {
			$rebootHour = 0;
			$rebootMinute = 0;
		}
		$stmt = $this->db->prepare("update Display set name = ?, vertical = ?, autoReboot = ?, rebootDay = ?, rebootHour = ?, rebootMinute = ?, checkTime = ? where id = ?");
		return $stmt->execute(array($data->name,$intVertical,$intReinicio,$data->selectDays,$rebootHour,$rebootMinute,$data->checkTime,$data->id));		
	}

	function editTemplateDefault($idTemplate,$idDisplay){
		$stmtCheck = $this->db->prepare("select id from ScheduleTemplateDefault where idDisplay = ? and deleted = 0");
		$stmtCheck->execute(array($idDisplay));
		if ($stmtCheck->rowCount($stmtCheck)>0) {
			$stmt = $this->db->prepare("update ScheduleTemplateDefault set idScheduleTemplate = ?, updateTime = now() where idDisplay = ?");
			$stmt->execute(array($idTemplate,$idDisplay));		
		} else {
			$stmt = $this->db->prepare("insert into ScheduleTemplateDefault (idScheduleTemplate, idDisplay, createTime, updateTime) values (?,?,now(),now())");
			$stmt->execute(array($idTemplate,$idDisplay));	
		}
	}

	function remoteRestart($idDisplay){	
		$stmt = $this->db->prepare("update Display set reboot = 1, updateTime = now() where id = ?");
		$stmt->execute(array($idDisplay));	
	}

	function remoteBackground($idDisplay){	
		$stmt = $this->db->prepare("update Display set background = 1, updateTime = now() where id = ?");
		$stmt->execute(array($idDisplay));	
	}

	function newDisplay($data){
		if ($data['vertical']=="true")
			$vertical = 1;	
		else
			$vertical = 0;
		$reinicio = 0;
		$selectDays = 0;
		$rebootHour = 0; 
		$rebootMinute = 0;
		if (isset($data['reinicio']) and $data['reinicio']=="true") { 
			$reinicio = 1;
			$selectDays = $data['selectDays'];
			if ($data['rebootTime']<>"") {
				$rebootTime = explode(":",$data['rebootTime']); 
				$rebootHour = $rebootTime[0]; 
				$rebootMinute = $rebootTime[1]; 	
			}
		}
		$stmt = $this->db->prepare("insert into Display (name, idStore, vertical, autoReboot, rebootDay, rebootHour, rebootMinute, checkTime, createTime, updateTime) values (?,?,?,?,?,?,?,?,now(),now())");
		$stmt->execute(array($data['name'],$data['idStore'],$vertical,$reinicio,$selectDays,$rebootHour,$rebootMinute,$data['checkTime']));
		$idDisplay = $this->db->lastInsertId();
		if(isset($data['idTemplate']) and $data['idTemplate'] <> "" ){
			$stmt = $this->db->prepare("insert into ScheduleTemplateDefault (idScheduleTemplate, idDisplay, createTime, updateTime) values (?,?,now(),now())");
			$stmt->execute(array($data['idTemplate'],$idDisplay));				
		}
	}

	function getAgendaSemanal($id){
		$rows = array();
		$stmt = $this->db->prepare("select Weekday.id, ScheduleWeek.idScheduleTemplate from Weekday left join ScheduleWeek on ScheduleWeek.idWeekday = Weekday.id and ScheduleWeek.idDisplay = ? and ScheduleWeek.deleted = 0 order by Weekday.id");
		$stmt->execute(array($id));
		$rowData = $stmt->fetchAll(); 
		if (!empty($rowData)) {
			foreach ($rowData as $data) {	
				$rows[] = array('idWeekday'=>$data['id'],'idScheduleTemplate'=>$data['idScheduleTemplate']);
			}
		}
		return $rows;
	}

	function editAgendaSemanal($data){	
		$stmtCheck = $this->db->prepare("select id from ScheduleWeek where idDisplay = ? and idWeekday = ? and deleted = 0");
		$stmtCheck->execute(array($data['idDisplay'],$data['idWeekday']));
		if ($stmtCheck->rowCount($stmtCheck)>0) {
			$stmt = $this->db->prepare("update ScheduleWeek set idScheduleTemplate = ?, updateTime = now() where idDisplay = ? and idWeekday = ?");
			$stmt->execute(array($data['idTemplate'],$data['idDisplay'], $data['idWeekday']));		
		} else {
			$stmt = $this->db->prepare("insert into ScheduleWeek (idDisplay, idWeekday, idScheduleTemplate, createTime, updateTime) values (?,?,?,now(),now())");
			$stmt->execute(array($data['idDisplay'], $data['idWeekday'], $data['idTemplate']));	
		}	
	}

	function deleteAgendaSemanal($data){
		$stmt = $this->db->prepare("update ScheduleWeek set deleted = 1, updateTime = now() where idDisplay = ? and idWeekday = ?");
		$stmt->execute(array($data['idDisplay'],$data['idWeekday']));
	}

	function deleteScheduleWeekByDisplay($idDisplay){
		$stmt = $this->db->prepare("update ScheduleWeek set deleted = 1, updateTime = now() where idDisplay = ? and deleted = 0");
		$stmt->execute(array($idDisplay));
	}

	function getDisplaysWithScheduleWeek($idCustomer){
		$rows="";
		$stmt = $this->db->prepare("select Display.id, Zone.name as zoneName, Store.name as storeName, Display.name as displayName from Zone inner join Store on Store.idZone = Zone.id and Store.deleted = 0 inner join Display on Display.idStore = Store.id and Display.deleted = 0 inner join ScheduleWeek on ScheduleWeek.idDisplay = Display.id and ScheduleWeek.deleted = 0 where Zone.idCustomer = ? and Zone.deleted = 0 group by Display.id order by Zone.name, Store.name, Display.name");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = array('id'=>$row['id'],'name'=>$row['zoneName'].' / '.$row['storeName'].' / '.$row['displayName']);
		}
		return $rows;
	}

}