<?php 

class FrontController{

	public function application(){		
		//login
		if (!isset($_SESSION))
			session_start();	
		if (!isset($_SESSION['isLogged'])) {			
			include(APPPATH.'controllers/user.php');
			if (!isset($_POST['email'])) {
					$user = new user;
					$user->login();
					exit();
			} else {
				$model_user = new Model_User();
				if (!($model_user->loginUser($_POST['email'],$_POST['password']))) {
					$user = new user;
					$user->login(true);
					exit();			
				} 
			}	
		}
		//router
		$router = new Router;
		$parameters = $router->getParameters($_SERVER['REQUEST_URI']);
		$controllerName = $parameters['controller'];
		$action = $parameters['action'];
		$param = $parameters['parameters'];
		function logout(){			
			session_destroy();
			header("Location:".FRONT_CONTROLLER);
			die();
		} 
		if ($action == 'logout')
			logout();
		if ($controllerName == "") {
			//Por rol
			switch ($_SESSION['idRole']) {
				case ROLE_ADMIN:	
					$controllerName = "admin";	
				break;
				case ROLE_CUSTOMER_ADMIN:
					$controllerName = "customer";				
				break;
				case ROLE_STORE_ADMIN:
					$controllerName = "display";	
				break;
				case ROLE_CONTENT_MANAGER:
					$controllerName = "content";	
				break;
				case ROLE_MONITOR:
					$controllerName = "online";	
				break;
				default:
					echo 'Unknown user type!';
				break;
			} 
		}
	//verifica el tipo de usuario con la peticion realizada
	$router->getAccess($controllerName);
	if ($action == "")				
		$action = "index";
	$controller = new $controllerName;
	$controller->$action($parameters); 
	}
	
}