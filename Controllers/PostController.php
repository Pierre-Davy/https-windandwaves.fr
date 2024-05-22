<?php

namespace App\Controllers;

use App\Models\services\PostService;
use App\Models\services\TagService;

class PostController extends Controller
{
    private PostService $postService;
    private TagService $tagService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->tagService = new TagService();
    }

    public function formMessage()
    {
        $tags = $this->tagService->getAllTags();
        $this->render('forum/formMessage', compact('tags'), 'default');
    }

    public function formMessagePost($idUser)
    {
        $this->postService->createMessage($_POST, $idUser);
    }

    public function updateMessage($idMessage)
    {
        $tags = $this->tagService->getAllTags();
        $message = $this->postService->getMessage($idMessage);
        $this->render('forum/updateMessage', compact('message', 'tags'), 'default');
    }

    public function updateMessagePost($idMessage)
    {
        $this->postService->updateMessage($idMessage, $_POST);
    }

    public function deleteMessagePost($idMessage)
    {
        $this->postService->deleteMessage($idMessage, $_POST);
    }

}