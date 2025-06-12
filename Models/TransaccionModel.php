<?php

	require_once("Libraries/Core/Mysql2.php");

	class TransaccionModel extends Mysql
	{	


		public function __construct()
		{
			parent::__construct();
			
		}
		
		public function getTransaction($filters = [])
		{

			$id_enterprise = $_SESSION['userData']['id_enterprise'];
			$sqlTable = "SELECT * FROM empresa WHERE id = $id_enterprise";
			$requestEnterprise = $this->select($sqlTable);
			$table = $requestEnterprise['table'];

			$where = "WHERE 1=1"; // base segura para concatenar
			if (!empty($filters['bank'])) {
				
				$bank = $filters['bank'];

				$where .= " AND m.bank LIKE '%$bank%'";
			}
			if (!empty($filters['account'])) {
				$account = $filters['account'];
				$where .= " AND m.account LIKE '%$account%'";
			}
			if (!empty($filters['reference'])) {
				$reference = $filters['reference'];
				$where .= " AND m.reference LIKE '%$reference%'";
			}
			if (!empty($filters['date'])) {
				$date = $filters['date'];
				$where .= " AND m.date LIKE '%$date%'";
			}
			
			$sql = "SELECT m.id, m.bank, m.account, m.responsible, m.reference, m.date, m.amount, 
						c.id as client_id, c.name as client_name, m.tasa 
					FROM $table m
					LEFT JOIN cliente c ON m.id_cliente = c.id
					$where";

			return $this->select_all($sql);
		}

		public function getBank($id_enterprise)
		{
			$sql = "SELECT * FROM banco WHERE id_enterprise = $id_enterprise AND `status` = 1";

			$request = $this->select_all($sql);

			return $request;
		}

		public function insertTransaction($anio, $mes, $banco, $movimientos)
		{	
			$id_enterprise = $_SESSION['userData']['id_enterprise'];

			$sqlTable = "SELECT * FROM empresa WHERE id = $id_enterprise";
			$request = $this->select($sqlTable);

			$table = $request['table'];

			$sqlBanco = "SELECT * FROM banco WHERE id = $banco";
			$selectBanco = $this->select($sqlBanco);
			
			
			foreach ($movimientos as $key => $mov) {
				
				// Extraer año y mes desde la fecha del movimiento
				$fechaMovimiento = DateTime::createFromFormat('Y-m-d', $mov['fecha']);
				$anioMov = (int)$fechaMovimiento->format('Y');
				$mesMov = (int)$fechaMovimiento->format('m');
				
				if ($anioMov == $anio && $mesMov == $mes) {
					
					$sqlInsert = "INSERT IGNORE INTO $table (bank, account, reference, `date`, amount, responsible)
									VALUES(?,?,?,?,?,?)";
					$valueArray = array($selectBanco['name'], $selectBanco['account'], $mov['referencia'], $mov['fecha'],
										$mov['monto'], 'API');
					$request = $this->insert($sqlInsert, $valueArray);
				}
			}
				

			return true;
		}

		public function getTransactionEndPoint($token, $rif, $bd, $cuenta, $opcion, $desde, $hasta)
		{	
			$sqlTable = "SELECT * FROM empresa 
						WHERE `rif` = '$rif' 
						AND token = '$token'
						AND (bd = '' OR  IF('$bd' = '',1, bd = '$bd'))";
			$request = $this->select($sqlTable);
			$table = $request['table'];

			if($opcion == 'movimientosRangoFecha'){
				$where = "  b.account = '$cuenta'
							AND m.date BETWEEN '$desde' AND '$hasta'";
			}else{
				$where = " m.date BETWEEN '$desde' AND '$hasta'";
			}

			$sql = "SELECT b.id_bank as bank, b.account, m.reference, m.`date`, 
						m.amount, m.responsible
					FROM $table m
					INNER JOIN banco b ON b.account = m.account
					WHERE $where";

			$request = $this->select_all($sql);

			return $request;

		}

		public function getTrans($id)
		{				
			$id_enterprise = $_SESSION['userData']['id_enterprise'];

			$sqlTable = "SELECT * FROM empresa WHERE id = $id_enterprise";
			$requestEnterprise = $this->select($sqlTable);

			$table = $requestEnterprise['table'];
			$sql = "SELECT * FROM $table WHERE id = $id";

			$request = $this->select($sql);
			return $request;
		}

		public function updateTransaccion($id, $reference, $date, $amount)
		{	
			$id_enterprise = $_SESSION['userData']['id_enterprise'];

			$sqlTable = "SELECT * FROM empresa WHERE id = $id_enterprise";
			$requestEnterprise = $this->select($sqlTable);

			$table = $requestEnterprise['table'];

			$sql = "UPDATE $table SET reference = ?, `date` = ?, amount = ?
					WHERE id = ?";
			$valueArray = array($reference, $date, $amount, $id);
			$request = $this->update($sql, $valueArray);
			return $request;
		}

		public function deleteTransaccion($id)
		{	
			$id_enterprise = $_SESSION['userData']['id_enterprise'];

			$sqlTable = "SELECT * FROM empresa WHERE id = $id_enterprise";
			$requestEnterprise = $this->select($sqlTable);

			$table = $requestEnterprise['table'];

			$sql = "DELETE FROM $table WHERE id = $id";
			$request = $this->delete($sql);
			return $request;			
		}

		public function updateFieldById($id, $field, $value)
		{
			$allowed = ['reference', 'date', 'amount', 'id_cliente', 'tasa'];
			if (!in_array($field, $allowed)) return false;

			$id_enterprise = $_SESSION['userData']['id_enterprise'];

			$sqlTable = "SELECT * FROM empresa WHERE id = $id_enterprise";
			$requestEnterprise = $this->select($sqlTable);

			$table = $requestEnterprise['table'];

			$sql = "UPDATE $table SET $field = ? WHERE id = ?";
			$arrData = array($value, $id);
			return $this->update($sql, $arrData);
		}

		public function buscarClientes($search)
		{
    		$sql = "SELECT id, `name` FROM cliente WHERE `name` LIKE '%$search%'";
    		return $this->select_all($sql);

		}

		public function getFiltros()
		{	
			$id_enterprise = $_SESSION['userData']['id_enterprise'];

			$sqlTable = "SELECT * FROM empresa WHERE id = $id_enterprise";
			$requestEnterprise = $this->select($sqlTable);

			$table = $requestEnterprise['table'];

			// Obtener bancos únicos ordenados
			$sqlBanks = "SELECT DISTINCT bank FROM $table WHERE bank IS NOT NULL AND bank != '' ORDER BY bank ASC";
			$banks = $this->select_all($sqlBanks);

			// Obtener cuentas únicas ordenadas
			$sqlAccounts = "SELECT DISTINCT account FROM $table WHERE account IS NOT NULL AND account != '' ORDER BY account ASC";
			$accounts = $this->select_all($sqlAccounts);

			// Extraemos solo los strings para enviar al controlador
			$banksList = array_map(fn($item) => $item['bank'], $banks);
			$accountsList = array_map(fn($item) => $item['account'], $accounts);

			return [
				'banks' => $banksList,
				'accounts' => $accountsList,
			];
		}

	}	


?>
