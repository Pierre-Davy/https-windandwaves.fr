<?php
if(!isset($_SESSION["user"])){
    header('location: ../../');
    return;
}
?>

<link rel="stylesheet" href="../../assets/styles/form.css">
<link rel="stylesheet" href="../../assets/styles/default.css">

<div class="blockFormContainer">
    <h1>METTRE A JOUR LE COMMENTAIRE</h1>
    <?php /** @var \App\Models\entities\Comment $comment */ ?>
    <form action="../updateCommentPost/<?= strip_tags($comment->getId()) ?>" method="post">
    <input type="hidden" name="idAuthor" value="<?= strip_tags($comment->getUserId()) ?>">
    <label for="contentMessage">Entrez votre message ici</label>
    <textarea name="contentMessage" id="contentMessage" cols="30" rows="10"><?= htmlspecialchars($comment->getContent()) ?></textarea>
       <input type="submit" value="Envoyez votre message">
</form>

<form class="formDelete" action="../deleteCommentPost/<?= strip_tags($comment->getId()) ?>" method="post">
    <input type="hidden" name="idAuthor" value="<?= strip_tags($comment->getUserId()) ?>">
    <input class="inputDelete" type="submit" value="Supprimer le message">
</form>

<a href="../../../"> <-- Retour à la page d'accueil</a>
</div>