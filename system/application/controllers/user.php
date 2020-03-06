<?php 
class user extends Controller_Abstract{

	public function __construct(){			
		parent::__construct();
		$this->user = new Model_User;
	}

	public function login($login_error = false){
		$data['login_error'] = $login_error;
		include(APPPATH."views/user/login.php");
	}

	public function account($parameters){
		$id = $_SESSION['idUser'];			
		if (isset($parameters['parameters']) and !empty($parameters['parameters']))		
			$id = $parameters['parameters'];
		$validate = false;
		if (($_SESSION['idRole']==ROLE_ADMIN) || ($_SESSION['idRole']==ROLE_MONITOR)){
			$validate = true;
		}
		else if ($_SESSION['idRole']==ROLE_CUSTOMER_ADMIN){
			$customer = $this->user->getCustomerByUser($id);
			if ($customer['id']==$_SESSION['idCustomer'])
				$validate = true;
		}
		else {
			if ($id == $_SESSION['idUser'])
				$validate = true;
		}
		if (isset($_POST['password'])) {
			include_once(APPPATH.'helpers/PassHash.php');
			$pass = new PassHash;
			$hashedPass = "";
			if ($_POST['password'] <> "")
				$hashedPass = $pass->hash($_POST['password']);
			$this->user->editUser($id,$_POST['email'], $hashedPass);
			$data['alert'] = true;			
		}		
		$data['userData'] = $this->user->getUser($id);
		$breadcrumb = array("Cuenta de usuario"); 
		$data['main_view'] = APPPATH.'views/user/account.php';
		require_once(APPPATH."views/layout.php");
	}
	
}