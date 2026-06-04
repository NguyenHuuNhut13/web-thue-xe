@extends('layouts.app')

@section('title')
    {{ $blog->title }} - Cẩm nang NKS
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- 1. Left Side: Article Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Meta Details -->
                <div class="space-y-3">
                    <div class="flex items-center space-x-3 text-xs font-semibold text-slate-400">
                        <span class="flex items-center"><i class="fa-solid fa-user-pen mr-1.5 text-slate-300"></i> {{ $blog->author->name }}</span>
                        <span>&bull;</span>
                        <span>{{ $blog->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
                        {{ $blog->title }}
                    </h1>
                </div>

                <!-- Featured Image -->
                <div class="w-full h-80 sm:h-96 rounded-3xl overflow-hidden shadow-sm bg-slate-200">
                    <img src="{{ $blog->image_url }}" 
                         alt="{{ $blog->title }}" class="w-full h-full object-cover">
                </div>

                <!-- Article Body -->
                <article class="prose max-w-none text-slate-600 text-sm leading-relaxed space-y-5">
                    <!-- Parse HTML from database -->
                    {!! $blog->content !!}
                </article>

                <!-- Social share -->
                <div class="border-t border-slate-100 pt-6 flex items-center space-x-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Chia sẻ bài viết</span>
                    <div class="flex space-x-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
                           class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-650 text-xs flex items-center justify-center transition-colors">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Right Side: Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6 sticky top-28">
                    <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-newspaper text-brand mr-2"></i> Bài viết mới đăng
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($recentBlogs as $recent)
                            <a href="{{ route('blogs.show', $recent->slug) }}" class="flex gap-3.5 group">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                                    <img src="{{ $recent->image_url }}" 
                                         alt="{{ $recent->title }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex flex-col justify-center">
                                    <h4 class="text-xs font-bold text-slate-800 group-hover:text-brand transition-colors line-clamp-2 leading-snug">
                                        {{ $recent->title }}
                                    </h4>
                                    <span class="text-[9px] text-slate-400 mt-1 font-semibold">{{ $recent->created_at->format('d/m/Y') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
