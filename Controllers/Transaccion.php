<?php 
	include dirname(__DIR__) . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class Transaccion extends Controllers{

	public function __construct()
	{	
	
		parent::__construct();

		$method = $_GET['url'] ?? ''; // obtener método actual
		if($method !== 'transaccion/getTransaction' && empty($_SESSION['login'])) {	
			header('Location: '.base_url().'/login');
			exit();
		}
	}


	//FUNCIONES PARA SUERVISOR

	public function transaccion()
	{	
		$data['page_functions_js'] = "functions_transaction.js";
		$this->views->getView($this,"transaccion", $data);
	}

	public function getMovimientos()
	{
		$filters = [
			'bank'     => $_GET['bank']     ?? '',
			'account'  => $_GET['account']  ?? '',
			'reference'=> $_GET['reference']?? '',
			'date'     => $_GET['date']     ?? '',
		];

		$arrData = $this->model->getTransaction($filters);
		echo json_encode(['data' => $arrData], JSON_UNESCAPED_UNICODE);
		die();
	}

	public function newTransaction()
	{	
		$data['page_functions_js'] = "functions_transaction.js";
		$data['years'] = range(2022, 2025);
		$data['months'] = [
			'Enero', 'Febrero', 'Marzo', 'Abril',
			'Mayo', 'Junio', 'Julio', 'Agosto',
			'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
		];

		$data['currentYear'] = date('Y');
    	$data['currentMonth'] = date('n');

		$data['bank'] = $this->model->getBank($_SESSION['userData']['id_enterprise']);

		$this->views->getView($this,"new", $data);
	}
	
	public function setTransaction()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$arrResponse = array('status' => false, 'msg' => 'Método no permitido.' );
		}

		$anio = $_POST['anio'] ?? null;
		$mes = $_POST['mes'] ?? null;
		$banco = $_POST['banco'] ?? null;
		$archivo = $_FILES['archive'] ?? null;
		// Validar archivo
		
		if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
			$tmpName = $archivo['tmp_name'];
			$fileName = uniqid('F') . '' . basename($archivo['name']);
			$uploadDir = dirname(__DIR__, 1) . '/';
			$uploadPath = $uploadDir . $fileName;
			// Crear carpeta si no existe
			if (!file_exists($uploadDir)) {
				mkdir($uploadDir, 0777, true);
			}
			
			$fileExt = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

			if (!in_array($fileExt, ['pdf', 'xls', 'xlsx', 'txt'])) {
				$arrResponse = array('status' => true, 'msg' => 'Formato de archivo no soportado.' );
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				die();
			}

			if ($fileExt === 'pdf') {
				
				if (move_uploaded_file($tmpName, $uploadPath)) {
					// URL pública si necesitas que PDF.co lo acceda por internet
					// Asegúrate de tener un alias de tu carpeta "uploads" accesible desde el navegador
					$fileUrl = "https://iabanking.apps-adn.com/" . $fileName;
	
					// Aquí sigue tu lógica PDF.co:
					//$apiKey = "adnlean.com@gmail.com_SfBGojnr2FuvXSWOHxuu8MzeyudrwopxbuyaxhvbWpUSXreGnto0giAxCbuucJGV";
					$apiKey = "quintanaanthony7@gmail.com_iY7N3p4sBXK9G4t0Y0Pwjv8UW4w0PQvZ8E87Pd0d8mD6lAuTru3iCS6hQmCtMFLp";
					$url = "https://api.pdf.co/v1/ai-invoice-parser";
					$params = ["url" => $fileUrl];
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_HTTPHEADER, [
						"x-api-key: $apiKey",
						"Content-type: application/json"
					]);
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));

					$result = curl_exec($curl);

					if (curl_errno($curl) === 0) {
						$status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

						if ($status_code == 200) {
							$json = json_decode($result, true);

							if (!isset($json["error"]) || $json["error"] == false) {
								$jobId = $json["jobId"];

								do {
									$response = $this->CheckJobStatus($jobId, $apiKey);
									
									if ($response['status'] === "success") {
										
										$resultUrl = $response['url'];
										$parsedJson = file_get_contents($resultUrl);
										$data = json_decode($parsedJson, true);
	
										// Separar banco ID y prefijo
										$bancoParts = explode('.', $banco);
										$bancoId = $bancoParts[0] ?? null;
										$bancoPrefijo = $bancoParts[1] ?? null;

										// Seleccionar función por banco
										$movimientosFormat = null;
										switch ($bancoPrefijo) {
											case 'SFT': $movimientosFormat = $this->bancoSofitasa($data); break;
											case 'BCT': $movimientosFormat = $this->bancoBicentenario($data); break;
											case 'BDT': $movimientosFormat = $this->bancoTesoro($data); break;
											case 'BCM': $movimientosFormat = $this->bancoBancamiga($data); break;
											case 'BCO': $movimientosFormat = $this->bancoBanesco($anio, $mes, $data); break;
											case 'VNZ': $movimientosFormat = $this->bancoVenezuela($data); break;
											case 'MRC': $movimientosFormat = $this->bancoMercantil($anio, $data); break;
											case 'BNC': $movimientosFormat = $this->bancoBnc($data); break;
											case 'PRV': $movimientosFormat = $this->bancoProvincial($data); break;
										}

										$inserted = $this->model->insertTransaction($anio, $mes, $bancoId, $movimientosFormat);

										// Eliminar archivo temporal
										unlink($uploadPath);

										$arrResponse = array('status' => true, 'msg' => $inserted );
										echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
										die();
									} elseif ($response['status'] === "working") {
										sleep(3);
									} elseif ($response['status'] === "failed") {
										echo json_encode([
											'success' => false,
											'msg' => 'Error a leer el archivo bancario.'
										]);
										die();
									} else {
										break;
									}
								} while (true);
							} else {
								echo "<p>Error: " . $json["message"] . "</p>";
							}
						} else {
							echo "<p>Status code: " . $status_code . "</p>";
							echo "<p>" . $result . "</p>";
						}
					} else {
						echo "Error: " . curl_error($curl);
					}
					curl_close($curl);

					// Eliminar archivo si ocurrió algún error después de haberlo subido
					if (file_exists($uploadPath)) {
						unlink($uploadPath);
					}
				} else {
					echo json_encode([
						'success' => false,
						'msg' => 'No se pudo guardar el archivo en el servidor.'
					]);
					die();
				}
				
			} else if($fileExt === 'txt'){
				
				if (!move_uploaded_file($tmpName, $uploadPath)) {
					$arrResponse = array('status' => true, 'msg' => 'No se pudo guardar el archivo en el servidor.' );
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
					die();
				}
				
				$bancoParts = explode('.', $banco);
				$bancoId = $bancoParts[0] ?? null;
				$bancoPrefijo = $bancoParts[1] ?? null;
								
				$movimientosFormat = null;
				switch ($bancoPrefijo) {
					case 'MRC': $movimientosFormat = $this->procesarTxtMercantil($uploadPath); break;
					//case 'SFT': $movimientosFormat = $this->procesarTxtSofitasa($uploadPath); break;
					//case 'BCM': $movimientosFormat = $this->procesarExcelBancamiga($uploadPath); break;
					//case 'VNZ': $movimientosFormat = $this->procesarExcelVenezuela($uploadPath); break;
					//case 'BCT': $movimientosFormat = $this->bancoBicentenario($data); break;
					//case 'BNC': $movimientosFormat = $this->bancoBnc($data); break;
					//case 'BDT': $movimientosFormat = $this->bancoTesoro($data); break;
					//case 'BCO': $movimientosFormat = $this->bancoBanesco($anio, $mes, $data); break;
					//case 'PRV': $movimientosFormat = $this->bancoProvincial($data); break;
					
				}

					$inserted = $this->model->insertTransaction($anio, $mes, $bancoId, $movimientosFormat);
					// Eliminar archivo temporal
					unlink($uploadPath);

					$arrResponse = array('status' => true, 'msg' => $inserted );
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
					die();

			}else {
				
				if (!move_uploaded_file($tmpName, $uploadPath)) {
					$arrResponse = array('status' => true, 'msg' => 'No se pudo guardar el archivo en el servidor.' );
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
					die();
				}
				
				$bancoParts = explode('.', $banco);
				$bancoId = $bancoParts[0] ?? null;
				$bancoPrefijo = $bancoParts[1] ?? null;

				$movimientosFormat = null;
				switch ($bancoPrefijo) {
					case 'SFT': $movimientosFormat = $this->procesarExcelSofitasa($uploadPath); break;
					case 'BCM': $movimientosFormat = $this->procesarExcelBancamiga($uploadPath); break;
					case 'VNZ': $movimientosFormat = $this->procesarExcelVenezuela($uploadPath); break;
					case 'MRC': $movimientosFormat = $this->procesarExcelMercantil($uploadPath); break;
					//case 'BCT': $movimientosFormat = $this->bancoBicentenario($data); break;
					//case 'BNC': $movimientosFormat = $this->bancoBnc($data); break;
					//case 'BDT': $movimientosFormat = $this->bancoTesoro($data); break;
					//case 'BCO': $movimientosFormat = $this->bancoBanesco($anio, $mes, $data); break;
					//case 'PRV': $movimientosFormat = $this->bancoProvincial($data); break;
					
				}

					$inserted = $this->model->insertTransaction($anio, $mes, $bancoId, $movimientosFormat);
					// Eliminar archivo temporal
					unlink($uploadPath);

					$arrResponse = array('status' => true, 'msg' => $inserted );
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
					die();
			}
		} else {
			echo json_encode([
				'success' => false,
				'msg' => 'Archivo inválido o no enviado.'
			]);
			die();
		}
	}
	
	private function CheckJobStatus($jobId, $apiKey)
	{
		// Create URL
		$url = "https://api.pdf.co/v1/job/check";

		$parameters = ["jobid" => $jobId];
		$data = json_encode($parameters);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTPHEADER, ["x-api-key: " . $apiKey, "Content-type: application/json"]);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($curl);
		curl_close($curl);

		if (curl_errno($curl)) {
			return ["status" => "error", "message" => curl_error($curl)];
		}

		$status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($status_code == 200) {
			$json = json_decode($result, true);
			if (!isset($json["error"]) || $json["error"] == false) {
				// Retorna la respuesta completa si está bien
				return $json;
			} else {
				return ["status" => "error", "message" => $json["message"]];
			}
		} else {
			return ["status" => "error", "message" => $result];
		}
	}

	public function getTransaction(){
			

			$postdata = file_get_contents("php://input");
			$request = json_decode($postdata, true);
			if ($request) {
				// Si se envió un JSON
				
				$token = $request['token'];
				$rif = $request['rif'];
				if(empty($request['bd'])){
					$bd = '';
				}else{
					$bd = $request['bd'];
				}
				$cuenta = $request['cuenta'];
				$opcion = $request['opcion'];
				$desde = $request['desde'];
				$hasta = $request['hasta'];

				$getTransaction = $this->model->getTransactionEndPoint($token, $rif, $bd, $cuenta, $opcion, $desde, $hasta);
					
				$arrResponse = array('status' => true, 'data' => $getTransaction );
			
			}
			
		echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		die();
	}
	
	private function bancoSofitasa($data)
	{
		// Puedes hacer un print_r si estás debuggeando:
		$movimientos = $data['transactions'];
		$movimientos_transformados = [];

		foreach ($movimientos as $key => $item) {
			// Convertir fecha de DD/MM/YYYY a YYYY-MM-DD
			$fecha = DateTime::createFromFormat('d/m/Y', $item['date'])->format('Y-m-d');
			// Limpiar y convertir a número float para poder comparar correctamente
			$debit = $this->parseEuropeanNumber($item['debit']);
			$credit = $this->parseEuropeanNumber($item['credit']);

			// Determinar el monto correcto
			if ($credit == 0.00) {
				$monto = $debit;
			} else {
				$monto = $credit;
			}

			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['reference'],
				'monto'      => $monto, // O 'credit' si prefieres según la lógica
			];
		}

		return $movimientos_transformados;
	}
	
	private function bancoBicentenario($data)
	{	

		// Puedes hacer un print_r si estás debuggeando:
		$movimientos = $data['transaction_details'];
		$movimientos_transformados = [];
		
		foreach ($movimientos as $key => $item) {
			
			// Convertir fecha de DD/MM/YYYY a YYYY-MM-DD
			$fecha = DateTime::createFromFormat('d-m-Y', $item['date'])->format('Y-m-d');
			// Limpiar y convertir a número float para poder comparar correctamente
			$debit = $this->parseEuropeanNumber($item['debit']);
			$credit = $this->parseEuropeanNumber($item['credit']);

			// Determinar el monto correcto
			if ($credit == 0.00) {
				$monto = '-'.$debit;
			} else {
				$monto = $credit;
			}

			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['reference'],
				'monto'      => $monto, // O 'credit' si prefieres según la lógica
			];
		}
		
		return $movimientos_transformados;
	}
	
	private function bancoTesoro($data)
	{	
		// Puedes hacer un print_r si estás debuggeando:
		$movimientos = $data['transactions'];
		$movimientos_transformados = [];
		
		foreach ($movimientos as $key => $item) {
			
			// Convertir fecha de DD/MM/YYYY a YYYY-MM-DD
			$fecha = DateTime::createFromFormat('d/m/Y', $item['date'])->format('Y-m-d');
			// Limpiar y convertir a número float para poder comparar correctamente
			$debit = $this->parseEuropeanNumber($item['debit']);
			$credit = $this->parseEuropeanNumber($item['credit']);

			// Determinar el monto correcto
			if ($credit == 0.00) {
				$monto = '-'.$debit;
			} else {
				$monto = $credit;
			}

			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['reference'],
				'monto'      => $monto, // O 'credit' si prefieres según la lógica
			];
		}
		
		return $movimientos_transformados;
	}
	
	private function bancoBancamiga($data)
	{	
		// Puedes hacer un print_r si estás debuggeando:
		$movimientos = $data['account_statement']['transactions'];
		$movimientos_transformados = [];
		
		foreach ($movimientos as $key => $item) {
			
			// Convertir fecha de DD/MM/YYYY a YYYY-MM-DD
			$fecha = DateTime::createFromFormat('d-m-Y', $item['date'])->format('Y-m-d');
			// Limpiar y convertir a número float para poder comparar correctamente
			$debit = $this->parseEuropeanNumber($item['debit']);
			$credit = $this->parseEuropeanNumber($item['credit']);
			
			
			
			// Determinar el monto correcto
			if ($credit == 0.00) {
				$monto = '-'.$debit;
			} else {
				$monto = $credit;
			}
			
			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['reference'],
				'monto'      => $monto, // O 'credit' si prefieres según la lógica
			];
		}
		
		return $movimientos_transformados;
	}
	
	private function bancoBanesco($anio, $mes, $data)
	{	
		
		echo json_encode([
			'status' => false,
			'msg' => 'Formato(PDF) - Banco Banesco, desabilitado temporalmente.'
		]);
		die();

		// Puedes hacer un print_r si estás debuggeando:
		$movimientos = $data['detalle_de_movimientos'];
		$movimientos_transformados = [];
		
		foreach ($movimientos as $key => $item) {
			
			// Convertir fecha de DD/MM/YYYY a YYYY-MM-DD
			$fechaStr = sprintf('%04d-%02d-%02d', $anio, $mes, $item['dia']);
			$fecha = (new DateTime($fechaStr))->format('Y-m-d');
			
			if($item['cargos'] == ''){
				$abonos = $this->parseEuropeanNumber($item['abonos']);
				$monto = $abonos;
			}else{
				$cargos = $this->parseEuropeanNumber($item['cargos']);
				$monto = '-'.$cargos;
			}
			
			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['ref.'],
				'monto'      => $monto, // O 'credit' si prefieres según la lógica
			];
		}
		
		return $movimientos_transformados;
	}
	
	private function bancoVenezuela($data)
	{	
		
		// Puedes hacer un print_r si estás debuggeando:
		$movimientos = $data['transactions'];
		$movimientos_transformados = [];
		
		foreach ($movimientos as $key => $item) {
			
			if($item['Descripción'] == 'SALDO INICIAL'){
				continue;
			}
			// Convertir fecha de DD/MM/YYYY a YYYY-MM-DD
			$fecha = DateTime::createFromFormat('d/m/Y', $item['Fecha'])->format('Y-m-d');
			// Limpiar y convertir a número float para poder comparar correctamente
			$debit = $this->parseEuropeanNumber($item['Débito']);
			$credit = $this->parseEuropeanNumber($item['Crédito']);
			
			// Determinar el monto correcto
			if ($credit == 0.00) {
				$monto = $debit;
			} else {
				$monto = $credit;
			}
			
			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['Referencia'],
				'monto'      => $monto, // O 'credit' si prefieres según la lógica
			];
		}
		
		return $movimientos_transformados;
	}
	
	private function bancoMercantil($anio, $data)
	{	
		if (array_key_exists('header', $data)) {
			
			echo json_encode([
				'status' => false,
				'msg' => 'Formato(PDF) - Banco Mercantil, desabilitado temporalmente.'
			]);
			die();
			
			$result = $this->movMercantil1($data);
			return $result;
		} else if(array_key_exists('account_details', $data)){
			$result = $this->movMercantil2($data);
			return $result;
		} else {
			$result = $this->movMercantil3($anio, $data);
			return $result;
		}
		
	}
	
	private function movMercantil1($data)
	{	
		// Puedes hacer un print_r si estás debuggeando:
		$movimientos = $data['transactions'];
		$movimientos_transformados = [];
		
		foreach ($movimientos as $key => $item) {
			
			// Convertir fecha de DD/MM/YYYY a YYYY-MM-DD
			$fecha = DateTime::createFromFormat('d/m/Y', $item['date'])->format('Y-m-d');
			
			if($item['debits'] == ''){
				$credits = $this->parseEuropeanNumber($item['credits']);
				$monto = $credits;
			}else{
				$debits = $this->parseEuropeanNumber($item['debits']);
				$monto = '-'.$debits;
			}
			
			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['transaction_number'],
				'monto'      => $monto, // O 'credit' si prefieres según la lógica
			];
		}
		return $movimientos_transformados;
	}
	
	private function movMercantil2($data)
	{	
		// Puedes hacer un print_r si estás debuggeando:
		$movimientos = $data['transactions'];
		$movimientos_transformados = [];
		
		foreach ($movimientos as $key => $item) {
			
			// Convertir fecha de DD/MM/YYYY a YYYY-MM-DD
			$fecha = DateTime::createFromFormat('d/m/Y', $item['date'])->format('Y-m-d');

			$amount = $this->parseEuropeanNumber($item['amount']);
			$monto = $amount;
			
			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['reference_number'],
				'monto'      => $monto, // O 'credit' si prefieres según la lógica
			];
		}
		
		return $movimientos_transformados;
	}
	
	private function movMercantil3($anio, $data)
	{	
		// Puedes hacer un print_r si estás debuggeando:
		$movDebit1 = $data['operations_debited'];
		$movDebit2 = $data['other_debits'];
		$movCredit = $data['other_credits'];
		$movimientos_transformados = [];
		
		foreach ($movDebit1 as $key => $item) {
			
			// Convertir '13/03' en día y mes
			list($dia, $mes) = explode('/', $item['date']);

			// Armar fecha y convertirla a formato 'Y-m-d'
			$fechaStr = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
			$fecha = (new DateTime($fechaStr))->format('Y-m-d');
			
			// Limpiar y convertir a número float para poder comparar correctamente
			$debit = $this->parseEuropeanNumber($item['amount']);
			
			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['number'],
				'monto'      => '-'.$debit, // O 'credit' si prefieres según la lógica
			];
		}
		
		foreach ($movDebit2 as $key => $item) {
			
			// Convertir '13/03' en día y mes
			list($dia, $mes) = explode('/', $item['date']);

			// Armar fecha y convertirla a formato 'Y-m-d'
			$fechaStr = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
			$fecha = (new DateTime($fechaStr))->format('Y-m-d');
			
			// Limpiar y convertir a número float para poder comparar correctamente
			$debit = $this->parseEuropeanNumber($item['amount']);
			
			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['number'],
				'monto'      => '-'.$debit, // O 'credit' si prefieres según la lógica
			];
		}
		
		foreach ($movCredit as $key => $item) {
			
			// Convertir '13/03' en día y mes
			list($dia, $mes) = explode('/', $item['date']);

			// Armar fecha y convertirla a formato 'Y-m-d'
			$fechaStr = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
			$fecha = (new DateTime($fechaStr))->format('Y-m-d');
			
			// Limpiar y convertir a número float para poder comparar correctamente
			$credit = $this->parseEuropeanNumber($item['amount']);
			
			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['number'],
				'monto'      => $credit, // O 'credit' si prefieres según la lógica
			];
		}

		return $movimientos_transformados;
	}
	
	private function bancoBnc($data)
	{
		// Puedes hacer un print_r si estás debuggeando:
		$movimientos = $data['transactions'];
		$movimientos_transformados = [];

		foreach ($movimientos as $key => $item) {
			// Convertir fecha de DD/MM/YYYY a YYYY-MM-DD
			$fecha = DateTime::createFromFormat('d/m/Y', $item['date'])->format('Y-m-d');
			// Limpiar y convertir a número float para poder comparar correctamente
			$debit = $this->parseEuropeanNumber($item['debit']);
			$credit = $this->parseEuropeanNumber($item['credit']);

			// Determinar el monto correcto
			if ($credit == 0.00) {
				$monto = $debit;
			} else {
				$monto = $credit;
			}

			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['reference'],
				'monto'      => $monto, // O 'credit' si prefieres según la lógica
			];
		}
	
		return $movimientos_transformados;
	}
	
	private function bancoProvincial($data)
	{
		// Puedes hacer un print_r si estás debuggeando:
		$movimientos = $data['account_statement']['transactions'];
		$movimientos_transformados = [];

		foreach ($movimientos as $key => $item) {
			// Convertir fecha de DD/MM/YYYY a YYYY-MM-DD
			$fecha = DateTime::createFromFormat('d-m-Y', $item['operation_date'])->format('Y-m-d');
			// Limpiar y convertir a número float para poder comparar correctamente
			$debit = $this->parseEuropeanNumber($item['charges']);
			$credit = $this->parseEuropeanNumber($item['credits']);
			
			// Determinar el monto correcto
			if (empty($credit)) {
				$monto = '-'.$debit;
			} else {
				$monto = $credit;
			}

			$movimientos_transformados[] = [
				'fecha'      => $fecha,
				'referencia' => $item['reference'],
				'monto'      => $monto, // O 'credit' si prefieres según la lógica
			];
		}

		return $movimientos_transformados;
	}
	/*private function parseEuropeanNumber($number) {

		// Elimina el separador de miles
		$number = str_replace('.', '', $number);
		// Reemplaza coma decimal por punto
		$number = str_replace(',', '.', $number);
		return floatval($number);
	}*/

	private function parseEuropeanNumber($number) 
	{
		$number = trim((string) $number);

		// Si contiene ambos símbolos: coma y punto
		if (strpos($number, ',') !== false && strpos($number, '.') !== false) {
			// Verificamos cuál está más cerca del final (probable decimal)
			if (strrpos($number, ',') > strrpos($number, '.')) {
				// Formato europeo: 1.234,56
				$number = str_replace('.', '', $number);  // quita miles
				$number = str_replace(',', '.', $number); // cambia decimal
			} else {
				// Formato mal exportado como 4,810.53 → debemos quitar la coma
				$number = str_replace(',', '', $number);  // quita miles
				// el punto decimal queda
			}
		}
		// Solo coma: formato europeo simple
		elseif (strpos($number, ',') !== false) {
			$number = str_replace(',', '.', $number);
		}
		// Solo punto: ya está bien

		return floatval($number);
	}

	public function edit($id)
	{	
		$data['page_functions_js'] = "functions_transaction.js";
		$data['transaccion'] = $this->model->getTrans($id);
		$this->views->getView($this, "edit", $data);
	}

	public function updateTransaccion()
	{	
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			return $this->jsonResponse(false, 'Método no permitido');
		}
		$id = $_POST['id'] ?? null;
		$reference = $_POST['reference'] ?? null;
		$date = $_POST['date'] ?? null;
		$amount = $_POST['amount'] ?? null;

		$response = $this->model->updateTransaccion($id, $reference, $date, $amount);
		if($response){
			echo json_encode([
				'status' => true,
				'message' => 'La transaccion se ha actualizado de manera correcta.'
			]);
			die();
		}else{
			echo json_encode([
				'status' => false,
				'message' => 'No se pudo actualizar la transaccion.'
			]);
			die();
		}
	}

	public function deleteTransaccion($id)
	{		
		$response = $this->model->deleteTransaccion($id);
		if($response){
			echo json_encode([
				'status' => true,
				'message' => 'La transaccion se elimino de manera exitosa'
			]);
			die();
		}else{
			echo json_encode([
				'status' => false,
				'message' => 'No se pudo eliminar la transaccion.'
			]);
			die();
		}
		
	}

	public function updateField()
	{
		$json = json_decode(file_get_contents("php://input"), true);
		$id = $json['id'] ?? null;
		$field = $json['field'] ?? null;
		$value = $json['value'] ?? null;

		// Validar campo permitido
		$allowedFields = ['reference', 'date', 'amount', 'id_cliente', 'tasa'];
		if (!in_array($field, $allowedFields)) {
			echo json_encode(['status' => false, 'message' => 'Campo no permitido']);
			return;
		}

		// Validar existencia y tipo
		if (!$id || !$field || $value === null) {
			echo json_encode(['status' => false, 'message' => 'Datos incompletos']);
			return;
		}

		// Ejemplo de actualización
		$update = $this->model->updateFieldById($id, $field, $value);
		if ($update) {
			echo json_encode(['status' => true, 'message' => 'Campo actualizado correctamente']);
		} else {
			echo json_encode(['status' => false, 'message' => 'No se pudo actualizar']);
		}
	}

	public function listado()
	{
		$search = $_GET['search'] ?? '';
		$clientes = $this->model->buscarClientes($search);
		echo json_encode($clientes);
	}

	public function getFiltros()
	{
		// Obtiene los bancos y cuentas
		$data = $this->model->getFiltros();

		// Devuelve JSON con bancos y cuentas
		header('Content-Type: application/json');
		echo json_encode($data);
		exit;
	}
	
	private function procesarExcelVenezuela($filePath)
	{
		try {
			$spreadsheet = IOFactory::load($filePath);
			$sheet = $spreadsheet->getActiveSheet();
			$rows = $sheet->toArray();

			$movimientos_transformados = [];
			
			// Asume que la primera fila son los encabezados
			for ($i = 1; $i < count($rows); $i++) {
				$fila = $rows[$i];

				$fecha = DateTime::createFromFormat('d/m/Y', $fila[3])->format('Y-m-d');

				$debit = $this->parseEuropeanNumber($fila[6]);
				$credit = $this->parseEuropeanNumber($fila[5]);

				// Determinar el monto correcto
				if ($credit == 0) {
					$monto = $debit;
				} else {
					$monto = $credit;
				}

				// Ajusta los índices [0], [1], [2] según el orden de tus columnas
				$movimientos_transformados[] = [
					'fecha'      => $fecha,  // Ej: "2024-01-01"
					'referencia' => $fila[1],  // Ej: "123456"
					'monto'      => $monto,  // Ej: "100.00"
				];
			}

			return $movimientos_transformados;

		} catch (Exception $e) {
			if (file_exists($filePath)) unlink($filePath);
			echo json_encode([
				'success' => false,
				'msg' => 'Archivo Excel esta dañado y/o absoleto.'
			]);
			die();
		}
	}

	private function procesarExcelSofitasa($filePath)
	{	
		try {
			$spreadsheet = IOFactory::load($filePath);
			$sheet = $spreadsheet->getActiveSheet();
			$rows = $sheet->toArray();

			$movimientos_transformados = [];
			
			// Asume que la primera fila son los encabezados
			for ($i = 16; $i < count($rows); $i++) {
				$fila = $rows[$i];
				
				if ($fila[1] == 'Totales') {
					break; // Termina el ciclo si la fecha está vacía
				}

				$fecha = DateTime::createFromFormat('d/m/Y', $fila[1])->format('Y-m-d');

				$debit = $this->parseEuropeanNumber($fila[13]);
				$credit = $this->parseEuropeanNumber($fila[15]);

				// Determinar el monto correcto
				if ($credit == 0) {
					$monto = $debit;
				} else {
					$monto = $credit;
				}

				// Ajusta los índices [0], [1], [2] según el orden de tus columnas
				$movimientos_transformados[] = [
					'fecha'      => $fecha,  // Ej: "2024-01-01"
					'referencia' => $fila[12],  // Ej: "123456"
					'monto'      => $monto,  // Ej: "100.00"
				];
			}

			return $movimientos_transformados;

		} catch (Exception $e) {
			if (file_exists($filePath)) unlink($filePath);
			echo json_encode([
				'success' => false,
				'msg' => 'Archivo Excel esta dañado y/o absoleto.'
			]);
			die();
		}
	}

	private function procesarExcelMercantil($filePath)
	{	
		try {
			$spreadsheet = IOFactory::load($filePath);
			$sheet = $spreadsheet->getActiveSheet();
			$rows = $sheet->toArray();

			foreach ($rows as $fila) {
				if (count($fila) > 4) {
					$result = $this->processMercantilExcel1($rows);
					return $result;
				}else{
					$result = $this->processMercantilExcel2($rows);
					return $result;
				}
			}

		} catch (Exception $e) {
			if (file_exists($filePath)) unlink($filePath);
			echo json_encode([
				'success' => false,
				'msg' => 'Archivo Excel esta dañado y/o absoleto.'
			]);
			die();
		}
	}

	private function processMercantilExcel1($rows)
	{	
		$movimientos_transformados = [];
			
		// Asume que la primera fila son los encabezados
		for ($i = 10; $i < count($rows); $i++) {
			$fila = $rows[$i];
			
			if ($fila[3] == 'SALDO INICIAL') {
				break; // Termina el ciclo si la fecha está vacía
			}

			$fecha = DateTime::createFromFormat('d/m/Y', $fila[1])->format('Y-m-d');

			$amount = $this->parseEuropeanNumber($fila[4]);

			$movimientos_transformados[] = [
				'fecha'      => $fecha,  // Ej: "2024-01-01"
				'referencia' => $fila[2],  // Ej: "123456"
				'monto'      => $amount,  // Ej: "100.00"
			];
		}

		return $movimientos_transformados;
	}

	private function processMercantilExcel2($rows)
	{	
		$movimientos_transformados = [];

		// Asume que la primera fila son los encabezados
		for ($i = 7; $i < count($rows); $i++) {
			$fila = $rows[$i];
			
			if ($fila[2] == 'SALDO INICIAL') {
				break; // Termina el ciclo si la fecha está vacía
			}

			$fecha = DateTime::createFromFormat('d/m/Y', $fila[0])->format('Y-m-d');

			$amount = $this->parseEuropeanNumber($fila[3]);

			$movimientos_transformados[] = [
				'fecha'      => $fecha,  // Ej: "2024-01-01"
				'referencia' => $fila[1],  // Ej: "123456"
				'monto'      => $amount,  // Ej: "100.00"
			];
		}

		return $movimientos_transformados;
	}

	private function procesarExcelBancamiga($filePath)
	{	
		
		try {
			$spreadsheet = IOFactory::load($filePath);
			$sheet = $spreadsheet->getActiveSheet();
			$rows = $sheet->toArray();

			$movimientos_transformados = [];
			
			// Asume que la primera fila son los encabezados
			for ($i = 6; $i < count($rows); $i++) {
				$fila = $rows[$i];
				if (empty($fila[1])) {
					break; // Termina el ciclo si la fecha está vacía
				}
				
				$fecha = DateTime::createFromFormat('m/d/Y', $fila[1])->format('Y-m-d');

				$debit = $this->parseEuropeanNumber($fila[4]);
				$credit = $this->parseEuropeanNumber($fila[5]);

				// Determinar el monto correcto
				if ($credit == 0) {
					$monto = '-'.$debit;
				} else {
					$monto = $credit;
				}

				$referencia = ltrim($fila[2], "'");

				// Ajusta los índices [0], [1], [2] según el orden de tus columnas
				$movimientos_transformados[] = [
					'fecha'      => $fecha,  // Ej: "2024-01-01"
					'referencia' => $referencia,  // Ej: "123456"
					'monto'      => $monto,  // Ej: "100.00"
				];
			}

			return $movimientos_transformados;

		} catch (Exception $e) {
			if (file_exists($filePath)) unlink($filePath);
			echo json_encode([
				'success' => false,
				'msg' => 'Archivo Excel esta dañado y/o absoleto.'
			]);
			die();
		}
	}

	private function procesarTxtMercantil($filePath)
	{	
		try {
			$handle = fopen($filePath, 'r');
			$linea = fgets($handle);
			fclose($handle);

			$linea = trim($linea);

			if (substr_count($linea, ',') > 3) {
				// Tipo 1: CSV
				$result = $this->procesarTxtMercantil1($filePath);
				return $result;
			} elseif (preg_match('/^(NC|ND|SF|SD)\s+\d{2}\/\d{2}\/\d{4}/', $linea)) {
				// Tipo 2: comienza con NC, ND, etc.
				$result = $this->procesarTxtMercantil2($filePath);
				return $result;
			} elseif (preg_match('/^0105\s+VES\s+\d{12}\s+\d{8}/', $linea)) {
				// Tipo 3: comienza con 0105 VES ... fecha
				$result = $this->procesarTxtMercantil3($filePath);
				return $result;
			} else {
				echo json_encode([
					'success' => false,
					'msg' => 'Formato txt no reconocido.'
				]);
				die();
			}
		} catch (Exception $e) {
			if (file_exists($filePath)) unlink($filePath);
			echo json_encode([
				'success' => false,
				'msg' => 'El archivo TXT está dañado o su formato no es reconocido.'
			]);
			die();
		}
	}


	private function procesarTxtMercantil1($filePath)
	{
		try {

			if (($handle = fopen($filePath, "r")) !== false) {
				while (($data = fgetcsv($handle, 1000, ',', '"')) !== false) {

					if (in_array($data[5], ['SI', 'SF'])) {
						continue; // Ignora este movimiento y pasa al siguiente
					}

					// Validamos que tenga al menos 10 columnas
					if (count($data) >= 8) {
						
						$fecha = DateTime::createFromFormat('dmY', $data[3])->format('Y-m-d');
						$amount = $this->parseEuropeanNumber($data[7]);

						if($data[5] == 'NC'){
							$monto = $amount;
						}else{
							$monto = '-'.$amount;
						}

						$movimientos_transformados[] = [
							'fecha'      => $fecha,
							'referencia' => $data[4],
							'monto'      => $monto
						];
					}
				}
				fclose($handle);
			}

			return $movimientos_transformados;

		} catch (Exception $e) {
			if (file_exists($filePath)) unlink($filePath);
			echo json_encode([
				'success' => false,
				'msg' => 'Archivo TXT esta dañado.'
			]);
			die();
		}
	}

	private function procesarTxtMercantil2($filePath)
	{
		try {

			$lineas = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

			$movimientos_transformados = [];

			foreach ($lineas as $linea) {
				// Asegurarse de que tenga largo mínimo
				if (strlen(trim($linea)) < 70) continue;

				$type        = trim(substr($linea, 0, 5));

				if ($type == 'SI' || $type == 'SF') {
						continue; // Ignora este movimiento y pasa al siguiente
				}

				$fechaRaw    = trim(substr($linea, 5, 13));   // "12/06/2025"
				$referencia  = trim(substr($linea, 18, 18));  // 15-18 caracteres según el ejemplo
				$montoRaw    = trim(substr($linea, 84));      // desde el caracter 80 en adelante (ajustar si varía)
				
				$fecha = DateTime::createFromFormat('d/m/Y', $fechaRaw)->format('Y-m-d');

				$amount = $this->parseEuropeanNumber($montoRaw);

				$movimientos_transformados[] = [
					'fecha'       => $fecha,
					'referencia'  => $referencia,
					'monto'       => $amount
				];
			}
			
			return $movimientos_transformados;

		} catch (Exception $e) {
			if (file_exists($filePath)) unlink($filePath);
			echo json_encode([
				'success' => false,
				'msg' => 'Archivo TXT esta dañado'
			]);
			die();
		}
	}

	private function procesarTxtMercantil3($filePath)
	{
		try {

			$lineas = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			$movimientos_transformados = [];

			foreach ($lineas as $linea) {
				// Dividir en tokens por uno o más espacios
				$tokens = preg_split('/\s+/', trim($linea));

				// Verifica que haya al menos 10 campos
				if (count($tokens) < 10) continue;

				// Extraer los últimos 3 valores
				$codigo   = array_pop($tokens);
				$saldoRaw = array_pop($tokens);
				$montoRaw = array_pop($tokens);

				// Convertir montos a float
				$amount = $this->parseEuropeanNumber($montoRaw);

				$tiposPermitidos = ['ND', 'NC'];
				$tipo = null;
				foreach ($tokens as $i => $val) {
					if (in_array($val, $tiposPermitidos)) {
						$tipo = $val;
						$posTipo = $i;
						break;
					}
				}

				if ($tipo === null) continue;


				// Separar los campos conocidos
				$fechaRaw   = $tokens[3] ?? '';
				$referencia = $tokens[4] ?? '';
				
				// Formatear fecha
				$fecha = DateTime::createFromFormat('dmY', $fechaRaw)->format('Y-m-d');

				// Si es ND, monto negativo
				if ($tipo === 'ND') {
					$amount *= -1;
				}

				$movimientos_transformados[] = [
					'fecha'       => $fecha,
					'referencia'  => $referencia,
					'monto'       => $amount,
				];
			}

			return $movimientos_transformados;

		} catch (Exception $e) {
			if (file_exists($filePath)) unlink($filePath);
			echo json_encode([
				'success' => false,
				'msg' => 'Archivo TXT esta dañado.'
			]);
			die();
		}
	}

}
?>