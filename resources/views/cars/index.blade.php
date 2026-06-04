@extends('layouts.app')

@section('title', 'Thuê Xe Du Lịch Tự Lái - Danh Sách Xe NKS')

@section('content')
    <!-- Header banner -->
    <div class="bg-slate-900 py-12 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-center md:text-left">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Danh Sách Xe Cho Thuê</h1>
                <p class="text-sm text-slate-400 mt-1">Tìm kiếm chiếc xe phù hợp nhất cho chuyến đi của bạn</p>
            </div>
            <div>
                <a href="{{ route('cars.map') }}" class="inline-flex items-center space-x-2 bg-brand hover:bg-brand-hover text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg shadow-brand/20 transition-all">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Xem trên Bản đồ</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- 1. Left Sidebar: Filters -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6 sticky top-28">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <h3 class="font-bold text-slate-900 text-lg flex items-center">
                            <i class="fa-solid fa-sliders text-brand mr-2.5"></i> Bộ lọc xe
                        </h3>
                        <a href="{{ route('cars.index') }}" class="text-xs font-semibold text-slate-400 hover:text-brand transition-colors">
                            Xóa lọc
                        </a>
                    </div>

                    <form action="{{ route('cars.index') }}" method="GET" class="space-y-5">
                        <!-- Keep search parameters when filtering -->
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <!-- Brand Filter -->
                        <div class="flex flex-col">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Hãng xe</label>
                            <div class="relative">
                                <select name="brand" onchange="this.form.submit()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-sm font-medium appearance-none bg-white">
                                    <option value="">Tất cả hãng</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand }}" {{ request('brand') === $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-xs text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Seats Filter -->
                        <div class="flex flex-col">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Số chỗ ngồi</label>
                            <div class="relative">
                                <select name="seats" onchange="this.form.submit()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-sm font-medium appearance-none bg-white">
                                    <option value="">Tất cả số chỗ</option>
                                    <option value="4" {{ request('seats') == '4' ? 'selected' : '' }}>4 chỗ</option>
                                    <option value="5" {{ request('seats') == '5' ? 'selected' : '' }}>5 chỗ</option>
                                    <option value="7" {{ request('seats') == '7' ? 'selected' : '' }}>7 chỗ</option>
                                    <option value="16" {{ request('seats') == '16' ? 'selected' : '' }}>16 chỗ</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-xs text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Transmission Filter -->
                        <div class="flex flex-col">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Hộp số</label>
                            <div class="relative">
                                <select name="transmission" onchange="this.form.submit()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-sm font-medium appearance-none bg-white">
                                    <option value="">Tất cả hộp số</option>
                                    <option value="automatic" {{ request('transmission') === 'automatic' ? 'selected' : '' }}>Số tự động</option>
                                    <option value="manual" {{ request('transmission') === 'manual' ? 'selected' : '' }}>Số sàn</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-xs text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Fuel Type Filter -->
                        <div class="flex flex-col">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nhiên liệu</label>
                            <div class="relative">
                                <select name="fuel_type" onchange="this.form.submit()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-sm font-medium appearance-none bg-white">
                                    <option value="">Tất cả nhiên liệu</option>
                                    <option value="gasoline" {{ request('fuel_type') === 'gasoline' ? 'selected' : '' }}>Xăng</option>
                                    <option value="diesel" {{ request('fuel_type') === 'diesel' ? 'selected' : '' }}>Dầu</option>
                                    <option value="electric" {{ request('fuel_type') === 'electric' ? 'selected' : '' }}>Điện (EV)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-xs text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Price Ranges -->
                        <div class="space-y-2.5">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Khoảng giá (VNĐ)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Tối thiểu"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-medium">
                                <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Tối đa"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-medium">
                            </div>
                            <button type="submit" class="w-full mt-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-2.5 rounded-xl transition-all">
                                Áp dụng giá
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2. Right Side: Search and Cars Grid -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Search bar & Sorting bar -->
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <form action="{{ route('cars.index') }}" method="GET" class="flex-grow max-w-lg">
                        <!-- Keep filter values when searching -->
                        @if(request('brand')) <input type="hidden" name="brand" value="{{ request('brand') }}"> @endif
                        @if(request('seats')) <input type="hidden" name="seats" value="{{ request('seats') }}"> @endif
                        @if(request('transmission')) <input type="hidden" name="transmission" value="{{ request('transmission') }}"> @endif
                        @if(request('fuel_type')) <input type="hidden" name="fuel_type" value="{{ request('fuel_type') }}"> @endif
                        @if(request('price_min')) <input type="hidden" name="price_min" value="{{ request('price_min') }}"> @endif
                        @if(request('price_max')) <input type="hidden" name="price_max" value="{{ request('price_max') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="relative" x-data="searchSuggestions" x-init="query = '{{ request('search') }}'" @click.outside="close()" @keydown.escape.window="close()">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-slate-400"></i>
                            <input type="text" name="search" placeholder="Tìm kiếm tên xe, dòng xe..." x-model="query" @focus="isOpen = query.trim().length >= 2" autocomplete="off"
                                   class="w-full pl-11 pr-24 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-sm font-medium">
                            <button type="submit" class="absolute right-1.5 top-1.5 bg-brand hover:bg-brand-hover text-white text-xs font-bold px-4 py-1.5 rounded-lg transition-colors">
                                Tìm
                            </button>

                            <!-- Suggestions Dropdown -->
                            <div x-show="isOpen" 
                                 x-transition:enter="transition ease-out duration-100" 
                                 x-transition:enter-start="opacity-0 translate-y-1" 
                                 x-transition:enter-end="opacity-100 translate-y-0" 
                                 x-transition:leave="transition ease-in duration-75" 
                                 x-transition:leave-start="opacity-100 translate-y-0" 
                                 x-transition:leave-end="opacity-0 translate-y-1"
                                 class="absolute z-50 left-0 right-0 top-full mt-2 bg-white border border-slate-100 rounded-2xl shadow-2xl py-2.5 max-h-80 overflow-y-auto"
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
                    </form>

                    <!-- Sorting -->
                    <div class="flex items-center space-x-3 self-end md:self-auto">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">Sắp xếp</label>
                        <div class="relative">
                            <select onchange="window.location.href = this.value" class="pl-4 pr-10 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-bold appearance-none bg-white">
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 text-[9px] text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- Cars Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($cars as $car)
                        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col justify-between">
                            <!-- Car Image -->
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ $car->thumbnail_url }}" 
                                     alt="{{ $car->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute bottom-3 left-3 bg-slate-950/80 backdrop-blur-sm text-white px-2.5 py-1 rounded-lg font-bold text-xs">
                                    {{ number_format($car->price_per_day, 0, ',', '.') }}đ <span class="text-[9px] text-slate-400 font-normal">/ ngày</span>
                                </div>
                                <div class="absolute top-3 right-3 bg-brand text-white px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider">
                                    {{ $car->transmission === 'automatic' ? 'Tự động' : 'Số sàn' }}
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $car->brand }}</span>
                                    <h3 class="text-base font-bold text-slate-900 mt-0.5 line-clamp-1">
                                        <a href="{{ route('cars.show', $car->slug) }}" class="hover:text-brand transition-colors">{{ $car->title }}</a>
                                    </h3>
                                    <!-- Location -->
                                    <p class="text-[11px] text-slate-400 mt-1 flex items-center">
                                        <i class="fa-solid fa-location-dot mr-1.5 text-brand/80"></i> {{ Str::limit($car->location, 35) }}
                                    </p>
                                </div>

                                <!-- Specs Grid -->
                                <div class="grid grid-cols-3 gap-1.5 border-t border-slate-100 pt-3.5 mt-3.5 text-center text-[11px]">
                                    <div class="bg-slate-50 py-1.5 rounded-lg">
                                        <i class="fa-solid fa-users text-slate-400 mb-0.5"></i>
                                        <p class="font-bold text-slate-700">{{ $car->seats }} chỗ</p>
                                    </div>
                                    <div class="bg-slate-50 py-1.5 rounded-lg">
                                        <i class="fa-solid fa-gas-pump text-slate-400 mb-0.5"></i>
                                        <p class="font-bold text-slate-700 capitalize">
                                            {{ $car->fuel_type === 'gasoline' ? 'Xăng' : ($car->fuel_type === 'diesel' ? 'Dầu' : 'Điện') }}
                                        </p>
                                    </div>
                                    <div class="bg-slate-50 py-1.5 rounded-lg">
                                        <i class="fa-solid fa-calendar text-slate-400 mb-0.5"></i>
                                        <p class="font-bold text-slate-700">Đời {{ $car->year }}</p>
                                    </div>
                                </div>

                                <!-- Action -->
                                <div class="mt-5">
                                    <a href="{{ route('cars.show', $car->slug) }}" class="w-full inline-flex items-center justify-center bg-slate-900 hover:bg-brand text-white text-xs font-bold py-2.5 rounded-xl transition-all">
                                        Xem chi tiết & Đặt xe
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-slate-100">
                            <i class="fa-solid fa-car-rear text-5xl text-slate-200 mb-4"></i>
                            <h3 class="text-lg font-bold text-slate-800">Không tìm thấy xe nào</h3>
                            <p class="text-sm text-slate-400 mt-1.5">Vui lòng thay đổi từ khóa hoặc bộ lọc của bạn.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $cars->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
