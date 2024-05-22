<?php

namespace App\Models\services;

use App\Models\dao\tagDao;


class TagService
{
    private TagDao $tagDao;


    public function __construct(){
        $this->tagDao = new tagDao();
    }



    public function getAllTags(): array
    {
        $tags = $this->tagDao->gettags();
        return $tags;
    }

    public function getTagsToAllPosts():array
    {
        $tags = $this->tagDao->getTagsToAllPosts();
        return $tags;
    }


}
