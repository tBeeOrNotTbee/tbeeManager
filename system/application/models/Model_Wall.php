<?php

class Model_Wall extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function getWallURPsByMaster($idURP){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select idSlave from Wall where idURP = ?");
		$stmt->execute(array($idURP));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] =array('idSlave'=>$row['idSlave']);
		}
		return $rows;
	}

	function getWallDataByMaster($idURP){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select WallOnline.idSlave, WallOnline.uptime, WallOnline.lastUpdate, WallOnline.ip, WallOnline.port, WallOnline.freeSpace, WallOnline.temperature, WallOnline.memPercentUsed, WallOnline.cpuUsage, WallOnline.WALLVersion from Wall inner join WallOnline on WallOnline.idSlave = Wall.idSlave where Wall.idURP = ?");
		$stmt->execute(array($idURP));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
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
			$rows[] =array('idSlave'=>$row['idSlave'],'uptime'=>$uptime,'lastUpdate'=>$lastUpdate,'minutes'=>$minutos,'ip'=>$row['ip'],'port'=>$row['port'],'freeSpace'=>$row['freeSpace'],'temperature'=>$temperature,'memPercentUsed'=>$row['memPercentUsed'],'cpuUsage'=>$row['cpuUsage'],'WALLVersion'=>$row['WALLVersion']);
		}
		return $rows;
	}

}