@extends('layouts.app')

@section('title', 'Cẩm nang du lịch và Thuê xe tự lái - NKS')

@section('content')
    <!-- Banner -->
    <div class="bg-slate-900 py-16 border-b border-slate-800 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-brand font-bold text-xs uppercase tracking-widest">Chia sẻ kinh nghiệm</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Cẩm Nang & Tin Tức Xe Du Lịch</h1>
            <p class="text-sm text-slate-400 mt-2.5 max-w-lg mx-auto">
                Chia sẻ mẹo lái xe an toàn, thủ tục thuê xe ô tô tự lái và các điểm du lịch phượt thú vị cùng bạn bè, gia đình.
            </p>
        </div>
    </div>

    <!-- Blogs list grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($blogs as $blog)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-lg transition-all duration-300">
                    <!-- Image -->
                    <div class="relative h-52 overflow-hidden bg-slate-200">
                        <img src="{{ $blog->image_url }}" 
                             alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $blog->created_at->format('d/m/Y') }}</span>
                            <h3 class="text-lg font-bold text-slate-900 mt-1 leading-snug line-clamp-2">
                                <a href="{{ route('blogs.show', $blog->slug) }}" class="hover:text-brand transition-colors">{{ $blog->title }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 mt-3 line-clamp-2 leading-relaxed">
                                {{ $blog->summary }}
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center text-xs">
                            <span class="text-slate-400 font-medium"><i class="fa-solid fa-user-pen mr-1.5 text-slate-300"></i> {{ $blog->author->name }}</span>
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="font-bold text-brand hover:text-brand-hover transition-colors">
                                Đọc thêm <i class="fa-solid fa-chevron-right ml-1 text-[9px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-slate-100">
                    <i class="fa-solid fa-book-open text-4xl text-slate-200 mb-3"></i>
                    <p class="text-slate-400 font-medium">Hiện tại chưa có bài viết nào được xuất bản.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $blogs->links() }}
        </div>
    </div>
@endsection
