<?php

namespace App\Controllers;

use App\Models\services\CommentService;
use App\Models\services\PostService;
use App\Models\services\UserService;

class AdminController extends Controller
{
    private UserService $userService;
    private PostService  $postService;
    private CommentService $commentService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->postService = new PostService();
        $this->commentService = new CommentService();
    }

    public function administration()
    {

        $users = $this->userService->getAllUsers();
        $messages = $this->postService->getAllMessages();
        $comments = $this->commentService->getAllComments();

        $this->render('admin/administration', compact('users', 'messages', 'comments'), 'default');
    }

    public function messageList($idAuthor)
    {
        $messages = $this->postService->getAllMessages();

        $this->render('admin/messageList', compact('idAuthor', 'messages'), 'default');
    }
    public function commentList($idAuthor)
    {

        $comments = $this->commentService->getAllComments();

        $this->render('admin/commentList', compact('idAuthor', 'comments'), 'default');
    }

    public function deleteAccountByAdmin()
    {
        $this->userService->deleteAccountByAdmin();
    }
}
