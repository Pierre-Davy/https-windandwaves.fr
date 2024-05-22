<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" sizes="32x32" href="public/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="public/favicon-16x16.png">

    <title>Wind And Waves - Application météo des plages et sites de sports nautiques aux alentours de Brest.</title>


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