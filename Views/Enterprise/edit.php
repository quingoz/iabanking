<?php

$botonVisualizar = 1;
$url_base = base_url();
$title = "Banking AI";  // Si deseas un título específico para esta vista
$titleHeader = "Editar Empresa";
//$descriptionHeader = "We are on a mission to help developers like you build successful projects for FREE.";
$urlHeader = base_url()."/enterprise";
$buttonHeader = "Lista de empresas";
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
                        <form name="formEditEnterprise" id="formEditEnterprise">
                            <input type="hidden" name="id" value="<?= $data['enterprise']['id'] ?>">
                            <div class="row">
                                <div class="col-md-12" id="name">
                                    <div class="form-group">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-control mb-3" name="name" id="name" value="<?= $data['enterprise']['name'] ?>">
                                        <div class="invalid-feedback d-none" id="messageName">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3" id="bd">
                                    <div class="form-group">
                                        <label class="form-label">BD</label>
                                        <input type="text" class="form-control mb-3" name="bd" id="bd" value="<?= $data['enterprise']['bd'] ?>">
                                        <div class="invalid-feedback d-none" id="messageBd">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3" id="rif">
                                    <div class="form-group">
                                        <label class="form-label">Rif</label>
                                        <input type="text" class="form-control mb-3" name="rif" id="rif" value="<?= $data['enterprise']['rif'] ?>">
                                        <div class="invalid-feedback d-none" id="messageRif">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="token">
                                    <div class="form-group">
                                        <label class="form-label">Token</label>
                                        <input type="text" class="form-control mb-3" name="token" id="token" value="<?= $data['enterprise']['token'] ?>">
                                        <div class="invalid-feedback d-none" id="messageToken">
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