<?php

	require_once("Libraries/Core/Mysql2.php");

	class EnterpriseModel extends Mysql
	{	


		public function __construct()
		{
			parent::__construct();
			
		}
	
		public function getEnterprises()
		{
			$sql = "SELECT * FROM empresa";

			$request = $this->select_all($sql);

			return $request;
		}

		public function getEnterprise($id)
		{
			$sql = "SELECT * FROM empresa WHERE id = $id";
			$request = $this->select($sql);
			return $request;
		}

		public function updateEnterprise($id, $name, $bd, $rif, $token)
		{	
			$sql = "UPDATE empresa SET `name` = ?, bd = ?, rif = ?, token = ?
					WHERE id = ?";
			$valueArray = array($name, $bd, $rif, $token, $id);
			$request = $this->update($sql, $valueArray);
			return $request;
		}

		public function deleteEnterprise($id)
		{	
			$selectSql = "SELECT * FROM empresa WHERE id = $id";
			$request = $this->select($selectSql);

			if($request['status'] == 1){
				$status = 0;
			}else{
				$status = 1;
			}

			$sql = "UPDATE empresa SET status = ? WHERE id = ?";
			$valueArray = array($status, $id);
			$request = $this->update($sql, $valueArray);

			if($request){
				if($status == 1){
					$response = 'activado';
				}else{
					$response = 'desactivado';
				}
				return $response;
			}else{
				return $request;
			}
			
		}

	}	


?>
