<?php

$botonVisualizar = 1;
$url_base = base_url();
$title = "Banking AI";  // Si deseas un título específico para esta vista
$titleHeader = "Editar Transaccion";
//$descriptionHeader = "We are on a mission to help developers like you build successful projects for FREE.";
$urlHeader = base_url()."/transaccion";
$buttonHeader = "Lista de transaccion";
ob_start(); // Inicia el almacenamiento en búfer para capturar el contenido

// El contenido específico de la vista home.php


?>

<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div id="loading-content" class="d-none">
        <div class="loader simple-loader loadingNew">
            <div class="loader-body"></div>
        </div>
    </div>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body p-5">
                        <form name="formEditTransaccion" id="formEditTransaccion">
                            <input type="hidden" name="id" value="<?= $data['transaccion']['id'] ?>">
                            <div class="row">
                                <div class="col-md-4" id="reference">
                                    <div class="form-group">
                                        <label class="form-label">Referencia</label>
                                        <input type="number" class="form-control mb-3" name="reference" id="reference" value="<?= $data['transaccion']['reference'] ?>">
                                        <div class="invalid-feedback d-none" id="messageReference">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" id="amount">
                                    <div class="form-group">
                                        <label class="form-label">Monto</label>
                                        <input type="number" class="form-control mb-3" step="0.01" name="amount" id="amount" value="<?= $data['transaccion']['amount'] ?>">
                                        <div class="invalid-feedback d-none" id="messageAmount">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" id="idBank">
                                    <div class="form-group">
                                        <label class="form-label">Fecha</label>
                                        <input type="date" class="form-control mb-3" name="date" id="date" value="<?= $data['transaccion']['date'] ?>">
                                        <div class="invalid-feedback d-none" id="messageDate">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-5">
                                Actualizar                         
                            </button>
                        </form>
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