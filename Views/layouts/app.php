<!doctype html>
<html lang="en" dir="ltr" data-bs-theme="dark" data-bs-theme-color="theme-color-default">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo isset($title) ? $title : 'Banking AI'; ?></title>
		
    <?php include __DIR__ . '/header.php'; ?>
</head>

<body>
    <!-- Obrtener la ruta actual -->
    <?php
        $currentRoute = strtok($_SERVER['REQUEST_URI'], '?');
    ?>
    <!-- loader Start -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>
    <!-- loader END -->

    <?php include __DIR__ . '/aside.php'; ?>

    <main class="main-content">
        
        <?php include __DIR__ . '/navbar.php'; ?>
        
        <!-- Aquí se cargará el contenido de cada vista -->
        <div class="container">
            <?php if(isset($content)) echo $content; ?>
        </div>

        <!-- Footer Section Start -->
        <footer class="footer">
            <?php include __DIR__ . '/footer.php'; ?>
        </footer>
        <!-- Footer Section End -->
    </main>

    <?php  include __DIR__ . '/scripts.php'; ?>

    <?php if(isset($script)) echo $script; ?>
</body>
</html>
