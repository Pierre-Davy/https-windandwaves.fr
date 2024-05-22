<?php

namespace App\Controllers;

use App\Models\services\CommentService;

class CommentController extends Controller
{
    private CommentService $commentService;

    public function __construct()
    {
        $this->commentService = new CommentService();
    }

    public function formComment($idMessage)
    {
        $this->render('forum/formComment', compact('idMessage'), 'default');
    }

    public function formCommentPost($idMessage)
    {

        $this->commentService->createComment($_POST, $idMessage);
    }

    public function updateComment($idComment)
    {
        $comment = $this->commentService->getComment($idComment);
        $this->render('forum/updateComment', compact('comment'), 'default');
    }

    public function updateCommentPost($idComment)
    {
        $this->commentService->updateComment($idComment, $_POST);
    }

    public function deleteCommentPost($idComment)
    {
        $this->commentService->deleteComment($idComment, $_POST);
    }

}
