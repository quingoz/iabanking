<?php

$botonVisualizar = 0;
$url_base = base_url();
$title = "Banking AI";  // Si deseas un título específico para esta vista
$titleHeader = "Panel de control";
//$descriptionHeader = "We are on a mission to help developers like you build successful projects for FREE.";
$urlHeader = base_url()."/home";
$buttonHeader = "Subir Movimiento";
ob_start(); // Inicia el almacenamiento en búfer para capturar el contenido

// El contenido específico de la vista home.php
?>

<div class="conatiner-fluid content-inner mt-n5 py-0">
    <?php if($_SESSION['userData']['ID_ROL'] == 1 || $_SESSION['userData']['ID_ROL'] == 3) { ?>
    <div class="row">
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive card">
                        <div class="card-header">
                            <h4 class="card-title mb-3">Ultimos 10 Movimientos</h4>
                        </div>
                        <table id="transaction-list-table" class="table table-striped" role="grid"
                            data-bs-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>BANCO</th>
                                    <th>CUENTA</th>
                                    <th>REFERENCIA</th>
                                    <th>FECHA</th>
                                    <th>MONTO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['transaccion'] as $key => $value) { 
                                    $color = $value['amount'] > 0 ? 'green' : 'red';
                                    ?>
                                <tr>
                                    <td><?= $value['id']; ?></td>
                                    <td><?= $value['bank']; ?></td>
                                    <td><?= $value['account']; ?></td>
                                    <td><?= $value['reference']; ?></td>
                                    <td><?= $value['date']; ?></td>
                                    <td><span style="color:<?= $color ?>"><?= $value['amount']; ?></span></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body" bis_skin_checked="1">
                            <div class="progress-widget" bis_skin_checked="1">
                                <div id="circle-progress-01" class="text-center">
                                    <svg class="icon-32" width="32" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4"
                                            d="M16.191 2H7.81C4.77 2 3 3.78 3 6.83V17.16C3 20.26 4.77 22 7.81 22H16.191C19.28 22 21 20.26 21 17.16V6.83C21 3.78 19.28 2 16.191 2Z"
                                            fill="currentColor"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.07996 6.6499V6.6599C7.64896 6.6599 7.29996 7.0099 7.29996 7.4399C7.29996 7.8699 7.64896 8.2199 8.07996 8.2199H11.069C11.5 8.2199 11.85 7.8699 11.85 7.4289C11.85 6.9999 11.5 6.6499 11.069 6.6499H8.07996ZM15.92 12.7399H8.07996C7.64896 12.7399 7.29996 12.3899 7.29996 11.9599C7.29996 11.5299 7.64896 11.1789 8.07996 11.1789H15.92C16.35 11.1789 16.7 11.5299 16.7 11.9599C16.7 12.3899 16.35 12.7399 15.92 12.7399ZM15.92 17.3099H8.07996C7.77996 17.3499 7.48996 17.1999 7.32996 16.9499C7.16996 16.6899 7.16996 16.3599 7.32996 16.1099C7.48996 15.8499 7.77996 15.7099 8.07996 15.7399H15.92C16.319 15.7799 16.62 16.1199 16.62 16.5299C16.62 16.9289 16.319 17.2699 15.92 17.3099Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </div>
                                <div class="progress-detail" bis_skin_checked="1">
                                    <h5 class="mb-2">Movimientos</h5>
                                    <h4 class="counter" style="visibility: visible;">
                                        <?= $data['countTransaccion']['total']; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if($_SESSION['userData']['ID_ROL'] == 1 || $_SESSION['userData']['ID_ROL'] == 2) { ?>
    <div class="row">
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive card">
                        <div class="card-header">
                            <h4 class="card-title mb-3">Ultimas 10 empresas</h4>
                        </div>
                        <table class="table table-striped" role="grid" data-bs-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>NOMBRE</th>
                                    <th>BD</th>
                                    <th>RIF</th>
                                    <th>TOKEN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['enterprise'] as $key => $value) { ?>
                                <tr>
                                    <td><?= $value['id']; ?></td>
                                    <td><?= $value['name']; ?></td>
                                    <td><?= $value['bd']; ?></td>
                                    <td><?= $value['rif']; ?></td>
                                    <td><?= $value['token']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="table-responsive card">
                        <div class="card-header">
                            <h4 class="card-title mb-3">Ultimos 10 Cuentas bancarias</h4>
                        </div>
                        <table class="table table-striped" role="grid" data-bs-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>BANCO</th>
                                    <th>CUENTA</th>
                                    <th>EMPRESA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['bank'] as $key => $value) { ?>
                                <tr>
                                    <td><?= $value['id']; ?></td>
                                    <td><?= $value['name']; ?></td>
                                    <td><?= $value['account']; ?></td>
                                    <td><?= $value['enterprise']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body" bis_skin_checked="1">
                            <div class="progress-widget" bis_skin_checked="1">
                                <div id="circle-progress-01" class="text-center">
                                    <svg class="icon-32" width="32" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4"
                                            d="M16.6756 2H7.33333C3.92889 2 2 3.92889 2 7.33333V16.6667C2 20.0711 3.92889 22 7.33333 22H16.6756C20.08 22 22 20.0711 22 16.6667V7.33333C22 3.92889 20.08 2 16.6756 2Z"
                                            fill="currentColor"></path>
                                        <path
                                            d="M7.36866 9.3689C6.91533 9.3689 6.54199 9.74223 6.54199 10.2045V17.0756C6.54199 17.5289 6.91533 17.9022 7.36866 17.9022C7.83088 17.9022 8.20421 17.5289 8.20421 17.0756V10.2045C8.20421 9.74223 7.83088 9.3689 7.36866 9.3689Z"
                                            fill="currentColor"></path>
                                        <path
                                            d="M12.0352 6.08887C11.5818 6.08887 11.2085 6.4622 11.2085 6.92442V17.0755C11.2085 17.5289 11.5818 17.9022 12.0352 17.9022C12.4974 17.9022 12.8707 17.5289 12.8707 17.0755V6.92442C12.8707 6.4622 12.4974 6.08887 12.0352 6.08887Z"
                                            fill="currentColor"></path>
                                        <path
                                            d="M16.6398 12.9956C16.1775 12.9956 15.8042 13.3689 15.8042 13.8312V17.0756C15.8042 17.5289 16.1775 17.9023 16.6309 17.9023C17.0931 17.9023 17.4664 17.5289 17.4664 17.0756V13.8312C17.4664 13.3689 17.0931 12.9956 16.6398 12.9956Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </div>
                                <div class="progress-detail" bis_skin_checked="1">
                                    <h5 class="mb-2">Cuentas bancarias</h5>
                                    <h4 class="counter" style="visibility: visible;">
                                        <?= $data['countBank']['total']; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body" bis_skin_checked="1">
                            <div class="progress-widget" bis_skin_checked="1">
                                <div id="circle-progress-01" class="text-center">
                                    <svg class="icon-32" width="32" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.9488 14.54C8.49884 14.54 5.58789 15.1038 5.58789 17.2795C5.58789 19.4562 8.51765 20.0001 11.9488 20.0001C15.3988 20.0001 18.3098 19.4364 18.3098 17.2606C18.3098 15.084 15.38 14.54 11.9488 14.54Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.4"
                                            d="M11.949 12.467C14.2851 12.467 16.1583 10.5831 16.1583 8.23351C16.1583 5.88306 14.2851 4 11.949 4C9.61293 4 7.73975 5.88306 7.73975 8.23351C7.73975 10.5831 9.61293 12.467 11.949 12.467Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.4"
                                            d="M21.0881 9.21923C21.6925 6.84176 19.9205 4.70654 17.664 4.70654C17.4187 4.70654 17.1841 4.73356 16.9549 4.77949C16.9244 4.78669 16.8904 4.802 16.8725 4.82902C16.8519 4.86324 16.8671 4.90917 16.8895 4.93889C17.5673 5.89528 17.9568 7.0597 17.9568 8.30967C17.9568 9.50741 17.5996 10.6241 16.9728 11.5508C16.9083 11.6462 16.9656 11.775 17.0793 11.7948C17.2369 11.8227 17.3981 11.8371 17.5629 11.8416C19.2059 11.8849 20.6807 10.8213 21.0881 9.21923Z"
                                            fill="currentColor"></path>
                                        <path
                                            d="M22.8094 14.817C22.5086 14.1722 21.7824 13.73 20.6783 13.513C20.1572 13.3851 18.747 13.205 17.4352 13.2293C17.4155 13.232 17.4048 13.2455 17.403 13.2545C17.4003 13.2671 17.4057 13.2887 17.4316 13.3022C18.0378 13.6039 20.3811 14.916 20.0865 17.6834C20.074 17.8032 20.1698 17.9068 20.2888 17.8888C20.8655 17.8059 22.3492 17.4853 22.8094 16.4866C23.0637 15.9589 23.0637 15.3456 22.8094 14.817Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.4"
                                            d="M7.04459 4.77973C6.81626 4.7329 6.58077 4.70679 6.33543 4.70679C4.07901 4.70679 2.30701 6.84201 2.9123 9.21947C3.31882 10.8216 4.79355 11.8851 6.43661 11.8419C6.60136 11.8374 6.76343 11.8221 6.92013 11.7951C7.03384 11.7753 7.09115 11.6465 7.02668 11.551C6.3999 10.6234 6.04263 9.50765 6.04263 8.30991C6.04263 7.05904 6.43303 5.89462 7.11085 4.93913C7.13234 4.90941 7.14845 4.86348 7.12696 4.82926C7.10906 4.80135 7.07593 4.78694 7.04459 4.77973Z"
                                            fill="currentColor"></path>
                                        <path
                                            d="M3.32156 13.5127C2.21752 13.7297 1.49225 14.1719 1.19139 14.8167C0.936203 15.3453 0.936203 15.9586 1.19139 16.4872C1.65163 17.4851 3.13531 17.8066 3.71195 17.8885C3.83104 17.9065 3.92595 17.8038 3.91342 17.6832C3.61883 14.9167 5.9621 13.6046 6.56918 13.3029C6.59425 13.2885 6.59962 13.2677 6.59694 13.2542C6.59515 13.2452 6.5853 13.2317 6.5656 13.2299C5.25294 13.2047 3.84358 13.3848 3.32156 13.5127Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </div>
                                <div class="progress-detail" bis_skin_checked="1">
                                    <h5 class="mb-2">Empresas</h5>
                                    <h4 class="counter" style="visibility: visible;">
                                        <?= $data['countEnterprise']['total']; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<script src="<?= media() ?>/js/<?= $data["page_functions_js"]?>"></script>
<?php
$content = ob_get_clean();  // Captura todo el contenido generado por la vista

// Incluye el layout principal (app.php) que contiene el <div class="container">
include __DIR__ . '/layouts/app.php';

?>