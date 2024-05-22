<?php

namespace App\Models\dao;

use App\Models\entities\Comment;
use App\Models\entities\Post;
use App\Models\entities\User;
use PDO;
use PDOException;

class CommentDao extends Db
{
    public function createComment($idMessage,$idUser, $content, $ip)
    {
        $stmt = $this->instance->prepare("INSERT INTO `comments` (`content`, `user_id`, `post_id`, `ip_author`) VALUES (:content, :userId, :postId, :ip)");
        $stmt->bindParam("content", $content, PDO::PARAM_STR);
        $stmt->bindParam("userId", $idUser, PDO::PARAM_INT);
        $stmt->bindParam("postId", $idMessage, PDO::PARAM_INT);
        $stmt->bindParam("ip", $ip, PDO::PARAM_STR);
        $res = $stmt->execute();
        $_SESSION["message"] = "Enregistrement réussi.";
        header("location: ../../../");

        if (!$res) {
            throw new PDOException($stmt->errorInfo()[2]);
        }
    }
    public function updateComment(string $idAuthor, string $idComment, string $content, $ip)
    {
        $stmt = $this->instance->prepare("UPDATE `comments` SET `content`=:content, `ip_author`=:ip WHERE `id`=:idComment AND `user_id`=:idAuthor");
        $stmt->bindParam("content", $content, PDO::PARAM_STR);
        $stmt->bindParam("idComment", $idComment, PDO::PARAM_INT);
        $stmt->bindParam("idAuthor", $idAuthor, PDO::PARAM_INT);
        $stmt->bindParam("ip", $ip, PDO::PARAM_STR);
        $res = $stmt->execute();
        $_SESSION["message"] = "Enregistrement réussi.";
        header("location: ../../../");

        if (!$res) {
            throw new PDOException($stmt->errorInfo()[2]);
        }

    }

    public function deleteComment($idAuthor, $idComment)
    {
        $stmt = $this->instance->prepare("DELETE FROM `comments` WHERE `id`=:idComment AND `user_id`=:idAuthor");
        $stmt->bindParam("idComment", $idComment, PDO::PARAM_INT);
        $stmt->bindParam("idAuthor", $idAuthor, PDO::PARAM_INT);
        $res = $stmt->execute();
        $_SESSION["message"] = "Suppression réussie.";
        header("location: ../../../");

        if (!$res) {
            throw new PDOException($stmt->errorInfo()[2]);
        }

    }

    public function getComments(): array
    {
        $stmt = $this->instance->prepare("SELECT comments.*, users.pseudo FROM comments, users WHERE comments.user_id = users.id ORDER BY comments.id DESC");
        $res = $stmt->execute();

        if ($res) {
            $comments = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $comments[] = $this->createObjectFromFields($row);
            }
            return $comments;
        } else {
            throw new PDOException($stmt->errorInfo()[2]);
        }
    }

    public function getcomment($idComment)
    {
        $stmt = $this->instance->prepare("SELECT comments.*, users.pseudo FROM comments, users WHERE comments.user_id = users.id AND comments.id = :idComment");
        $stmt->bindParam("idComment", $idComment, PDO::PARAM_INT);
        $stmt->execute();
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($comment) {
            return $this->createObjectFromFields($comment);
        } else {
            throw new PDOException($stmt->errorInfo()[2]);
        }
    }

    public function createObjectFromFields($fields): Comment
    {
        $comment = new Comment();

        $comment->setId($fields['id'])
            ->setContent($fields['content'])
            ->setCreatedAt($fields['created_at'])
            ->setUserId($fields['user_id'])
            ->setPostId($fields['post_id'])
            ->setAuthor($fields['pseudo']);

        return $comment;
    }




}
