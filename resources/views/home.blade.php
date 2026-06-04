@extends('layouts.app')

@section('title', 'NKS - Website Cho Thuê Xe Du Lịch Tự Lái Uy Tín Hàng Đầu')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-slate-900 overflow-hidden py-32 sm:py-40">
        <!-- Hero Background Image with Premium Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1920&q=80" 
                 alt="Luxury Car" class="w-full h-full object-cover opacity-35 object-center">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/80 to-transparent"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10 text-center space-y-6">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-brand/20 text-blue-400 border border-brand/30">
                <i class="fa-solid fa-sparkles"></i> Hệ thống kết nối chủ xe toàn quốc
            </span>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight text-white leading-none">
                Hành Trình Sang Trọng<br><span class="text-brand">Trải Nghiệm Hoàn Hảo</span>
            </h1>
            <p class="max-w-2xl mx-auto text-lg text-slate-300">
                Tìm kiếm và đặt thuê xe du lịch 4 - 16 chỗ nhanh chóng, đa dạng chủng loại từ xe xăng đến xe điện cao cấp, liên hệ chủ xe trực tiếp qua Zalo/Phone.
            </p>
        </div>
    </div>

    <!-- Search Form overlapping Hero -->
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 z-20">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 md:p-8">
            <form action="{{ route('cars.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                
                <!-- Search text -->
                <div class="flex flex-col relative" x-data="searchSuggestions" @click.outside="close()" @keydown.escape.window="close()">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Từ khóa tìm kiếm</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-slate-400"></i>
                        <input type="text" name="search" placeholder="Tên xe, hãng xe..." x-model="query" @focus="isOpen = query.trim().length >= 2" autocomplete="off"
                               class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-sm font-medium">
                    </div>
                    <!-- Suggestions Dropdown -->
                    <div x-show="isOpen" 
                         x-transition:enter="transition ease-out duration-100" 
                         x-transition:enter-start="opacity-0 translate-y-1" 
                         x-transition:enter-end="opacity-100 translate-y-0" 
                         x-transition:leave="transition ease-in duration-75" 
                         x-transition:leave-start="opacity-100 translate-y-0" 
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute z-50 left-0 top-full mt-2 w-full sm:w-[400px] md:w-[450px] bg-white border border-slate-100 rounded-2xl shadow-2xl py-2.5 max-h-80 overflow-y-auto"
                         style="display: none;">
                         
                        <div x-show="loading" class="px-4 py-3 text-xs font-semibold text-slate-400 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-circle-notch fa-spin text-brand"></i>
                            <span>Đang tìm kiếm xe...</span>
                        </div>
                        
                        <div x-show="!loading && suggestions.length === 0" class="px-4 py-3 text-xs font-medium text-slate-400 text-center">
                            <i class="fa-solid fa-face-frown mr-1"></i> Không tìm thấy xe nào phù hợp
                        </div>
                        
                        <template x-for="car in suggestions" :key="car.id">
                            <a :href="car.url" class="flex items-center px-4 py-2.5 hover:bg-slate-50 transition-colors group">
                                <img :src="car.thumbnail" :alt="car.title" class="w-12 h-12 rounded-lg object-cover border border-slate-100 flex-shrink-0">
                                <div class="ml-3 flex-grow text-left">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block" x-text="car.brand"></span>
                                    <span class="text-sm font-bold text-slate-800 group-hover:text-brand transition-colors line-clamp-1" x-text="car.title"></span>
                                </div>
                                <div class="text-right ml-2 flex-shrink-0">
                                    <span class="text-xs font-bold text-brand" x-text="car.price"></span>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>

                <!-- Seats -->
                <div class="flex flex-col">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Số chỗ ngồi</label>
                    <div class="relative">
                        <i class="fa-solid fa-users absolute left-4 top-3.5 text-slate-400"></i>
                        <select name="seats" class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-sm font-medium appearance-none">
                            <option value="">Tất cả</option>
                            <option value="4">4 chỗ</option>
                            <option value="5">5 chỗ</option>
                            <option value="7">7 chỗ</option>
                            <option value="16">16 chỗ</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-4 text-xs text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Fuel Type -->
                <div class="flex flex-col">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nhiên liệu</label>
                    <div class="relative">
                        <i class="fa-solid fa-gas-pump absolute left-4 top-3.5 text-slate-400"></i>
                        <select name="fuel_type" class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-sm font-medium appearance-none">
                            <option value="">Tất cả</option>
                            <option value="gasoline">Xăng</option>
                            <option value="diesel">Dầu</option>
                            <option value="electric">Điện (EV)</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-4 text-xs text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Transmission -->
                <div class="flex flex-col">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Hộp số</label>
                    <div class="relative">
                        <i class="fa-solid fa-gears absolute left-4 top-3.5 text-slate-400"></i>
                        <select name="transmission" class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-sm font-medium appearance-none">
                            <option value="">Tất cả</option>
                            <option value="automatic">Số tự động</option>
                            <option value="manual">Số sàn</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-4 text-xs text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col justify-end">
                    <button type="submit" class="w-full bg-brand hover:bg-brand-hover text-white text-sm font-semibold py-3.5 rounded-xl shadow-lg shadow-brand/20 transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Tìm xe ngay</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Car Brands Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold uppercase tracking-wider text-slate-400">Hãng xe nổi bật</h2>
        </div>
        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12 opacity-65">
            <a href="{{ route('cars.index') }}?brand=Toyota" class="flex flex-col items-center hover:opacity-100 hover:text-brand transition-all text-slate-500 font-semibold">
                <i class="fa-solid fa-car text-3xl mb-2"></i> Toyota
            </a>
            <a href="{{ route('cars.index') }}?brand=VinFast" class="flex flex-col items-center hover:opacity-100 hover:text-brand transition-all text-slate-500 font-semibold">
                <i class="fa-solid fa-bolt text-3xl mb-2"></i> VinFast
            </a>
            <a href="{{ route('cars.index') }}?brand=Honda" class="flex flex-col items-center hover:opacity-100 hover:text-brand transition-all text-slate-500 font-semibold">
                <i class="fa-solid fa-car-rear text-3xl mb-2"></i> Honda
            </a>
            <a href="{{ route('cars.index') }}?brand=Ford" class="flex flex-col items-center hover:opacity-100 hover:text-brand transition-all text-slate-500 font-semibold">
                <i class="fa-solid fa-truck-pickup text-3xl mb-2"></i> Ford
            </a>
            <a href="{{ route('cars.index') }}?brand=Hyundai" class="flex flex-col items-center hover:opacity-100 hover:text-brand transition-all text-slate-500 font-semibold">
                <i class="fa-solid fa-car-side text-3xl mb-2"></i> Hyundai
            </a>
            <a href="{{ route('cars.index') }}?brand=Mitsubishi" class="flex flex-col items-center hover:opacity-100 hover:text-brand transition-all text-slate-500 font-semibold">
                <i class="fa-solid fa-van-shuttle text-3xl mb-2"></i> Mitsubishi
            </a>
        </div>
    </div>

    <!-- Featured Cars -->
    <div class="bg-slate-100/60 py-20 border-y border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <span class="text-brand font-bold text-xs uppercase tracking-widest">Xe mới cập nhật</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Danh Sách Xe Cho Thuê</h2>
                </div>
                <a href="{{ route('cars.index') }}" class="hidden sm:inline-flex items-center text-sm font-semibold text-brand hover:text-brand-hover transition-colors">
                    Xem tất cả xe <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>

            <!-- Cars Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredCars as $car)
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden group hover:shadow-xl transition-all duration-300 flex flex-col">
                        <!-- Car Thumbnail -->
                        <div class="relative h-56 overflow-hidden">
                            <img src="{{ $car->thumbnail_url }}" 
                                 alt="{{ $car->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <!-- Price overlay -->
                            <div class="absolute bottom-4 left-4 bg-slate-950/80 backdrop-blur-md text-white px-3 py-1.5 rounded-xl font-bold text-sm">
                                {{ number_format($car->price_per_day, 0, ',', '.') }}đ <span class="text-[10px] text-slate-400 font-normal">/ ngày</span>
                            </div>
                            <!-- Transmission badge -->
                            <div class="absolute top-4 right-4 bg-brand text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">
                                {{ $car->transmission === 'automatic' ? 'Số tự động' : 'Số sàn' }}
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ $car->brand }}</span>
                                <h3 class="text-lg font-bold text-slate-900 mt-1 line-clamp-1">
                                    <a href="{{ route('cars.show', $car->slug) }}" class="hover:text-brand transition-colors">{{ $car->title }}</a>
                                </h3>
                                <!-- Location -->
                                <p class="text-xs text-slate-500 mt-2 flex items-center">
                                    <i class="fa-solid fa-location-dot mr-1.5 text-brand/80"></i> {{ Str::limit($car->location, 35) }}
                                </p>
                            </div>

                            <!-- Specs Grid -->
                            <div class="grid grid-cols-3 gap-2 border-t border-slate-100 pt-4 mt-4 text-center">
                                <div class="bg-slate-50 py-2 rounded-xl">
                                    <i class="fa-solid fa-users text-slate-400 text-sm mb-1"></i>
                                    <p class="text-xs font-bold text-slate-700">{{ $car->seats }} chỗ</p>
                                </div>
                                <div class="bg-slate-50 py-2 rounded-xl">
                                    <i class="fa-solid fa-gas-pump text-slate-400 text-sm mb-1"></i>
                                    <p class="text-xs font-bold text-slate-700 capitalize">
                                        {{ $car->fuel_type === 'gasoline' ? 'Xăng' : ($car->fuel_type === 'diesel' ? 'Dầu' : 'Điện') }}
                                    </p>
                                </div>
                                <div class="bg-slate-50 py-2 rounded-xl">
                                    <i class="fa-solid fa-calendar text-slate-400 text-sm mb-1"></i>
                                    <p class="text-xs font-bold text-slate-700">Đời {{ $car->year }}</p>
                                </div>
                            </div>

                            <!-- CTA Button -->
                            <div class="mt-6">
                                <a href="{{ route('cars.show', $car->slug) }}" class="w-full inline-flex items-center justify-center bg-slate-900 hover:bg-brand text-white text-sm font-semibold py-3 rounded-xl transition-all">
                                    Chi tiết & Đặt xe
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-slate-100">
                        <i class="fa-solid fa-car-rear text-4xl text-slate-300 mb-3"></i>
                        <p class="text-slate-500 font-medium">Hiện tại chưa có xe nào hoạt động trên hệ thống.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="text-center sm:hidden mt-8">
                <a href="{{ route('cars.index') }}" class="inline-flex items-center text-sm font-semibold text-brand">
                    Xem tất cả xe <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Why Choose NKS Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <span class="text-brand font-bold text-xs uppercase tracking-widest">Dịch vụ chuyên nghiệp</span>
        <h2 class="text-3xl font-extrabold text-slate-900 mt-1 mb-16">Tại Sao Chọn Thuê Xe Tại NKS?</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Reason 1 -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-14 h-14 bg-blue-50 text-brand rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-shield-halved text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-950 mb-3">An toàn tuyệt đối</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Mọi chủ xe trên hệ thống đều phải xác minh thông tin cá nhân và xe trước khi duyệt tin. Các xe cho thuê cam kết được bảo dưỡng kỹ càng.
                </p>
            </div>
            <!-- Reason 2 -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-14 h-14 bg-blue-50 text-brand rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-comments-dollar text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-950 mb-3">Giá tốt & minh bạch</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Không phí ẩn, không chênh lệch giá. Khách thuê thỏa thuận giá, lịch trình và nhận xe trực tiếp từ chủ xe vô cùng minh bạch, thuận lợi.
                </p>
            </div>
            <!-- Reason 3 -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-14 h-14 bg-blue-50 text-brand rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-headset text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-950 mb-3">Hỗ trợ 24/7</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    NKS luôn đồng hành cùng bạn trên mọi nẻo đường, hỗ trợ kịp thời các thủ tục kết nối và giải quyết các khiếu nại phát sinh nhanh nhất.
                </p>
            </div>
        </div>
    </div>

    <!-- Latest Blogs -->
    <div class="bg-slate-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <span class="text-brand font-bold text-xs uppercase tracking-widest">Tin tức hữu ích</span>
                    <h2 class="text-3xl font-extrabold text-white mt-1">Cẩm Nang Du Lịch & Thuê Xe</h2>
                </div>
                <a href="{{ route('blogs.index') }}" class="inline-flex items-center text-sm font-semibold text-brand hover:text-brand-hover transition-colors">
                    Xem tất cả bài viết <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($latestBlogs as $blog)
                    <div class="bg-slate-800 rounded-3xl overflow-hidden border border-slate-700/50 flex flex-col group hover:border-brand/40 transition-colors duration-300">
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $blog->image_url }}" 
                                 alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $blog->created_at->format('d/m/Y') }}</span>
                                <h3 class="text-lg font-bold text-white mt-1 leading-snug line-clamp-2">
                                    <a href="{{ route('blogs.show', $blog->slug) }}" class="hover:text-brand transition-colors">{{ $blog->title }}</a>
                                </h3>
                                <p class="text-xs text-slate-400 mt-2 line-clamp-2 leading-relaxed">
                                    {{ $blog->summary }}
                                </p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-700/50 flex justify-between items-center">
                                <span class="text-xs text-slate-400 font-medium"><i class="fa-solid fa-user-pen mr-1"></i> {{ $blog->author->name }}</span>
                                <a href="{{ route('blogs.show', $blog->slug) }}" class="text-xs font-bold text-brand hover:text-brand-hover transition-colors">
                                    Đọc thêm <i class="fa-solid fa-chevron-right ml-1 text-[9px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-slate-800 rounded-3xl border border-slate-700/50">
                        <p class="text-slate-400 font-medium">Chưa có bài viết nào được đăng.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
