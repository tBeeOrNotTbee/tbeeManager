<?php

class Model_User extends Model_Abstract {

	function __construct(){
		$this->db = $this->getConnection();
	}

	function getUsers(){
		$stmt = $this->db->prepare("select User.id, User.email, Role.id as idRole, Role.name as roleName from User inner join UserRole on UserRole.idUser = User.id and UserRole.deleted = 0 inner Join Role on Role.id = UserRole.idRole and Role.deleted = 0 where User.deleted = 0");
		$stmt->execute();
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] =array('id'=>$row['id'],'email'=>$row['email'],'idRole'=>$row['idRole'],'roleName'=>$row['roleName']);
		}
		return $rows;
	}

	function getUsersByCustomer($idCustomer){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select User.id, User.email, Role.id as idRole, Role.name as roleName from UserCustomer inner join User on User.id = UserCustomer.idUser and User.deleted = 0 inner join UserRole on UserRole.idUser = User.id and UserRole.deleted = 0 inner Join Role on Role.id = UserRole.idRole and Role.deleted = 0 where UserCustomer.idCustomer = ? and UserCustomer.deleted = 0");
		$stmt->execute(array($idCustomer));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] =array('id'=>$row['id'],'email'=>$row['email'],'idRole'=>$row['idRole'],'roleName'=>$row['roleName']);
		}
		return $rows;
	}

	function getUsersByRole($idRole){
		//$rows = "";
		$rows = array();
		$stmt = $this->db->prepare("select User.id, User.email, Role.name as roleName from UserRole inner join User on User.id = UserRole.idUser and User.deleted = 0 inner Join Role on Role.id = UserRole.idRole and Role.deleted = 0 where UserRole.idRole = ?");
		$stmt->execute(array($idRole));
		$rowData = $stmt->fetchAll(); 
		foreach ($rowData as $row) {
			$rows[] =array('id'=>$row['id'],'email'=>$row['email'],'idRole'=>$idRole,'roleName'=>$row['roleName']);
		}
		return $rows;
	}

	function getStoreByUser($idUser){
		$stmt = $this->db->prepare("select idStore from UserStore where idUser = ?");
		$stmt->execute(array($idUser));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['idStore'];
	}

	function deleteUser($id){
		$stmt = $this->db->prepare("update User set deleted = 1, updateTime = now() where id = ?");
	 	$stmt->execute(array($id));
		$stmt = $this->db->prepare("update UserCustomer set deleted = 1, updateTime = now() where idUser = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update UserRole set deleted = 1, updateTime = now() where idUser = ?");
		$stmt->execute(array($id));
		$stmt = $this->db->prepare("update UserStore set deleted = 1, updateTime = now() where idUser = ?");
		return $stmt->execute(array($id));
	}

	function newUser($data){
		$email = $data['email'];
		include_once(APPPATH.'helpers/PassHash.php');
		//Instantiate the class
		$pass = new PassHash;
		$password = $pass->hash($data['password']);
		$stmt = $this->db->prepare("insert into User (email, password, createTime, updateTime) values (?,?,now(),now())");
		$stmt->execute(array($email, $password));
		$idUser = $this->db->lastInsertId();
		$stmt = $this->db->prepare("insert into UserRole (idUser, idRole, createTime, updateTime) values (?,?,now(),now())");
		$stmt->execute(array($idUser, $data['role']));
		if (isset($data['idCustomer']) and !empty($data['idCustomer'])) {
			$stmt = $this->db->prepare("insert into UserCustomer (idUser, idCustomer, createTime, updateTime) values (?,?,now(),now())");
			$stmt->execute(array($idUser, $data['idCustomer']));	
			if (isset($data['idStore']) and !empty($data['idStore'])) {
				$stmt = $this->db->prepare("insert into UserStore (idUser, idStore, createTime, updateTime) values (?,?,now(),now())");
				$stmt->execute(array($idUser, $data['idStore']));	
			}	
		}
	}

	function editUser($id, $email, $password){
		if ($password <> "") {
			$stmt = $this->db->prepare("update User set email = ?, password = ?, updateTime = now() where id = ?");
			$stmt->execute(array($email,$password,$id));		
		} else {
			$stmt = $this->db->prepare("update User set email = ?, updateTime = now() where id = ?");
			$stmt->execute(array($email,$id));		
		}		
	}

	function getUser($id){
		$stmtSelect =  $this->db->prepare("select id, email from User where id = ? LIMIT 1");
		$stmtSelect->execute(array($id));
		$row = $stmtSelect->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	function getCustomerByUser($id){
		$stmtSelect =  $this->db->prepare("select Customer.id, Customer.name from UserCustomer left join Customer on Customer.id = UserCustomer.idCustomer where UserCustomer.idUser = ?");
		$stmtSelect->execute(array($id));
		$row = $stmtSelect->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	function loginUser($email,$password){
		include_once(APPPATH.'helpers/PassHash.php');
		//Instantiate the class
		$pass = new PassHash;
		//busca en la tabla el usuario que coincida con el ingresado
		$stmt = $this->db->prepare("select User.id, User.password, UserCustomer.idCustomer, UserRole.idRole, Role.name as roleName from User left join UserCustomer on UserCustomer.idUser = User.id and UserCustomer.deleted = 0 inner join UserRole on UserRole.idUser = User.id and UserRole.deleted = 0 inner join Role on Role.id = UserRole.idRole and Role.deleted = 0 where User.email = ? and User.deleted = 0 limit 1");
		$stmt->execute(array($email));
		$rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!empty($rowUser)) {
			//compara usuario y contraseña
			if ($pass->check_password($rowUser['password'],$password)) {
				$_SESSION['isLogged'] = true;
				$_SESSION['idUser'] = $rowUser['id'];
				$_SESSION['email'] = $email;
				if ($rowUser['idCustomer'] == NULL)	
					$_SESSION['idCustomer'] = 0;
				else
					$_SESSION['idCustomer'] = $rowUser['idCustomer'];
				$_SESSION['idRole'] = $rowUser['idRole'];	
				$_SESSION['roleName'] = $rowUser['roleName'];	
				return true;
			} else {
				$stmt = $this->db->prepare("insert into AuditLogin (email, idStatus, date) values (?,?,now())");
				$stmt->execute(array($email,STATUS_WRONG_PASSWORD));
				return false;
			}	
		} else {
			$stmt = $this->db->prepare("insert into AuditLogin (email, idStatus, date) values (?,?,now())");
			$stmt->execute(array($email,STATUS_WRONG_EMAIL));
			return false;
		}
	}

}