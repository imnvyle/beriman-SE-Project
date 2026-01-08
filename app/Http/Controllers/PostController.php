<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $path = $request->file('image')->store('uploads', 'public');

        Post::create([
            'title' => $request->title,
            'image' => $path,
        ]);

        return redirect()->route('post.show', Post::latest()->first()->id)
                         ->with('success', 'Image uploaded!');
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.show', compact('post'));
    }
}
