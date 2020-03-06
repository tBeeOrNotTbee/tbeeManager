<?php 

class calendar extends Controller_Abstract{

	public function __construct(){
		parent::__construct();
		$this->calendar = new Model_Calendar;
		$this->display = new Model_Display;	
		$this->schedule = new Model_Schedule;
	}

	public function viewCalendar($parameters){
	    $arrDaysWithTemplate = json_encode($this->calendar->getAllDaysWithTemplate($_SESSION['idCustomer'])); 
		$data['main_view'] = APPPATH.'views/calendar/viewCalendar.php';
		require_once(APPPATH."views/layout.php");			
	}
	
	public function viewCalendarDay($parameters){
		$date = explode("-",$parameters['parameters']);
		$calendar_data['day'] =  $date[0];
		$calendar_data['month'] = $date[1];	
		$calendar_data['idCustomer'] = $_SESSION['idCustomer'];
		if (isset($_POST['display']) and !empty($_POST['display'])) {	
			$calendar_data['display'] = $_POST['display'];	
			$calendar_data['template'] = $_POST['template'];			
			$this->calendar->newCalendar($calendar_data);				
		}	
		$breadcrumb = array("Calendario",$parameters['parameters']); 	
		$select_content['displays'] = $this->display->getDisplaysByCustomer($calendar_data['idCustomer']);	
		$select_content['templates'] = $this->schedule->getSchedulesByCustomer($calendar_data['idCustomer']);	
		$data['contents'] = $this->calendar->getCalendarByDate($calendar_data['idCustomer'],$calendar_data['day'],$calendar_data['month']);
		$arrDaysWithTemplate = json_encode($this->calendar->getAllDaysWithTemplate($calendar_data['idCustomer'])); 
		$data['main_view'] = APPPATH.'views/calendar/viewCalendarDay.php';
		require_once(APPPATH."views/layout.php");
	}	
	
	public function deleteScheduleCalendar($parameters){
		$this->calendar->deleteCalendar($_POST['id']);		
	}

	public function deleteScheduleCalendarDate($parameters){
		$date = explode("-",$_POST['date']);
		$this->calendar->deleteCalendarByDate($_SESSION['idCustomer'],$date[0],$date[1]);
	}
	
	public function editScheduleCalendar($parameters){
		$this->calendar->editCalendar($_POST['idTemplate'],$_POST['idDisplay'],$_POST['day'],$_POST['month'],$_POST['id']);
	}

	public function copyScheduleCalendar($parameters){
		$date = explode("-",$_POST['selectedDate']);
		$selected_date['day'] =  $date[0];
		$selected_date['month'] = $date[1];
		$date = explode("-",$_POST['copyDate']);
		$copy_date['day'] =  $date[0];
		$copy_date['month'] = $date[1];
		$this->calendar->deleteCalendarByDate($_SESSION['idCustomer'],$selected_date['day'],$selected_date['month']);
		$calendar_copy = $this->calendar->getCalendarByDate($_SESSION['idCustomer'],$copy_date['day'],$copy_date['month']);
		foreach ($calendar_copy as $calendar) {
			$calendar_data['day'] =  $selected_date['day'];
			$calendar_data['month'] = $selected_date['month'];	
			$calendar_data['idCustomer'] = $_SESSION['idCustomer'];
			$calendar_data['display'] = array($calendar['idDisplay']);	
			$calendar_data['template'] = $calendar['idScheduleTemplate'];
			$this->calendar->newCalendar($calendar_data);
		}
	}
	
}