<?php

namespace App\Http\Controllers;

use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'content' => 'required|string|max:1000',
            'name' => 'required_if:user_id,null|string|max:100',
            'email' => 'required_if:user_id,null|email|max:100',
        ]);

        $blog = Blog::published()->findOrFail($request->blog_id);

        BlogComment::create([
            'blog_id' => $blog->id,
            'user_id' => auth()->id() ?? null,
            'name' => $request->name,
            'email' => $request->email,
            'content' => $request->content,
            'approved' => false, // Pending admin approval
        ]);

        return redirect()->back()->with('success', 'Comment submitted for approval!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogComment $blogComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogComment $blogComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogComment $blogComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogComment $blogComment)
    {
        //
    }
}
