<?php

abstract class Model_Abstract {

	protected $_connection = null;
		
	public function getConnection() {
    	if (!$this->_connection) {
			require(APPPATH . "config/database.php");			
	        $this->_connection = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
			$settime = $this->_connection->query("SET time_zone='-3:00'");
			$settime->execute();
			$this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $this->_connection;
	}	
	
}
