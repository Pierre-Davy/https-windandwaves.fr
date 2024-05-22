<?php

namespace App\Models\dao;

use App\Models\entities\Post;
use App\Models\entities\User;
use PDO;
use PDOException;

class PostDao extends Db
{
    public function createMessage($idUser, $lieuMessage, $content, $tags, $ip)
    {
            $stmt = $this->instance->prepare("INSERT INTO `messages` (`lieu`, `content`, `users_id`, `ip_author`) VALUES (:lieu, :content, :userId, :ip)");
            $stmt->bindParam("lieu", $lieuMessage, PDO::PARAM_STR);
            $stmt->bindParam("content", $content, PDO::PARAM_STR);
            $stmt->bindParam("userId", $idUser, PDO::PARAM_INT);
            $stmt->bindParam("ip", $ip, PDO::PARAM_STR);
            $res = $stmt->execute();

            // On insert le Tag en base de données
            // On récupère l'ID du dernier message créé
            $newIdMessage = $this->instance->lastInsertId();

            // On boucle sur le tableau des tags pour inserer en base de données
            for ($i = 0; $i< count($tags); $i++){
                $stmt = $this->instance->prepare("INSERT INTO `messages_tags`(`id_messages`, `id_tags`) VALUES (:idMessage,:idTag)");
                $stmt->bindParam("idMessage", $newIdMessage, PDO::PARAM_INT);
                $stmt->bindParam("idTag", $tags[$i], PDO::PARAM_INT);
                $res = $stmt->execute();
            }


            $_SESSION["message"] = "Enregistrement réussi.";
            header("location: ../../../");

            if (!$res) {
                throw new PDOException($stmt->errorInfo()[2]);
            }
    }
    public function updateMessage(string $idAuthor, string $idMessage, string $lieuMessage, string $content, $tags, $ip)
    {
        $stmt = $this->instance->prepare("UPDATE `messages` SET `lieu`=:lieu, `content`=:content, `ip_author`=:ip WHERE `id`=:idMessage AND `users_id`=:idAuthor");
        $stmt->bindParam("lieu", $lieuMessage, PDO::PARAM_STR);
        $stmt->bindParam("content", $content, PDO::PARAM_STR);
        $stmt->bindParam("ip", $ip, PDO::PARAM_STR);
        $stmt->bindParam("idMessage", $idMessage, PDO::PARAM_INT);
        $stmt->bindParam("idAuthor", $idAuthor, PDO::PARAM_INT);
        $res = $stmt->execute();

        // On supprime tous les tags du tableau sur l'idMessage
        $stmt = $this->instance->prepare("DELETE FROM `messages_tags` WHERE `id_messages`=:idMessage");
        $stmt->bindParam("idMessage", $idMessage, PDO::PARAM_INT);
        $res = $stmt->execute();

        // On boucle sur le tableau des tags pour inserer en base de données
        for ($i = 0; $i< count($tags); $i++){
            $stmt = $this->instance->prepare("INSERT INTO `messages_tags`(`id_messages`, `id_tags`) VALUES (:idMessage,:idTag)");
            $stmt->bindParam("idMessage", $idMessage, PDO::PARAM_INT);
            $stmt->bindParam("idTag", $tags[$i], PDO::PARAM_INT);
            $res = $stmt->execute();
        }


        $_SESSION["message"] = "Enregistrement réussi.";
        header("location: ../../../");

        if (!$res) {
            throw new PDOException($stmt->errorInfo()[2]);
        }
    }

    public function deleteMessage($idAuthor, $idMessage)
    {
        $stmt = $this->instance->prepare("DELETE FROM `messages` WHERE `id`=:idMessage AND `users_id`=:idAuthor");
        $stmt->bindParam("idMessage", $idMessage, PDO::PARAM_INT);
        $stmt->bindParam("idAuthor", $idAuthor, PDO::PARAM_INT);
        $res = $stmt->execute();
        $_SESSION["message"] = "Suppression réussie.";
        header("location: ../../../");

        if (!$res) {
            throw new PDOException($stmt->errorInfo()[2]);
        }

    }

    public function getMessages(): array
    {
        $stmt = $this->instance->prepare("SELECT messages.*, users.pseudo FROM `messages`, users WHERE messages.users_id = users.id  ORDER BY messages.id DESC LIMIT 50");
        $res = $stmt->execute();

        if ($res) {
            $messages = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
                    $messages[] = $this->createObjectFromFields($row);
            }
            return $messages;
        } else {
            throw new PDOException($stmt->errorInfo()[2]);
        }

    }

    public function getMessage($idMessage)
    {
        $stmt = $this->instance->prepare("SELECT messages.*, users.pseudo FROM messages, users WHERE messages.users_id = users.id AND messages.id = :idMessage");
        $stmt->bindParam("idMessage", $idMessage, PDO::PARAM_INT);
        $stmt->execute();
        $message = $stmt->fetch(PDO::FETCH_ASSOC);



        if ($message) {
            return $this->createObjectFromFields($message);
        } else {
            throw new PDOException($stmt->errorInfo()[2]);
        }
    }

    public function createObjectFromFields($fields): Post
    {
        $message = new Post();

        $message->setId($fields['id'])
            ->setLieu($fields['lieu'])
            ->setContent($fields['content'])
            ->setCreatedAt($fields['created_at'])
            ->setUserId($fields['users_id'])
            ->setAuthor($fields['pseudo']);

       return $message;
    }
}