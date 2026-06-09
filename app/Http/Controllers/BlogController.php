<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with(['author'])->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('blogs.index', compact('blogs'));
    }

    public function show($slug)
    {
        $blog = Blog::with(['author'])->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $recentBlogs = Blog::with(['author'])->where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('blogs.show', compact('blog', 'recentBlogs'));
    }
}
