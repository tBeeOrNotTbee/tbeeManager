<?php

class Model_Calendar extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function newCalendar($data){
		foreach ($data['display'] as $display) {
			$stmt = $this->db->prepare("insert into ScheduleCalendar (idCustomer, idScheduleTemplate, idDisplay, day, month, createTime, updateTime) values (?,?,?,?,?,now(),now())");
			$stmt->execute(array($data['idCustomer'],$data['template'],$display,$data['day'],$data['month']));		
		}	
	}

	function editCalendar($idTemplate,$idDisplay,$day,$month,$id){
		$stmt = $this->db->prepare("update ScheduleCalendar set idScheduleTemplate = ?, idDisplay = ?, day = ?, month = ? where id = ?");
		$stmt->execute(array($idTemplate,$idDisplay,$day,$month,$id));
	}	

	function deleteCalendar($id){
		$stmt = $this->db->prepare("update ScheduleCalendar set deleted = 1, updateTime = now() where id = ?");
		$stmt->execute(array($id));
	}

	function deleteCalendarByDisplay($id){
		$stmt = $this->db->prepare("update ScheduleCalendar set deleted = 1, updateTime = now() where idDisplay = ?");
		$stmt->execute(array($id));
	}

	function deleteCalendarByDate($idCustomer,$day,$month){
		$stmt = $this->db->prepare("update ScheduleCalendar set deleted = 1, updateTime = now() where idCustomer = ? and day = ? and month = ? and deleted = 0");
		$stmt->execute(array($idCustomer,$day,$month));
	}

	function getCalendarByDate($idCustomer,$day,$month){
		$rows = array();
		$stmt = $this->db->prepare("select ScheduleCalendar.id, ScheduleCalendar.idScheduleTemplate, ScheduleCalendar.idDisplay, ScheduleTemplate.name as templateName, Display.name as displayName from ScheduleCalendar inner join ScheduleTemplate on ScheduleTemplate.id = ScheduleCalendar.idScheduleTemplate inner join Display on Display.id = ScheduleCalendar.idDisplay where ScheduleCalendar.idCustomer = ? and ScheduleCalendar.day = ? and ScheduleCalendar.month = ? and ScheduleCalendar.deleted = 0");
		$stmt->execute(array($idCustomer,$day,$month));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {		
			$rows[] =array('id'=>$row['id'],'idScheduleTemplate'=>$row['idScheduleTemplate'],'idDisplay'=>$row['idDisplay'],
			'nameScheduleTemplate'=>$row['templateName'],'nameDisplay'=>$row['displayName']);
		}
		return $rows;
	}

	function getAllDaysWithTemplate($idCustomer){
		$rows = array();
		$stmt = $this->db->prepare("select day, month, CONCAT(day,'-',month) as fecha from ScheduleCalendar where idCustomer = ? and day IS NOT NULL and month IS NOT NULL and deleted = 0 group by fecha order by month, day");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] = str_pad($row['day'], 2, "0", STR_PAD_LEFT)."-".str_pad($row['month'], 2, "0", STR_PAD_LEFT);
		}
		return $rows;
	}

}