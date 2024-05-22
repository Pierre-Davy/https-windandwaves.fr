<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/assets/styles/index.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="icon" type="image/png" sizes="32x32" href="public/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="public/favicon-16x16.png">
    <title>Wind And Waves - Météo des spots de sports nautiques - Secteur de Brest</title>
    <meta name="google-site-verification" content="1bxrbIultV2PpRLK7lqFxYFbmk7vyi1bg4qSAl0d98o">

    <meta name="description" content="Wind And Waves est une application de recensement et d’affichage des données météorologiques spécifiques aux différents sites de sports nautiques, principalement liés aux vents, aux alentours de Brest.">

    <link rel="canonical" href="https://windandwaves.fr/" />

    <meta property="og:title" content="Wind And Waves">
    <meta property="og:url" content="https://windandwaves.fr">
    <meta property="og:image" content="https://windandwaves.fr/public/assets/img/logo.png">
    <meta name="og:description" content="Venez découvrir l'application météo vagues et vent des spots Brestois">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Wind and Waves">
    <meta name="twitter:description" content="Venez découvrir l'application météo vagues et vent des spots Brestois">
    <meta name="twitter:image" content="https://windandwaves.fr/public/assets/img/logo.png">
    <meta name="twitter:url" content="https://windandwaves.fr">

    <script src="https://cdn.maptiler.com/maptiler-sdk-js/v1.2.0/maptiler-sdk.umd.min.js"></script>
    <link
            href="https://cdn.maptiler.com/maptiler-sdk-js/v1.2.0/maptiler-sdk.css"
            rel="stylesheet"
    >
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
            integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
    >
</head>

<body>
    <div class="container">
        <?php if(!empty($_SESSION['error'])): ?>
            <div class="alert-danger" role="alert">
                <h1><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></h1>
            </div>
        <?php endif; ?>
        <?php if(!empty($_SESSION['message'])): ?>
            <div class="alert-success" role="alert">
                <h1><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></h1>
            </div>
        <?php endif; ?>
        <?= $contenu ?>
    </div>


</body>

</html>