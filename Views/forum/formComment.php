<?php
if(!isset($_SESSION["user"])){
    header('location: ../../');
    return;
}
?>

<link rel="stylesheet" href="../../assets/styles/form.css">
<link rel="stylesheet" href="../../assets/styles/default.css">


<div class="blockFormContainer">
<h1>REPONDRE AU MESSAGE</h1>
<form action="../formCommentPost/<?= strip_tags($idMessage) ?>" method="post">
    <input type="hidden" name="idUser" value="<?= strip_tags($_SESSION["user"]["id"]) ?>">
    <label for="contentMessage">Entrez votre message ici</label>
    <textarea name="contentMessage" id="contentMessage" cols="30" rows="10"></textarea>
    <input type="submit" value="Envoyez votre message">
</form>
    <a href="../../">Retour à la page d'accueil</a>
</div>