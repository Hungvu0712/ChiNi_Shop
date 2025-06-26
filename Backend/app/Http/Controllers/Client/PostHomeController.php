<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Post;
use Illuminate\Http\Request;

class PostHomeController extends Controller
{
    public function index()
    {
        $posts = Post::query()->with('postCategory')->latest('id')->paginate(6);
        $menus = Menu::where('parent_id', null)->orderBy('order_index', 'asc')->get();
        return view('client.blog.blog', compact('posts', 'menus'));
    }

    public function show($slug)
    {
        $post = Post::with('postCategory')->where('slug', $slug)->first();

        return view('client.blog.blog_detail', compact('post'));
    }
}
