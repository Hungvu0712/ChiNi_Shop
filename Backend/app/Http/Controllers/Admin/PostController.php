<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StoreRequest;
use App\Http\Requests\Posts\UpdateRequest;
use App\Models\Post;
use App\Models\PostCategory;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission.404:post-list')->only('index', 'show');
        // $this->middleware('permission.404:post-create')->only('create', 'store');
        // $this->middleware('permission.404:post-edit')->only('edit', 'update');
        // $this->middleware('permission.404:post-delete')->only('destroy');
        $this->middleware('permission.404:crudpost')->only('index', 'create', 'store', 'edit', 'update', 'destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('postCategory')->latest('id')->get();
        return view('admin.pages.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $postCategories = PostCategory::all();
        return view('admin.pages.posts.create', compact('postCategories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $slug = \Str::slug($validated['title']);

        $featuredImageUrl = null;
        $publicFeaturedImageId = null;

        if ($request->hasFile('featured_image')) {
            $uploaded = Cloudinary::upload($request->file('featured_image')->getRealPath(), [
                'folder' => 'posts',
            ]);

            $featuredImageUrl = $uploaded->getSecurePath();
            $publicFeaturedImageId = $uploaded->getPublicId();
        }

        Post::create([
            'post_category_id' => $validated['post_category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'featured_image' => $featuredImageUrl,
            'public_featured_image_id' => $publicFeaturedImageId,
            'status' => $validated['status'],
        ]);

        return redirect()->route('posts.index')->with('success', 'Thêm bài viết thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        $postCategories = PostCategory::all();

        return view('admin.pages.posts.edit', compact('post', 'postCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        $post = Post::findOrFail($id);

        $slug = \Str::slug($validated['title']);

        $featuredImageUrl = $post->featured_image;
        $publicImageId = $post->public_featured_image_id;

        if ($request->hasFile('featured_image')) {

            if ($post->public_featured_image_id) {
                Cloudinary::destroy($post->public_featured_image_id);
            }

            $uploaded = Cloudinary::upload($request->file('featured_image')->getRealPath(), [
                'folder' => 'posts',
            ]);

            $featuredImageUrl = $uploaded->getSecurePath();
            $publicImageId = $uploaded->getPublicId();
        }

        $post->update([
            'post_category_id' => $validated['post_category_id'],
            'title'             => $validated['title'],
            'slug'              => $slug,
            'excerpt'           => $validated['excerpt'],
            'content'           => $validated['content'],
            'featured_image'    => $featuredImageUrl,
            'public_featured_image_id' => $publicImageId,
            'status'            => $validated['status'],
        ]);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Cập nhật bài viết thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->public_featured_image_id) {
            Cloudinary::destroy($post->public_featured_image_id);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Xóa bài viết thành công!');
    }

    public function uploadImageSummernote(Request $request)
    {
        if ($request->hasFile('file')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('file')->getRealPath(), [
                'folder' => 'posts',
            ])->getSecurePath();
            return response()->json($uploadedFileUrl);
        }

        return response()->json(['error' => 'Không có ảnh gửi lên'], 400);
    }
}
