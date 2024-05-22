<?php
if($_SESSION["user"]['role'] != 'ADMIN'){
    header('location: ../../');
    return;
}
?>
<link rel="stylesheet" href="../../assets/styles/default.css">

<div class="administration">
    <h1>Liste des commentaires de l'utilisateur</h1>
    <div class="adminMessageDivContainer">
<?php
/** @var \App\Models\entities\Comment $comments */
foreach($comments as $comment):
    /** @var \App\Models\entities\Comment $idAuthor */
    if($comment->getUserId() == $idAuthor){
        ?><div class="adminMessageDiv">
        <table style="border-collapse: collapse; "border=1">
            <tbody>
            <tr>
                <td>ID :</td>
                <td><?= strip_tags($comment->getId()) ?></td>
            </tr>
            <tr>
                <td>Content :</td>
                <td><?= htmlspecialchars($comment->getContent()) ?></td>
            </tr>
            <tr>
                <td>Créé le :</td>
                <td><?=  strip_tags($comment->getCreatedAt()) ?></td>
            </tr>
            <tr>
                <td>Author</td>
                <td><?= strip_tags($comment->getAuthor()) ?></td>
            </tr>
            </tbody>
        </table>

        <a href="../../comment/updateComment/<?= strip_tags($comment->getId())  ?>">
            <button class="buttonForum">Modifier le commentaire</button>
        </a>

        </div>
        <?php

    };

    ?>
<?php endforeach; ?>
    </div>
    <a href="../administration"><button>Retour Administration</button></a>
</div>