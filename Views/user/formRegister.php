<link rel="stylesheet" href="../assets/styles/form.css">
<link rel="stylesheet" href="../assets/styles/default.css">

<div class="blockFormContainer">
    <h1>S'ENREGISTRER</h1>
<form action="register" method="post">
    <div class="pseudo-container">
        <label for="pseudo">Entrez votre pseudo</label>
        <input type="text" autocomplete="off" name="pseudo" id="pseudo">
        <span></span>
    </div>
    <div class="email-container">
        <label for="email">Entrez votre email</label>
        <input type="text" autocomplete="off" name="email" id="email">
        <span>Email incorrect</span>
    </div>
    <div class="password-container">
        <label for="password">Entrez votre mot de passe</label>
        <input type="password" autocomplete="off" name="password" id="password">
        <p id="progress-bar"></p>
        <span></span>
    </div>
    <div class="confirm-container">
        <label for="passwordConfirm">Confirmez la saisie du mot de passe</label>
        <input type="password" autocomplete="off" name="passwordConfirm" id="confirm">
        <span></span>
    </div>
    <input type="submit">
</form>

<a href="formLogin">Vous avez déjà un compte utilisateur ? Vous connecter.</a>

<a href="../../"> <-- Retour à la page d'accueil</a>
</div>
<script src="../assets/js/form.js"></script>





