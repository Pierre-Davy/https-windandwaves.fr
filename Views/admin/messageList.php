<?php
if($_SESSION["user"]['role'] != 'ADMIN'){
    header('location: ../../');
    return;
}
?>

<link rel="stylesheet" href="../../assets/styles/default.css">

<div class="administration">
<h1>Liste des messages de l'utilisateur</h1>
    <div class="adminMessageDivContainer">
<?php
/** @var \App\Models\entities\Post $messages */
foreach($messages as $message):

    /** @var \App\Models\entities\Post $idAuthor */
    if($message->getUserId() == $idAuthor){
        ?><div class="adminMessageDiv">
        <table style="border-collapse: collapse;" border="1">
            <tbody>
            <tr>
                <td class="tdtitle">ID :</td>
                <td><?= strip_tags($message->getId()) ?></td>
            </tr>
            <tr>
                <td class="tdtitle">Lieu :</td>
                <td><?= strip_tags($message->getLieu()) ?></td>
            </tr>
            <tr>
                <td class="tdtitle">Content :</td>
                <td><?= htmlspecialchars($message->getContent()) ?></td>
            </tr>
            <tr>
                <td class="tdtitle">Créé le :</td>
                <td><?= strip_tags($message->getCreatedAt()) ?></td>
            </tr>
            <tr>
                <td class="tdtitle">Author</td>
                <td><?= strip_tags($message->getAuthor()) ?></td>
            </tr>
            </tbody>
        </table>

        <a href="../../post/updateMessage/<?= strip_tags($message->getId())  ?>">
            <button class="buttonForum">Modifier ce message</button>
        </a>

        </div>
        <?php

    };

    ?>
<?php endforeach; ?>
    </div>
<a href="../administration"><button>Retour Administration</button></a>
</div>

