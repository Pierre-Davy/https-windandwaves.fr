<?php

namespace App\Models\dao;

use App\Models\entities\Post;
use App\Models\entities\Tag;
use App\Models\entities\User;
use PDO;
use PDOException;

class tagDao extends Db
{




    public function getTags(): array
    {
        $stmt = $this->instance->prepare("SELECT * FROM tags");
        $res = $stmt->execute();

        if ($res) {
            $tags = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tags[] = $this->createObjectFromFields($row);
            }
            return $tags;
        } else {
            throw new PDOException($stmt->errorInfo()[2]);
        }
    }

    public function getTagsToAllPosts(): array
    {
        $stmt = $this->instance->prepare("SELECT tags.*, messages.id as messageId FROM `tags`, messages, messages_tags WHERE messages_tags.id_messages = messages.id AND messages_tags.id_tags = tags.id");
        $res = $stmt->execute();

        if ($res) {
            $tags = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tags[] = $this->createObjectFromFields($row);
            }
            return $tags;
        } else {
            throw new PDOException($stmt->errorInfo()[2]);
        }
    }

    public function createObjectFromFields($fields): Tag  {

        $tag = new Tag();



        $tag->setId($fields['id'])
            ->setName($fields['name']);
        if(isset($fields['messageId'])){$tag->setMessageId($fields['messageId']);}



        return $tag;
    }




}
