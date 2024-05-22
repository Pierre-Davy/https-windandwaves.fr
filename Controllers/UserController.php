<?php

namespace App\Controllers;

use App\Models\services\CommentService;
use App\Models\services\PostService;
use App\Models\services\UserService;

class UserController extends Controller
{
    private UserService $userService;
    private PostService $postService;
    private CommentService $commentService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->postService = new PostService();
        $this->commentService = new CommentService();
    }

    public function formLogin()
    {
        $this->render('user/formLogin', [], 'default');
    }

    public function formRegister()
    {
        $this->render('user/formRegister', [], 'default');
    }

    public function login()
    {
        $user = $this->userService->login($_POST);

        header("location: ../../");
        //$this->render('main/index', compact('user'), 'home');
    }

    public function register()
    {
        $this->userService->register($_POST);

    }
    public function formForgotPassword()
    {
        $this->render('user/forgotPassword', [], 'default');
    }
    public function forgotPassword()
    {
        $this->userService->forgotPassword();
    }
    public function monCompte()
    {
        $messages = $this->postService->getAllMessages();
        $comments = $this->commentService->getAllComments();
        $this->render('user/infoPage', compact('messages', 'comments'), 'default');
    }

    public function resetPassword()
    {
        $this->userService->resetPassword();
    }
    public function deleteAccount()
    {
        $this->userService->deleteAccount();
    }

    public function logout()
    {
        $this->userService->logout();

        header("location: ../../");
    }


}