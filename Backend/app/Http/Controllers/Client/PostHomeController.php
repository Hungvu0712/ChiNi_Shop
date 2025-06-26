<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostHomeController extends Controller
{
    public function index()
    {
        $posts = Post::query()->with('postCategory')->latest('id')->paginate(6);
        return view('client.blog.blog', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::with('postCategory')->where('slug', $slug)->first();

        return view('client.blog.blog_detail', compact('post'));
    }
}
