<?php

use App\Models\entities\Comment;
use App\Models\entities\Post;

if (!isset($_SESSION["user"])) {
    header('location: ../../');
    return;
}
?>

<link rel="stylesheet" href="../assets/styles/form.css">
<link rel="stylesheet" href="../assets/styles/default.css">

<div class="infoPage">
    <h1>Mes informations</h1>
    <div class="infos">
        <div class="infosText">
            <h3>Mon Email de connexion :</h3><?= strip_tags($_SESSION["user"]["email"])  ?>
            <h3>Mon Pseudo :</h3><?= strip_tags($_SESSION["user"]["pseudo"])  ?>
            <h3>Mon Role :</h3><?= strip_tags($_SESSION["user"]["role"])  ?>
            <?php
            $i = 0;
            /** @var Post $messages */
            foreach ($messages as $message):
                if ($message->getUserId() == $_SESSION["user"]["id"]) {
                    $i++;
                }

                ?>
            <?php endforeach; ?>
            <h3>Nombre de message : <?= $i ?></h3>

            <?php
            $i = 0;
            /** @var Comment $comments */
            foreach ($comments as $comment):

                if ($comment->getUserId() == $_SESSION["user"]["id"]) {
                    $i++;
                }

                ?>
            <?php endforeach; ?>
            <h3>Nombre de commentaire : <?= $i ?></h3>
        </div>
        <div class="blockFormContainer">
            <form action="resetPassword" method="post">
                <div>
                    <label for="actualPassword">Entrez votre mot de passe actuel</label>
                    <input type="password" name="actualPassword">
                </div>
                <div class="password-container">
                    <label for="password">Entrez votre mot de passe</label>
                    <input type="password" autocomplete="off" name="password" id="password">
                    <p id="progress-bar"></p>
                    <span></span>
                </div>
                <div class="confirm-container">
                    <label for="passwordConfirm">Confirmer la saisie du mot de passe</label>
                    <input type="password" autocomplete="off" name="passwordConfirm" id="confirm">
                    <span></span>
                </div>
                <input type="submit" value="Modifier votre mot de passe">
            </form>
            <form class="formDelete" action="deleteAccount" method="post">
                <fieldset>
                    <legend>Souhaitez-vous supprimer votre compte ?</legend>
                    <input type="radio" id="deleteConfirmNo" name="deleteConfirm" value="no" checked>
                    <label for="deleteConfirmNo">Non</label>
                    <input type="radio" id="deleteConfirmYes" name="deleteConfirm" value="yes">
                    <label for="deleteConfirmYes">Oui</label>
                </fieldset>
                <input class="inputDelete" type="submit" value="Supprimer mon compte">
            </form>
        </div>
    </div>

    <a href="../../">Retour à la page d'accueil</a>
</div>
<script src="../assets/js/form.js"></script>
