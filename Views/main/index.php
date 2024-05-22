<header>
    <img src="public/assets/img/logo.webp" alt="logo du site web">
    <div aria-label="presentation">
        <h1>WIND AND WAVES</h1>
        <p>Bienvenue sur l'<em>application météo</em> destinée aux sports nautiques, aux alentours de
            <strong>Brest</strong>.</p>
    </div>
    <div aria-label="interface de connexion" class="logInterface">
        <?php

        if (empty($_SESSION['user'])): ?>
            <a href="public/user/formLogin" class="button-53" role="button">
                Login
            </a>
        <?php endif; ?>

        <?php if (!empty($_SESSION['user'])): ?>
            <div class="alert-danger" role="alert">
                <p class="connectionText">Connecté en tant que
                    <strong><?= strip_tags($_SESSION['user']["pseudo"]) ?></strong></p>
            </div>
            <a href="public/user/monCompte" class="button-53" role="button">
                Mon compte
            </a>
            <?php if (!empty($_SESSION['user']["role"] === "ADMIN")): ?>
                <a href="public/admin/administration" class="button-53" role="button">
                    Administration
                </a>
            <?php endif; ?>
            <a href="public/user/logout" class="button-53" role="button">
                Se déconnecter
            </a>
        <?php endif; ?>
    </div>
</header>
<main class="mainContainer" style="border: 2px solid orange">
    <div class="leftContainer">
        <div class="flexCenterWrap" aria-label="choice location">
            <button class="choiceLocation button-53 selected" id="le_conquet">LE CONQUET <br> Blancs
                Sablons
            </button>
            <button class="choiceLocation button-53" id="plougonvelin">
                PLOUGONVELIN <br> Treiz Hir
            </button>
            <button class="choiceLocation button-53" id="landeda">
                LANDEDA <br> Ste Marguerite
            </button>
            <button class="choiceLocation button-53" id="ploudalmezeau">
                PLOUDALMEZEAU <br> Treompan
            </button>
        </div>
        <div class="hourSelection">
            <button id="previousHour" class="button-53" aria-label="heure précédente">
                <i class="fa-solid fa-arrow-left"></i> <span>&nbsp;</span>Heure précédente
            </button>
            <button id="nextHour" class="button-53" aria-label="heure suivante">
                Heure suivante <span>&nbsp;</span> <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
        <div class="mainData">
            <div class="datasContainerBlur">
                <div><h2>Données générales</h2></div>
                <div class="flexCenterWrap">
                    <div class="containid" id="dateContainer"></div>
                    <div class="containid" id="hourContainer"></div>
                    <div class="containid" id="temperature"></div>
                    <div class="containid" id="situation"></div>
                </div>
            </div>
            <div class="datasContainerBlur">
                <div><h2>Caractéristiques du vent</h2></div>
                <div class="flexCenterWrap">
                    <div class="containid" id="windspeed"></div>
                    <div class="containid" id="windgust"></div>
                    <div class="containid" id="winddirection"></div>
                </div>
                <div id="alertWindDirection"></div>
            </div>
            <div class="datasContainerBlur">
                <div><h2>Caractéristiques de la mer</h2></div>
                <div class="flexCenterWrap">
                    <div class="containid" id="waveHeight"></div>
                    <div class="containid" id="waveDirection"></div>
                    <div class="containid" id="wavePeriod"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="rightContainer">
        <div id="buttonViewMap" aria-label="choice view map">
            <button id="satelliteView" class="button-53" aria-label="vue satellite">Vue satellite</button>
            <button id="streetView" class="button-53" aria-label="vue carte">Vue carte</button>
        </div>
        <div id="canvasContainer"></div>
    </div>
</main>

<section class="forum" id="forumDivision">
    <h2>Bienvenue sur le Forum </h2>

    <?php if (empty($_SESSION['user'])): ?>
        <a href="public/user/formLogin" class="button-53" role="button">
            Se connecter pour écrire un message
        </a>
    <?php endif; ?>
    <?php if (!empty($_SESSION['user'])): ?>
        <a href="public/post/formMessage" class="button-53" role="button">
            Ecrire un message
        </a>
    <?php endif; ?>

    <div class="forumItemContainer" id="forumItemContainer">

        <?php
        /** @var Post $messages */
        foreach ($messages as $message) {
            ?>
            <div class="forumItem">
                <?php
                if (!empty($_SESSION['user'])) {
                    if (($_SESSION["user"]["pseudo"] == $message->getAuthor()) || ($_SESSION["user"]["role"] == "ADMIN")) {
                        ?>
                        <a href="public/post/updateMessage/<?= $message->getId() ?>" class="buttonForum">
                            Modifier ce message
                        </a>
                        <?php
                    }
                }
                ?>

                <h3>Spot : <u><?= strip_tags($message->getLieu()) ?></u></h3>
                <br>
                <?php
                /** @var Tag $tags */
                foreach ($tags as $tag) {
                    if ($message->getId() == $tag->getMessageId()) {
                        ?>
                        <i>#<?= strip_tags($tag->getName()) ?></i>
                        <?php
                    }
                }
                ?>
                <br>
                <h4>Auteur : <u><?= strip_tags($message->getAuthor()) ?></u></h4>
                <br>
                <p><?= htmlspecialchars($message->getContent()) ?></p>
                <br>
                <i>
                    <time datetime="2018-07-07T20:00:00">Posté le : <?= strip_tags($message->getCreatedAt()) ?></time>
                </i>
                <?php if (!empty($_SESSION['user'])): ?>
                    <a href="public/comment/formComment/<?= strip_tags($message->getId()) ?>" class="buttonForum">
                        Répondre à ce message
                    </a>
                <?php endif; ?>
                <?php /** @var Comment $comments */
                foreach ($comments as $comment) {
                    if ($comment->getPostId() == $message->getId()) {
                        ?>
                        <div class="commentItem">


                            <h4>Auteur : <u><?= strip_tags($comment->getAuthor()) ?></u> <?php
                                if (!empty($_SESSION['user'])) {
                                    if (($_SESSION["user"]["pseudo"] == $comment->getAuthor()) || ($_SESSION["user"]["role"] == "ADMIN")) {
                                        ?>
                                        <a href="public/comment/updateComment/<?= strip_tags($comment->getId()) ?>" class="buttonForum">
                                            Modifier le commentaire
                                        </a>
                                        <?php
                                    }
                                }
                                ?></h4>
                            <br>
                            <p><?= htmlspecialchars($comment->getContent()) ?></p>
                            <br>
                            <i>
                                <time datetime="2018-07-07T20:00:00">Posté le : <?= strip_tags($comment->getCreatedAt()) ?></time>
                            </i>
                        </div>
                        <?php
                    }
                } ?>
            </div>
            <?php
        }
        ?>
    </div>
</section>
<footer>
    <h3>Pensez aux numéros d'urgence 17 (Police/Gendarmerie), 18 (Pompier), 15 (SAMU), 196 (CROSS)</h3>
   <div>
    <img src="public/assets/img/logo.webp" alt="logo du site web">
    <a role="button" aria-label="bouton de contact par email" href="mailto:admin@windandwave.fr">
        <div  class="contact"><p>Contacter l'administrateur </p> <span class="material-symbols-outlined">mail</span>
        </div>
    </a>
   </div>
    <a href="public/main/mentions">
        Afficher les mentions légales.
    </a>
    <p>2024 &copy; Windandwaves. Tous droits réservés.</p>
</footer>

<script src="public/assets/js/app.js"></script>


