<!doctype html>
<html lang="en" dir="ltr" data-bs-theme="dark" data-bs-theme-color="theme-color-default">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Banking AI</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= media() ?>/images/favicon.ico">
    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="<?= media() ?>/css/core/libs.min.css">
    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="<?= media() ?>/css/hope-ui.min.css?v=5.0.0">
    <!-- Custom Css -->
    <link rel="stylesheet" href="<?= media() ?>/css/custom.min.css?v=5.0.0">
    <!-- Customizer Css -->
    <link rel="stylesheet" href="<?= media() ?>/css/customizer.min.css?v=5.0.0">
    <!-- RTL Css -->
    <link rel="stylesheet" href="<?= media() ?>/css/rtl.min.css?v=5.0.0">
</head>

<body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <!-- loader Start -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body">
            </div>
        </div>
    </div>
    <!-- loader END -->
    <div class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                                <div class="card-body z-3 px-md-0 px-lg-4">
                                    <div class="text-center">
                                        <img class="mb-4" width="150" src="<?= media() ?>/images/auth/logo-adn-blanco.png">
                                        <p class="text-center">Inicie sesión para mantenerse informado.</p>
                                    </div>
                                    <form class="login-form" name="formLogin" id="formLogin" action="">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="usuario" class="form-label">Usuario</label>
                                                    <input type="text" class="form-control" id="txtUsername"
                                                        name="txtUsername" aria-describedby="Usuario"
                                                        placeholder="Usuario">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="password" class="form-label">Contraseña</label>
                                                    <input type="password" class="form-control" id="txtPassword"
                                                        name="txtPassword" aria-describedby="Clave" placeholder="Clave">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center mt-5">
                                            <button type="submit" class="btn btn-primary">Iniciar Sessión</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
                    <img src="<?= media() ?>/images/auth/tipos-de-banco.jpeg"
                        class="img-fluid gradient-main animated-scaleX" alt="images">
                </div>
            </div>
        </section>
    </div>

    <!-- Library Bundle Script -->
    <script src="<?= media() ?>/js/core/libs.min.js"></script>
    <!-- External Library Bundle Script -->
    <script src="<?= media() ?>/js/core/external.min.js"></script>
    <!-- Widgetchart Script -->
    <script src="<?= media() ?>/js/charts/widgetcharts.js"></script>
    <!-- mapchart Script -->
    <script src="<?= media() ?>/js/charts/vectore-chart.js"></script>
    <script src="<?= media() ?>/js/charts/dashboard.js"></script>
    <!-- fslightbox Script -->
    <script src="<?= media() ?>/js/plugins/fslightbox.js"></script>
    <!-- Settings Script -->
    <script src="<?= media() ?>/js/plugins/setting.js"></script>
    <!-- Slider-tab Script -->
    <script src="<?= media() ?>/js/plugins/slider-tabs.js"></script>
    <!-- Form Wizard Script -->
    <script src="<?= media() ?>/js/plugins/form-wizard.js"></script>
    <!-- AOS Animation Plugin-->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- App Script -->
    <script src="<?= media() ?>/js/hope-ui.js" defer></script>

    <script src="<?= media() ?>/js/functions_login.js"></script>

    <script>
    const base_url = "<?= base_url(); ?>";
    </script>
</body>

</html>