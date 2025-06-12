<?php 

	class UsuarioModel extends Mysql
	{	


		public function __construct()
		{
			parent::__construct();
		}
		
		public function getUsuarios(){

			$sql = "SELECT * FROM usuario";
			$request = $this->select_all($sql);

			return $request;
		}

        public function infoUsuario($id)
        {
            $sql = "SELECT * FROM usuario WHERE ID = '$id'";
			$request = $this->select($sql);

			return $request;
        }

		public function updateUsuario($id, $nombre, $username)
		{
			$sql = "UPDATE usuario SET NOMBRE = ?, USUARIO = ? WHERE ID = '$id'";
			$valueArray = array($nombre, $username);
			$request = $this->update($sql, $valueArray);

			return $request;
		}

		public function updatePassword($id, $password)
		{
			$hashedPassword = hash('SHA256', $password);

			$sql = "UPDATE usuario SET `CLAVE` = ? WHERE ID = '$id'";
			$valueArray = array($hashedPassword);
			$request = $this->update($sql, $valueArray);

			return $request;
		}


	}	


 ?>