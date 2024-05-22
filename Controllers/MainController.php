<?php
namespace App\Controllers;

use App\Models\services\CommentService;
use App\Models\services\PostService;
use App\Models\services\TagService;

class MainController extends Controller
{
    private PostService  $postService;
    private CommentService $commentService;
    private TagService $tagService;

    public function __construct(){
        $this->postService = new PostService();
        $this->commentService = new CommentService();
        $this->tagService = new TagService();
    }

    public function index()
    {

        $messages = $this->postService->getAllMessages();
        $comments = $this->commentService->getAllComments();
        $tags = $this->tagService->getTagsToAllPosts();



        $this->render('main/index', compact('messages', 'comments', 'tags'), 'home');
    }
    public function page404()
    {
        $this->render('main/page404', [], 'default');
    }
    public function mentions()
    {
        $this->render('main/mentionsLegales', [], 'default');
    }
}