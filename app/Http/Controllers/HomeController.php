<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Blog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCars = Car::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $latestBlogs = Blog::with(['author'])->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('home', compact('featuredCars', 'latestBlogs'));
    }
}
