<?php 

class Enterprise extends Controllers{

	public function __construct()
	{	
		parent::__construct();
		if(empty($_SESSION['login']))
		{	
			header('Location: '.base_url().'/login');
			exit();
		}
	}

	public function enterprise()
	{	
		$data['page_functions_js'] = "functions_enterprise.js";
		$this->views->getView($this,"enterprise", $data);
	}

	public function getEnterprises()
	{	
		$banks = $this->model->getEnterprises();
		echo json_encode($banks,JSON_UNESCAPED_UNICODE);
	}

	public function edit($id)
	{	
		$data['page_functions_js'] = "functions_enterprise.js";
		$data['enterprise'] = $this->model->getEnterprise($id);
		$this->views->getView($this, "edit", $data);
	}

	public function updateEnterprise()
	{	
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			return $this->jsonResponse(false, 'Método no permitido');
		}
		$id = $_POST['id'] ?? null;
		$name = $_POST['name'] ?? null;
		$bd = $_POST['bd'] ?? null;
		$rif = $_POST['rif'] ?? null;
		$token = $_POST['token'] ?? null;

		$response = $this->model->updateEnterprise($id, $name, $bd, $rif, $token);
		if($response){
			echo json_encode([
				'status' => true,
				'message' => 'La empresa se ha actualizado de manera correcta.'
			]);
			die();
		}else{
			echo json_encode([
				'status' => false,
				'message' => 'No se pudo actualizar la empresa.'
			]);
			die();
		}
	}

	public function deleteEnterprise($id)
	{		
		$response = $this->model->deleteEnterprise($id);
		if($response){
			echo json_encode([
				'status' => true,
				'message' => 'La empresa se '.$response.' de manera exitosa'
			]);
			die();
		}else{
			echo json_encode([
				'status' => false,
				'message' => 'No se pudo desactivar/activar la empresa.'
			]);
			die();
		}
		
	}
	
}
