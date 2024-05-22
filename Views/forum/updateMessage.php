<?php
if(!isset($_SESSION["user"])){
    header('location: ../../');
    return;
}
?>

<link rel="stylesheet" href="../../assets/styles/form.css">
<link rel="stylesheet" href="../../assets/styles/default.css">

<div class="blockFormContainer">
<h1>METTRE A JOUR LE MESSAGE</h1>
    <?php /** @var \App\Models\entities\Post $message */ ?>
<form action="../updateMessagePost/<?= strip_tags($message->getId()) ?>" method="post">
    <div>
        <input type="hidden" name="idAuthor" value="<?= strip_tags($message->getUserId()) ?>">
        <label for="lieuMessage">De quel spot souhaitez-vous discuter ?</label>
        <select id="lieuMessage" name="lieuMessage" >
            <option value="<?= strip_tags($message->getLieu()) ?>"><?= strip_tags($message->getLieu()) ?></option>
            <option value="Blancs Sablons (Le Conquet)">Blancs Sablons (Le Conquet)</option>
            <option value="Treiz Hir (Plougonvelin)">Treiz Hir (Plougonvelin)</option>
            <option value="Sainte Marguerite (Landeda)">Sainte Marguerite (Landeda)</option>
            <option value="Tréompan (Ploudalmezeau)">Tréompan (Ploudalmezeau)</option>
            <option value="Tous les spots">Tous les spots</option>
        </select>
    </div>
    <div>
        <label for="contentMessage">Entrez votre message ici</label>
        <textarea name="contentMessage" id="contentMessage" cols="30" rows="10"><?= htmlspecialchars($message->getContent()) ?></textarea>
    </div>   <div>

        <legend>Choisissez un ou plusieurs Tags vous correspondant :</legend>
        <div class="checkboxContainer">
            <?php /** @var \App\Models\Entities\Tag $tags */
            foreach ($tags as $tag){
                ?>
                <div>
                    <input type="checkbox" id="<?= strip_tags($tag->getName())  ?>" name="tagValue<?= strip_tags($tag->getId()) ?>" value="<?= strip_tags($tag->getId()) ?>" />
                    <label for="<?= strip_tags($tag->getName())  ?>"><?= strip_tags($tag->getName())  ?></label>
                </div>
                <?php
            }?>
        </div>
    </div>
    <input type="submit" value="Envoyer votre message">
</form>

<form class="formDelete" action="../deleteMessagePost/<?= strip_tags($message->getId()) ?>" method="post">
    <input type="hidden" name="idAuthor" value="<?= strip_tags($message->getUserId()) ?>">
    <input class="inputDelete" type="submit"  value="Supprimer votre message">
</form>

<a href="../../../"> <-- Retour à la page d'accueil</a>
</div>




