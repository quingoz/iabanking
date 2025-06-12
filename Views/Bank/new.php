<?php

$botonVisualizar = 1;
$url_base = base_url();
$title = "Banking AI";  // Si deseas un título específico para esta vista
$titleHeader = "Crear Banco";
//$descriptionHeader = "We are on a mission to help developers like you build successful projects for FREE.";
$urlHeader = base_url()."/bank";
$buttonHeader = "Lista de bancos";
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
                        <form name="formNewBank" id="formNewBank">
                            <div class="row">
                                <div class="col-md-6" id="name">
                                    <div class="form-group">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-control mb-3" name="name" id="name">
                                        <div class="invalid-feedback d-none" id="messageName">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="account">
                                    <div class="form-group">
                                        <label class="form-label">Cuenta</label>
                                        <input type="number" class="form-control mb-3" name="account" id="account">
                                        <div class="invalid-feedback d-none" id="messageAccount">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2" id="idBank">
                                    <div class="form-group">
                                        <label class="form-label">ID Banco</label>
                                        <input type="number" class="form-control mb-3" name="id_bank" id="id_bank">
                                        <div class="invalid-feedback d-none" id="messageIdBank">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5" id="enterprise">
                                    <div class="form-group">
                                        <label class="form-label">Empresa</label>
                                        <select class="form-select mb-3 shadow-none" name="id_enterprise" id="id_enterprise">
                                            <?php foreach ($data['enterprise'] as $index => $enterprise): ?>
                                            <option value="<?= $enterprise['id']; ?>">
                                                <?= $enterprise['name']; ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback d-none" id="messageEnterprise">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-5" id="prex">
                                    <div class="form-group">
                                        <label class="form-label">Prefijo</label>
                                        <select class="form-select mb-3 shadow-none" name="prefix" id="prefix">
                                            <option value="BTC">BTC - BICENTENARIO</option>
                                            <option value="BNC">BNC - BNC</option>
                                            <option value="BDT">BDT - DEL TESORO</option>
                                            <option value="BCM">BCM - BANCAMIGA</option>
                                            <option value="BCO">BCO - BANESCO</option>
                                            <option value="SFT">SFT - SOFITASA</option>
                                            <option value="VNZ">VNZ - VENEZUELA</option>
                                            <option value="PRV">PRV - PROVINCIAL</option>
                                            <option value="MRC">MRC - MERCANTIL</option>
                                        </select>
                                        <div class="invalid-feedback d-none" id="messagePrex">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-5">
                                Registrar                         
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