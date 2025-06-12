<?php

$botonVisualizar = 1;
$url_base = base_url();
$title = "Banking AI";  // Si deseas un título específico para esta vista
$titleHeader = "Subir Movimientos";
//$descriptionHeader = "We are on a mission to help developers like you build successful projects for FREE.";
$urlHeader = base_url()."/transaccion";
$buttonHeader = "Lista de movimientos";
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
                        <form name="formNewTransaction" id="formNewTransaction">
                            <div class="row">
                                <div class="col-md-3" id="anio">
                                    <div class="form-group">
                                        <label class="form-label">Año</label>
                                        <select class="form-select mb-3 shadow-none" name="anio" id="anio">
                                            <?php foreach ($data['years'] as $year): ?>
                                            <option value="<?= $year ?>"
                                                <?= ($year == $data['currentYear']) ? 'selected' : '' ?>>
                                                <?= $year ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback d-none" id="messageAnio">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3" id="mes">
                                    <div class="form-group">
                                        <label class="form-label">Mes</label>
                                        <select class="form-select mb-3 shadow-none" name="mes" id="mes">
                                            <?php foreach ($data['months'] as $index => $month): ?>
                                            <option value="<?= $index + 1 ?>"
                                                <?= ($index + 1 == $data['currentMonth']) ? 'selected' : '' ?>>
                                                <?= $month ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback d-none" id="messageMes">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="banco">
                                    <div class="form-group">
                                        <label class="form-label">Banco</label>
                                        <select class="form-select mb-3 shadow-none" name="banco" id="banco">
                                            <?php foreach ($data['bank'] as $index => $bank): ?>
                                            <option value="<?= $bank['id'] ?>.<?= $bank['banco'] ?>">
                                                <?= $bank['name']  ?> - <?= $bank['account']  ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback d-none" id="messageBanco">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="archive">
                                    <div class="form-group">
                                        <label class="form-label">Archivo de movimientos</label>
                                        <input type="file" class="form-control" required name="archive" id="archive">
                                        <div class="invalid-feedback d-none" id="messageArchive">
                                            El campo es obligatorio
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-5">
                                Subir Archivo 
                                <svg class="icon-20 ms-2" width="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4"
                                        d="M17.554 7.29614C20.005 7.29614 22 9.35594 22 11.8876V16.9199C22 19.4453 20.01 21.5 17.564 21.5L6.448 21.5C3.996 21.5 2 19.4412 2 16.9096V11.8773C2 9.35181 3.991 7.29614 6.438 7.29614H7.378L17.554 7.29614Z"
                                        fill="currentColor"></path>
                                    <path
                                        d="M12.5464 16.0374L15.4554 13.0695C15.7554 12.7627 15.7554 12.2691 15.4534 11.9634C15.1514 11.6587 14.6644 11.6597 14.3644 11.9654L12.7714 13.5905L12.7714 3.2821C12.7714 2.85042 12.4264 2.5 12.0004 2.5C11.5754 2.5 11.2314 2.85042 11.2314 3.2821L11.2314 13.5905L9.63742 11.9654C9.33742 11.6597 8.85043 11.6587 8.54843 11.9634C8.39743 12.1168 8.32142 12.3168 8.32142 12.518C8.32142 12.717 8.39743 12.9171 8.54643 13.0695L11.4554 16.0374C11.6004 16.1847 11.7964 16.268 12.0004 16.268C12.2054 16.268 12.4014 16.1847 12.5464 16.0374Z"
                                        fill="currentColor"></path>
                                </svg>
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