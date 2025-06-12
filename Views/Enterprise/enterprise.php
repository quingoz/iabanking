<?php

$botonVisualizar = 0;
$url_base = base_url();
$title = "Banking AI";  // Si deseas un título específico para esta vista
$titleHeader = "Listado de empresas";
//$descriptionHeader = "We are on a mission to help developers like you build successful projects for FREE.";
$urlHeader = base_url()."/enterprise/new";
$buttonHeader = "Crear empresa";
ob_start(); // Inicia el almacenamiento en búfer para capturar el contenido

// El contenido específico de la vista home.php
?>

<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Lista de empresas</h4>
                        </div>
                    </div>
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table id="enterprise-list-table" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>NOMBRE</th>
                                        <th>BD</th>
                                        <th>RIF</th>
                                        <th>TOKEN</th>
                                        <th>TABLA</th>
                                        <th>ACCIONES</th>
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