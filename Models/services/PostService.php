<?php

namespace App\Models\services;

use App\Models\dao\PostDao;
use App\Models\dao\TagDao;


class PostService
{
    private PostDao $messagerieDao;
    private TagDao $tagDao;


    public function __construct(){
        $this->messagerieDao = new PostDao();
        $this->tagDao = new TagDao();
    }

    public function createMessage($data, $idUser)
    {
        $tags = [];
        // On ajoute les tags selectionnés dans un tableau
        for ($i = 0; $i < 10 ; $i++){
            if(isset($data["tagValue".$i])){
                //$tags[] += htmlspecialchars($data["tagValue".$i]);
                $tags[] += $data["tagValue".$i];
            }
        }

            // On vérifie si les variables existent et sont remplies
        if (isset($data['lieuMessage']) && !empty($data['lieuMessage']) && isset($data['contentMessage']) && !empty($data['contentMessage'])) {
            // On clean les données (faille xss et injection sql)
            //$lieuMessage = htmlspecialchars($data['lieuMessage']);
            $lieuMessage = $data['lieuMessage'];
            //$content = htmlspecialchars($data['contentMessage']);
            $content = $data['contentMessage'];
            //$idUser = htmlspecialchars($idUser);
            $idUser = $idUser;
            // On supprime les espaces inutiles dans le content
            $content = preg_replace('/\s\s+/', ' ', $content);
            $content = trim($content);

            // On récupère l'addresse IP de l'utilisateur
            $ip = $_SERVER['REMOTE_ADDR'];

            $this->messagerieDao->createMessage($idUser, $lieuMessage, $content, $tags, $ip);

        } else {
            $_SESSION["error"] = "Une erreur est survenue lors de l'enregistrement du message";
            header("location: ../../");
            return;
        }
    }

    public function updateMessage($idMessage, $data)
    {
        $tags = [];

        for ($i = 0; $i < 10 ; $i++){
            if(isset($data["tagValue".$i])){
                //$tags[] += htmlspecialchars($data["tagValue".$i]);
                $tags[] += $data["tagValue".$i];
            }
        }

        // On vérifie si les variables existent et sont remplies
        if (isset($data['lieuMessage']) && !empty($data['lieuMessage']) && isset($data['contentMessage']) && !empty($data['contentMessage'])&& isset($data['idAuthor']) && !empty($data['idAuthor'])) {
            // On clean les données (faille xss et injection sql)
            //$lieuMessage = htmlspecialchars($data['lieuMessage']);
            $lieuMessage = $data['lieuMessage'];
            //$content = htmlspecialchars($data['contentMessage']);
            $content = $data['contentMessage'];
            //$idAuthor = htmlspecialchars($data['idAuthor']);
            $idAuthor = $data['idAuthor'];
            //$idMessage = htmlspecialchars($idMessage);
            $idMessage = $idMessage;
            // On vérifie que l'utilisateur est toujours bien l'auteur du message ou l'admin (modif interne en console)
            if(($idAuthor != $_SESSION["user"]["id"]) && ($_SESSION["user"]["role"] != "ADMIN")){
                $_SESSION["error"] = "Une erreur est survenue lors de la modification du message";
                header("location: ../../");
                return;
            }
            // On supprime les espaces inutiles dans le content
            $content = preg_replace('/\s\s+/', ' ', $content);
            $content = trim($content);

            // On récupère l'addresse IP de l'utilisateur
            $ip = $_SERVER['REMOTE_ADDR'];

            $this->messagerieDao->updateMessage($idAuthor,$idMessage, $lieuMessage, $content, $tags, $ip);
        }
    }

    public function getAllMessages(): array
    {
        $messages = $this->messagerieDao->getMessages();
        return $messages;
    }
    public function getMessage($idMessage)
    {
        $idMessage = htmlspecialchars($idMessage);
        $message = $this->messagerieDao->getMessage($idMessage);
        return $message;
    }

    public function deleteMessage($idMessage, $data)
    {
        // On vérifie si les variables existent et sont remplies
        if (isset($data['idAuthor']) && !empty($data['idAuthor'])) {
            // On clean les données
            $idAuthor = htmlspecialchars($data['idAuthor']);
            $idMessage = htmlspecialchars($idMessage);

            // On vérifie que l'utilisateur est toujours bien l'auteur du message ou l'admin (modif interne en console)
            if(($idAuthor != $_SESSION["user"]["id"]) && ($_SESSION["user"]["role"] != "ADMIN")){
                $_SESSION["error"] = "Une erreur est survenue lors de la suppression du message";
                header("location: ../../");
                return;
            }

            $this->messagerieDao->deleteMessage($idAuthor,$idMessage);
        }

    }


}