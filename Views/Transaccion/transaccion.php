<?php

$botonVisualizar = 1;
$url_base = base_url();
$title = "Banking AI";  // Si deseas un título específico para esta vista
$titleHeader = "Listado de Movimientos";
//$descriptionHeader = "We are on a mission to help developers like you build successful projects for FREE.";
$urlHeader = base_url()."/transaccion/newTransaction";
$buttonHeader = "Subir Movimiento";
ob_start(); // Inicia el almacenamiento en búfer para capturar el contenido

// El contenido específico de la vista home.php
?>

<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
        <div class="row mb-3">
            <div class="col-sm-12">
                <div class="card">
                    <div class="row card-body">
                        <div class="col-sm-3">
                            <label for="filtroBank">Filtrar por banco:</label>
                            <select id="filtroBank" class="form-select">
                                <option value="">Todos</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="filtroAccount">Filtrar por cuenta:</label>
                            <select id="filtroAccount" class="form-select">
                                <option value="">Todas</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="filtroReference">Filtrar por referencia:</label>
                            <input type="text" id="filtroReference" class="form-control">
                        </div>
                        <div class="col-sm-3">
                            <label for="filtroDate">Filtrar por fecha:</label>
                            <input type="date" id="filtroDate" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Lista de Movimientos</h4>
                        </div>
                    </div>
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table id="transaction-list-table" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>BANCO</th>
                                        <th>CUENTA</th>
                                        <th>REFERENCIA</th>
                                        <th>CLIENTE</th>
                                        <th>DATE</th>
                                        <th>MONTO</th>
                                        <th>ID CLIENTE</th>
                                        <th>RESPONSABLE</th>
                                        <th>TASA</th>
                                        <th>ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= media() ?>/js/<?= $data["page_functions_js"]?>"></script>
<?php
$content = ob_get_clean();  // Captura todo el contenido generado por la vista

// Incluye el layout principal (app.php) que contiene el <div class="container">
include dirname(__DIR__) . '/layouts/app.php';

?>