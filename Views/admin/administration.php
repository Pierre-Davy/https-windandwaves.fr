<?php

use App\Models\entities\Comment;
use App\Models\entities\Post;
use App\Models\entities\User;

if ($_SESSION["user"]['role'] != 'ADMIN') {
    header('location: ../../');
    return;
}
?>


<link rel="stylesheet" href="../assets/styles/default.css">

<div class="administration">
    <h1>Panneau d'administration</h1>
    <a href="../../">
        <button>RETOUR</button>
    </a>


    <h2>Liste des utilisateurs</h2>

    <div class="adminUserDivContainer">

        <?php
        /** @var User $users */
        foreach ($users as $user): ?>
            <div class="adminUserDiv">

                <p>Id numéro : <?= strip_tags($user->getId())  ?></p>
                <p>Email : <?= strip_tags($user->getEmail())  ?></p>
                <p>Pseudo : <?= strip_tags($user->getPseudo())  ?></p>
                <p>Role : <?= strip_tags($user->getRole())  ?></p>
                <p>Compte crée le : <?= strip_tags($user->getcreatedDate())  ?></p>
                <p>IP : <?= strip_tags($user->getIp())  ?></p>
       

                <?php
                $i = 0;
                /** @var Post $messages */
                foreach ($messages as $message):
                    if ($message->getUserId() == $user->getId()) {
                        $i++;
                    }

                    ?>
                <?php endforeach; ?>
                <p>Nombre de message : <a href="messageList/<?= strip_tags($user->getId())  ?>"><?= $i ?></a></p>


                <?php
                $i = 0;
                /** @var Comment $comments */
                foreach ($comments as $comment):

                    if ($comment->getUserId() == $user->getId()) {
                        $i++;
                    }

                    ?>
                <?php endforeach; ?>
                <p>Nombre de commentaire : <a href="commentList/<?= strip_tags($user->getId())  ?>"><?= $i ?></a></p>

                <form action="deleteAccountByAdmin" method="post">
                    <legend>Souhaitez-vous supprimer le compte ?</legend>
                    <input type="hidden" name="idUser" value="<?= strip_tags($user->getId())  ?>">
                    <input type="radio" id="deleteConfirmNo" name="deleteConfirm" value="no" checked>
                    <label for="deleteConfirmNo">Non</label>
                    <input type="radio" id="deleteConfirmYes" name="deleteConfirm" value="yes">
                    <label for="deleteConfirmYes">Oui</label>
                    <input type="submit" value="Supprimer le compte">
                </form>

            </div>
        <?php endforeach; ?>
    </div>
</div>
