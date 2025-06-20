<?php

class Conexion2{
	private $conect;

	public function __construct(){
		$connectionString = "mysql:host=".DB_HOST_BANKING.";dbname=".DB_NAME_BANKING.";charset=".DB_CHARSET_BANKING;
		try{
			$this->conect = new PDO($connectionString, DB_USER_BANKING, DB_PASSWORD_BANKING);
			$this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    //echo "conexión exitosa";
		}catch(PDOException $e){
			$this->conect = 'Error de conexión';
		    echo "ERROR: " . $e->getMessage();
		}
	}

	public function conect(){
		return $this->conect;
	}
}

?>