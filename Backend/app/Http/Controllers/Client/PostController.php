<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {
        $posts = Post::query()->with('postCategory')->latest('id')->paginate(6);
        // dd($posts);
        return view('client.blog.blog', compact('posts'));
    }

    public function show($id) {
        $postId = Post::with('postCategory')->find($id);
        return view('client.blog.blog_detail', compact('postId'));
    }
}
