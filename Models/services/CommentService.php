<?php

namespace App\Models\services;

use App\Models\dao\CommentDao;


class CommentService
{
    private CommentDao $commentDao;


    public function __construct()
    {
        $this->commentDao = new CommentDao();

    }

    public function createComment($data, $idMessage)
    {
        // On vérifie si les variables existent et sont remplies
        if (isset($data['contentMessage']) && !empty($data['contentMessage']) && isset($data['idUser']) && !empty($data['idUser'])) {
            // On clean les données (faille xss)
            //$content = htmlspecialchars($data['contentMessage']);
            $content = $data['contentMessage'];
            //$idUser = htmlspecialchars($data['idUser']);
            $idUser = $data['idUser'];
            //$idMessage = htmlspecialchars($idMessage);
            $idMessage = $idMessage;
            // On supprime les espaces inutiles dans le content
            $content = preg_replace('/\s\s+/', ' ', $content);
            $content = trim($content);

            // On récupère l'addresse IP de l'utilisateur
            $ip = $_SERVER['REMOTE_ADDR'];

            $this->commentDao->createComment($idMessage, $idUser, $content, $ip);
        } else {
            $_SESSION["error"] = "Une erreur est survenue lors de l'enregistrement du message";
            header("location: ../../");
            return;
        }
    }

    public function updateComment($idComment, $data)
    {
        // On vérifie si les variables existent et sont remplies
        if (isset($data['contentMessage']) && !empty($data['contentMessage']) && isset($data['idAuthor']) && !empty($data['idAuthor'])) {
            // On clean les données (faille xss)
            //$content = htmlspecialchars($data['contentMessage']);
            $content = $data['contentMessage'];
            //$idAuthor = htmlspecialchars($data['idAuthor']);
            $idAuthor = $data['idAuthor'];
            //$idComment = htmlspecialchars($idComment);
            $idComment = $idComment;
            // On vérifie que l'utilisateur est toujours l'auteur du commentaire (modif interne en console)
            if (($idAuthor != $_SESSION["user"]["id"]) && ($_SESSION["user"]["role"] != "ADMIN")) {
                $_SESSION["error"] = "Une erreur est survenue lors de la modification du message";
                header("location: ../../");
                return;
            }
            // On supprime les espaces inutiles dans le content
            $content = preg_replace('/\s\s+/', ' ', $content);
            $content = trim($content);

            // On récupère l'addresse IP de l'utilisateur
            $ip = $_SERVER['REMOTE_ADDR'];

            $this->commentDao->updateComment($idAuthor, $idComment, $content, $ip);
        }
    }

    public function getAllComments(): array
    {
        $comments = $this->commentDao->getComments();
        return $comments;
    }

   public function getComment($idComment)
    {
        $idComment = htmlspecialchars($idComment);
        $comment = $this->commentDao->getComment($idComment);
        return $comment;
    }

   public function deleteComment($idComment, $data)
    {
        // On vérifie si les variables existent et sont remplies
        if (isset($data['idAuthor']) && !empty($data['idAuthor'])) {
            // On clean les données (faille xss)
            $idAuthor = htmlspecialchars($data['idAuthor']);
            $idComment = htmlspecialchars($idComment);

            // On vérifie que l'utilisateur est toujours bien l'auteur du message (modif interne en console)
            if (($idAuthor != $_SESSION["user"]["id"]) && ($_SESSION["user"]["role"] != "ADMIN")) {
                $_SESSION["error"] = "Une erreur est survenue lors de la suppression du message";
                header("location: ../../");
                return;
            }

            $this->commentDao->deleteComment($idAuthor, $idComment);
        }

    }
}



