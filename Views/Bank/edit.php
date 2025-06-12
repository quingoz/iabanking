<?php

$botonVisualizar = 1;
$url_base = base_url();
$title = "Banking AI";  // Si deseas un título específico para esta vista
$titleHeader = "Editar Banco";
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
                        <form name="formEditBank" id="formEditBank">
                            <input type="hidden" name="id" value="<?= $data['bank']['id'] ?>">
                            <div class="row">
                                <div class="col-md-6" id="name">
                                    <div class="form-group">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-control mb-3" name="name" id="name" value="<?= $data['bank']['name'] ?>">
                                        <div class="invalid-feedback d-none" id="messageName">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="account">
                                    <div class="form-group">
                                        <label class="form-label">Cuenta</label>
                                        <input type="number" class="form-control mb-3" name="account" id="account" value="<?= $data['bank']['account'] ?>">
                                        <div class="invalid-feedback d-none" id="messageAccount">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2" id="idBank">
                                    <div class="form-group">
                                        <label class="form-label">ID Banco</label>
                                        <input type="number" class="form-control mb-3" name="id_bank" id="id_bank" value="<?= $data['bank']['id_bank'] ?>">
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
                                            <option value="<?= $enterprise['id']; ?>" <?= ($data['bank']['id_enterprise'] == $enterprise['id']) ? 'selected' : '' ?>>
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
                                            <option value="BTC" <?= ($data['bank']['banco'] == 'BTC') ? 'selected' : '' ?>>BTC - BICENTENARIO</option>
                                            <option value="BNC" <?= ($data['bank']['banco'] == 'BNC') ? 'selected' : '' ?>>BNC - BNC</option>
                                            <option value="BDT" <?= ($data['bank']['banco'] == 'BDT') ? 'selected' : '' ?>>BDT - DEL TESORO</option>
                                            <option value="BCM" <?= ($data['bank']['banco'] == 'BCM') ? 'selected' : '' ?>>BCM - BANCAMIGA</option>
                                            <option value="BCO" <?= ($data['bank']['banco'] == 'BCO') ? 'selected' : '' ?>>BCO - BANESCO</option>
                                            <option value="SFT" <?= ($data['bank']['banco'] == 'SFT') ? 'selected' : '' ?>>SFT - SOFITASA</option>
                                            <option value="VNZ" <?= ($data['bank']['banco'] == 'VNZ') ? 'selected' : '' ?>>VNZ - VENEZUELA</option>
                                            <option value="PRV" <?= ($data['bank']['banco'] == 'PRV') ? 'selected' : '' ?>>PRV - PROVINCIAL</option>
                                            <option value="MRC" <?= ($data['bank']['banco'] == 'MRC') ? 'selected' : '' ?>>MRC - MERCANTIL</option>
                                        </select>
                                        <div class="invalid-feedback d-none" id="messagePrex">
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