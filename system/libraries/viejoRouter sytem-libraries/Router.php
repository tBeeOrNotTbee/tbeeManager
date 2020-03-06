<?php 
//echo "llego hasta aca";
class Router{

	function getParameters($Request_Uri){
		$controller = "";
		$action = "";
		$parametersInput = "";
		$parametersInput2 = "";	
		$parametersInput3 = "";	
		$entry = explode(BASE_FOLDER, $Request_Uri);
		if (isset($entry[1]))
			$parameters = explode("/", $entry[1]);
		if (isset($parameters[0]))
			$controller = $parameters[0];
		if (isset($parameters[1]))
			$action = $parameters[1];
		if (isset($parameters[2]))
			$parametersInput = $parameters[2];
		if (isset($parameters[3]))
			$parametersInput2 = $parameters[3];
		if (isset($parameters[4]))
			$parametersInput3 = $parameters[4];
		$result = array('controller'=>$controller,'action'=>$action,'parameters'=>$parametersInput,'parameters2'=>$parametersInput2,'parameters3'=>$parametersInput3);
		return $result;
	}
	
	function getAccess($controller){
		$PermisosAdmin = array("admin","user","store","display","zone","schedule","online");
		$PermisosCustomerAdmin = array("customer","user","display","zone","store","schedule","calendar","content","menu","lista","trivia","playlist","online");
		$PermisosStoreAdmin = array("display","user");
		$PermisosContentManager = array("content","menu","user","lista","trivia","playlist","schedule");
		$PermisosMonitor = array("online","user");
		switch ($_SESSION['idRole']) {
			case ROLE_ADMIN:	
				if (!in_array($controller,$PermisosAdmin))
					die("No tiene acceso permitido");	
			break;
			case ROLE_CUSTOMER_ADMIN:
				if (!in_array($controller,$PermisosCustomerAdmin))
					die("No tiene acceso permitido");
			break;
			case ROLE_STORE_ADMIN:
				if (!in_array($controller,$PermisosStoreAdmin))
					die("No tiene acceso permitido");
			break;
			case ROLE_CONTENT_MANAGER:
				if (!in_array($controller,$PermisosContentManager))
					die("No tiene acceso permitido");
			break;
			case ROLE_MONITOR:
				if (!in_array($controller,$PermisosMonitor))
					die("No tiene acceso permitido");
			break;
		}
	}

}