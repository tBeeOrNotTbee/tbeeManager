<?php

class Model_Online extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function getAdminInfo($idCustomer=NULL){
		$rows=array();
		if (is_null($idCustomer)) {
			$stmt = $this->db->prepare("select Display.id as idDisplay, Customer.name as CustomerName, Zone.name as ZoneName, Store.name as StoreName, Display.name as DisplayName, Online.uptime, Online.lastUpdate, Content.name as ContentName, Content.version as ContentVersion, Online.ip, Online.port, Online.freeSpace, Online.temperature, Online.URPType, Online.URPVersion, Online.memPercentUsed, Online.cpuUsage from Online left join Content on Online.idContent = Content.id left join Display on Online.idDisplay = Display.id left join Store on Display.idStore = Store.id left join Zone on Store.idZone = Zone.id left join Customer on Zone.idCustomer = Customer.id order by Customer.name, Zone.name, Store.name, Display.name");
			$stmt->execute();
		} else {
			$stmt = $this->db->prepare("select Display.id as idDisplay, Customer.name as CustomerName, Zone.name as ZoneName, Store.name as StoreName, Display.name as DisplayName, Online.uptime, Online.lastUpdate, Content.name as ContentName, Content.version as ContentVersion, Online.ip, Online.port, Online.freeSpace, Online.temperature, Online.URPType, Online.URPVersion, Online.memPercentUsed, Online.cpuUsage from Customer left join Zone on Zone.idCustomer = Customer.id left join Store on Store.idZone = Zone.id left join Display on Display.idStore = Store.id right join Online on Online.idDisplay = Display.id left join Content on Online.idContent = Content.id where Customer.id = ? order by Customer.name, Zone.name, Store.name, Display.name");
			$stmt->execute(array($idCustomer));
		}
		$rowData = $stmt->fetchAll();
		foreach($rowData as $row) {		
			$date = new DateTime($row['lastUpdate']);
			$now = new DateTime();
			$diferencia = $date->diff($now);
			$minutos = $diferencia->days * 24 * 60;
			$minutos += $diferencia->h * 60;
			$minutos += $diferencia->i;			
			if (!$row['lastUpdate'])
				$lastUpdate = "";
			else
				$lastUpdate = date('d-m H:i',strtotime($row['lastUpdate']));
			if (!$row['uptime'])
				$uptime = "";
			else {				
				$date = new DateTime('@'.$row['uptime']);
				$epoch = new DateTime("@0");
				$diff = $date->diff($epoch);
				if ($diff->days ==0)
					$uptime = gmdate("H:i:s",$row['uptime']);
				else if($diff->days==1)
					$uptime = $diff->days . " día " . gmdate("H:i:s",$row['uptime']);
				else
					$uptime = $diff->days . " días " . gmdate("H:i:s",$row['uptime']);
			}
			if (isset($row['temperature'])){
				if ($row['temperature']!=0)		
					$temperature = round($row['temperature'],1) ."°";
				else
					$temperature = '-';
			}
			$rows[] =array('CustomerName'=>$row['CustomerName'],'ZoneName'=>$row['ZoneName'],'StoreName'=>$row['StoreName'], 'DisplayName'=>$row['DisplayName'],'idDisplay'=>$row['idDisplay'],'uptime'=>$uptime,'lastUpdate'=>$lastUpdate,'temperature'=>$temperature ,'ip'=>$row['ip'],'port'=>$row['port'],'freeSpace'=>$row['freeSpace'],'URPType'=>$row['URPType'],'URPVersion'=>$row['URPVersion'],'minutes'=>$minutos,'ContentName'=>$row['ContentName'],'ContentVersion'=>$row['ContentVersion'],'memPercentUsed'=>$row['memPercentUsed'],'cpuUsage'=>$row['cpuUsage']);
		}
		return $rows;
	}

	function getCustomerAdminInfo($idCustomer){
		$rows=array();
		$stmt = $this->db->prepare("select Customer.id as idCustomer, Zone.id as idZone, Zone.name as ZoneName, Store.id as idStore, Store.name as StoreName, Display.id as idDisplay, Display.name as DisplayName, Online.idURP, Online.lastUpdate, Online.freeSpace, ScheduleTemplate.name as scheduleTemplate, Content.name as ContentName, Content.version as ContentVersion from Online left join Content on Online.idContent = Content.id inner join Display on Online.idDisplay = Display.id and Display.deleted = 0 left join ScheduleTemplate on ScheduleTemplate.id = Online.idScheduleTemplate and ScheduleTemplate.deleted = 0 inner join Store on Display.idStore = Store.id and Store.deleted = 0 inner join Zone on Store.idZone = Zone.id and Zone.deleted = 0 inner join Customer on Zone.idCustomer = Customer.id and Customer.id = ? order by Zone.name, Store.name, Display.name");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach($rowData as $row){
			$date = new DateTime($row['lastUpdate']);
			$now = new DateTime();
			$diferencia = $date->diff($now);
			$minutos = $diferencia->days * 24 * 60;
			$minutos += $diferencia->h * 60;
			$minutos += $diferencia->i;		
			if (!$row['lastUpdate'])
				$lastUpdate = "";
			else
				$lastUpdate = date('d-m H:i',strtotime($row['lastUpdate']));
			$rows[] =array('ZoneName'=>$row['ZoneName'],'StoreName'=>$row['StoreName'],'DisplayName'=>$row['DisplayName'],'idDisplay'=>$row['idDisplay'],'lastUpdate'=>$lastUpdate,'freeSpace'=>$row['freeSpace'],'scheduleTemplate'=>$row['scheduleTemplate'],'minutes'=>$minutos,'ContentName'=>$row['ContentName'],'ContentVersion'=>$row['ContentVersion']);
		}
		return $rows;
	}

}