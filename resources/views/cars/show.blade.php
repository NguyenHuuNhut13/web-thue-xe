@extends('layouts.app')

@section('title')
    {{ $car->title }} - Thuê xe du lịch NKS
@endsection

@section('content')
    <!-- Status Messages -->
    <!-- Status Messages -->
    @if(session('success'))
        <div x-data="{ open: true }" x-show="open" style="display: none;">
            <!-- Backdrop -->
            <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); z-index: 99998;" @click="open = false"></div>
            
            <!-- Modal Box -->
            <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #ffffff; padding: 2.25rem 2rem; border-radius: 1.75rem; width: calc(100% - 2rem); max-width: 28rem; text-align: center; border: 1px solid #e2e8f0; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); z-index: 99999; box-sizing: border-box;" class="animate-in fade-in zoom-in-95 duration-200">
                <!-- Close Button -->
                <button type="button" @click="open = false" style="position: absolute; top: 1rem; right: 1rem; padding: 0.375rem; color: #94a3b8; border: none; background: transparent; cursor: pointer; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; outline: none;" onmouseover="this.style.color='#475569'" onmouseout="this.style.color='#94a3b8'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; display: block;" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Icon -->
                <div style="margin: 0 auto 1.5rem auto; display: flex; align-items: center; justify-content: center; width: 72px; height: 72px; border-radius: 50%; background-color: #ecfdf5; color: #10b981; border: 1px solid #d1fae5; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 32px; height: 32px; display: block;" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                
                <!-- Title -->
                <h3 style="margin: 0 0 0.75rem 0; font-size: 1.25rem; font-weight: 900; color: #0f172a; font-family: Outfit, sans-serif;">Gửi yêu cầu đặt xe thành công!</h3>
                
                <!-- Description -->
                <p style="margin: 0; font-size: 0.875rem; font-weight: 500; color: #64748b; line-height: 1.6; padding: 0 0.5rem; font-family: Outfit, sans-serif;">
                    {{ session('success') }}
                </p>
                
                <!-- Button -->
                <button @click="open = false" 
                        style="width: 100%; margin-top: 2rem; background-color: #0077bb; color: #ffffff; font-weight: 700; font-size: 0.875rem; padding: 0.875rem 0; border-radius: 0.75rem; border: none; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.1); font-family: Outfit, sans-serif;"
                        onmouseover="this.style.backgroundColor='#0066aa'"
                        onmouseout="this.style.backgroundColor='#0077bb'">
                    Đồng ý
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center space-x-3 text-sm">
                <i class="fa-solid fa-circle-xmark text-rose-500 text-lg"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Gallery Header -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <!-- Main large image -->
            <div class="md:col-span-2 h-[350px] md:h-[450px] rounded-3xl overflow-hidden shadow-sm bg-slate-200">
                <img src="{{ $car->imageUrl(0) }}" 
                     alt="{{ $car->title }}" class="w-full h-full object-cover">
            </div>
            
            <!-- Side images (grid/stacked) -->
            <div class="hidden md:flex flex-col gap-4 h-[450px]">
                <div class="h-1/2 rounded-3xl overflow-hidden shadow-sm bg-slate-200">
                    <img src="{{ $car->imageUrl(1) }}" 
                         alt="{{ $car->title }}" class="w-full h-full object-cover">
                </div>
                <div class="h-1/2 rounded-3xl overflow-hidden shadow-sm bg-slate-200 relative">
                    <img src="{{ $car->imageUrl(0) }}" 
                         alt="{{ $car->title }}" class="w-full h-full object-cover opacity-60">
                    <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                        <span class="text-white font-extrabold text-lg"><i class="fa-solid fa-images mr-1"></i> Xem ảnh thực tế</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2 Columns Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Side: Specs, Features, Owner Card -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Main Header info -->
                <div>
                    <div class="flex items-center space-x-2.5">
                        <span class="text-xs font-bold text-brand uppercase tracking-widest bg-blue-50 px-2.5 py-1 rounded-md">{{ $car->brand }}</span>
                        <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md">Đời {{ $car->year }}</span>
                    </div>
                    <h1 class="text-3xl font-extrabold text-slate-900 mt-2.5">{{ $car->title }}</h1>
                    
                    <p class="text-xs text-slate-500 mt-2 flex items-center">
                        <i class="fa-solid fa-location-dot text-brand mr-2 text-sm"></i> {{ $car->location }}
                    </p>
                </div>

                <!-- Specifications Grid -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-950 text-base mb-4 flex items-center">
                        <i class="fa-solid fa-gears text-brand mr-2"></i> Thông số kỹ thuật
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="bg-slate-50 p-4 rounded-2xl text-center">
                            <span class="text-xs text-slate-400 font-medium">Hộp số</span>
                            <p class="font-extrabold text-slate-800 text-sm mt-1">
                                {{ $car->transmission === 'automatic' ? 'Tự động' : 'Số sàn' }}
                            </p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl text-center">
                            <span class="text-xs text-slate-400 font-medium">Nhiên liệu</span>
                            <p class="font-extrabold text-slate-800 text-sm mt-1 capitalize">
                                {{ $car->fuel_type === 'gasoline' ? 'Xăng' : ($car->fuel_type === 'diesel' ? 'Dầu' : 'Điện') }}
                            </p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl text-center">
                            <span class="text-xs text-slate-400 font-medium">Số chỗ</span>
                            <p class="font-extrabold text-slate-800 text-sm mt-1">
                                {{ $car->seats }} chỗ
                            </p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl text-center">
                            <span class="text-xs text-slate-400 font-medium">Mức tiêu thụ</span>
                            <p class="font-extrabold text-slate-800 text-sm mt-1">
                                {{ $car->fuel_type === 'electric' ? '15 kWh/100km' : '7.5L / 100km' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-3">
                    <h3 class="font-bold text-slate-950 text-base mb-1 flex items-center">
                        <i class="fa-solid fa-file-invoice text-brand mr-2"></i> Mô tả chi tiết xe
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">
                        {{ $car->description ?? 'Chủ xe chưa cập nhật mô tả chi tiết cho chiếc xe này.' }}
                    </p>
                </div>

                <!-- Amenities Checklist -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-950 text-base mb-4 flex items-center">
                        <i class="fa-solid fa-circle-check text-brand mr-2"></i> Trang bị tiện ích có trên xe
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs font-semibold text-slate-600">
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-map-location-dot text-emerald-500 text-sm"></i>
                            <span>Bản đồ định vị GPS</span>
                        </div>
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-video text-emerald-500 text-sm"></i>
                            <span>Camera hành trình</span>
                        </div>
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-camera text-emerald-500 text-sm"></i>
                            <span>Camera lùi/360</span>
                        </div>
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-bluetooth text-emerald-500 text-sm"></i>
                            <span>Kết nối Bluetooth</span>
                        </div>
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-shield text-emerald-500 text-sm"></i>
                            <span>Cảm biến lốp xe</span>
                        </div>
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-temperature-arrow-down text-emerald-500 text-sm"></i>
                            <span>Điều hòa tự động</span>
                        </div>
                    </div>
                </div>

                <!-- Owner Profile & Contact Card -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-900 text-base mb-4 flex items-center">
                        <i class="fa-solid fa-user-tie text-brand mr-2"></i> Thông tin chủ xe
                    </h3>
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $car->owner->avatar_url }}" 
                                 alt="Owner" class="w-16 h-16 rounded-full object-cover border-2 border-brand/20">
                            <div>
                                <h4 class="font-extrabold text-slate-800 text-base">{{ $car->owner->name }}</h4>
                                <span class="text-xs font-semibold text-slate-400">Thành viên từ {{ $car->owner->created_at->format('m/Y') }}</span>
                                <p class="text-xs text-emerald-600 font-bold mt-1 flex items-center">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block mr-1.5 animate-pulse"></span> Đang online
                                </p>
                            </div>
                        </div>
                        
                        <!-- Contact Action buttons -->
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <!-- Direct Phone call -->
                            <a href="tel:{{ $car->owner->phone ?? '0932030958' }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center space-x-2 bg-slate-900 hover:bg-slate-850 text-white text-xs font-bold px-5 py-3 rounded-xl transition-all shadow-md">
                                <i class="fa-solid fa-phone"></i>
                                <span>Gọi điện thoại</span>
                            </a>
                            <!-- Zalo Chat -->
                            <a href="https://zalo.me/{{ $car->owner->zalo ?? '0932030958' }}" target="_blank" class="flex-1 sm:flex-initial inline-flex items-center justify-center space-x-2 bg-[#0068ff] hover:bg-[#0057d4] text-white text-xs font-bold px-5 py-3 rounded-xl transition-all shadow-md shadow-blue-500/10">
                                <i class="fa-solid fa-message"></i>
                                <span>Nhắn Zalo</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Side: Booking Form, Favorite, Sharing -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Price Card + Favorite button + Booking Form -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-xl p-6 space-y-6 sticky top-28" 
                     x-data="{ 
                        startDate: '', 
                        endDate: '', 
                        pricePerDay: {{ $car->price_per_day }}, 
                        get days() {
                            if(!this.startDate || !this.endDate) return 0;
                            const start = new Date(this.startDate);
                            const end = new Date(this.endDate);
                            const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                            return diff > 0 ? diff : 0;
                        },
                        get total() {
                            return this.days * this.pricePerDay;
                        },
                        formatMoney(val) {
                            return new Intl.NumberFormat('vi-VN').format(val) + ' VNĐ';
                        }
                     }">
                    
                    <!-- Price and Favorite action -->
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Đơn giá</span>
                            <p class="text-2xl font-black text-brand mt-0.5">
                                {{ number_format($car->price_per_day, 0, ',', '.') }}đ <span class="text-xs text-slate-400 font-normal">/ ngày</span>
                            </p>
                        </div>
                        
                        <!-- Favorite Button (AJAX) -->
                        <button onclick="toggleFavorite({{ $car->id }})" id="fav-btn" class="w-12 h-12 rounded-2xl flex items-center justify-center border {{ $isFavorite ? 'border-rose-100 bg-rose-50 text-rose-500' : 'border-slate-100 hover:bg-slate-50 text-slate-400' }} transition-all focus:outline-none">
                            <i class="fa-{{ $isFavorite ? 'solid' : 'regular' }} fa-heart text-xl"></i>
                        </button>
                    </div>

                    <!-- Booking Form -->
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-sm mb-4">Lên lịch đặt thuê xe</h4>
                        <form action="{{ route('cars.book', $car->id) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <!-- Date fields side-by-side -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex flex-col">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Ngày nhận xe</label>
                                    <input type="date" name="start_date" x-model="startDate" min="{{ date('Y-m-d') }}" required
                                           class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold">
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Ngày trả xe</label>
                                    <input type="date" name="end_date" x-model="endDate" :min="startDate || '{{ date('Y-m-d') }}'" required
                                           class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold">
                                </div>
                            </div>

                            <!-- Lộ trình -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex flex-col">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Điểm đi</label>
                                    <input type="text" name="pickup_location" placeholder="Ví dụ: Quận 1" required
                                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold">
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Điểm đến</label>
                                    <input type="text" name="dropoff_location" placeholder="Ví dụ: Vũng Tàu" required
                                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold">
                                </div>
                            </div>

                            <!-- Dịch vụ tài xế -->
                            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center space-x-2.5">
                                    <div class="bg-blue-50 text-brand p-1.5 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-user-tie text-xs"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-700">Thuê kèm tài xế</span>
                                        <span class="text-[9px] text-slate-400 font-medium">Lái xe an toàn suốt hành trình</span>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="has_driver" value="1" class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:width-4 after:w-4 after:transition-all peer-checked:bg-brand"></div>
                                </label>
                            </div>

                            <!-- Thông tin khách hàng -->
                            <div class="border-t border-slate-100 pt-3.5 space-y-3">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Thông tin liên hệ</span>
                                
                                <div class="flex flex-col">
                                    <label class="text-[9px] font-bold text-slate-500 mb-1">Họ và tên</label>
                                    <input type="text" name="customer_name" required 
                                           value="{{ auth()->check() ? auth()->user()->name : '' }}"
                                           placeholder="Nhập họ và tên"
                                           class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold">
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex flex-col">
                                        <label class="text-[9px] font-bold text-slate-500 mb-1">Số điện thoại</label>
                                        <input type="tel" name="customer_phone" required
                                               value="{{ auth()->check() ? auth()->user()->phone : '' }}"
                                               placeholder="Nhập số điện thoại"
                                               class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold">
                                    </div>
                                    <div class="flex flex-col">
                                        <label class="text-[9px] font-bold text-slate-500 mb-1">Email</label>
                                        <input type="email" name="customer_email" required
                                               value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                               placeholder="Nhập địa chỉ email"
                                               class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold">
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="flex flex-col">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Ghi chú gửi chủ xe</label>
                                <textarea name="notes" placeholder="Lời nhắn, lịch trình, yêu cầu giao nhận xe..." rows="2"
                                          class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold"></textarea>
                            </div>

                            <!-- Live Price Calculation display -->
                            <div x-show="days > 0" x-transition class="bg-blue-50/50 rounded-2xl p-4 space-y-2.5 text-xs font-semibold text-slate-600 border border-brand/5">
                                <div class="flex justify-between">
                                    <span>Đơn giá</span>
                                    <span x-text="formatMoney(pricePerDay)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Số ngày thuê</span>
                                    <span class="text-brand font-bold" x-text="days + ' ngày'"></span>
                                </div>
                                <div class="flex justify-between border-t border-slate-100 pt-2.5 font-bold text-sm text-slate-900">
                                    <span>Tổng cộng dự tính</span>
                                    <span class="text-brand font-black" x-text="formatMoney(total)"></span>
                                </div>
                            </div>

                            <!-- Submit Request -->
                            @auth
                                <button type="submit" class="w-full bg-brand hover:bg-brand-hover text-white text-sm font-semibold py-3.5 rounded-xl shadow-lg shadow-brand/20 transition-all flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-file-contract"></i>
                                    <span>Gửi yêu cầu đặt xe</span>
                                </button>
                            @else
                                <a href="/member/login" class="w-full inline-flex items-center justify-center bg-slate-900 hover:bg-brand text-white text-sm font-semibold py-3.5 rounded-xl transition-all">
                                    Đăng nhập để đặt xe
                                </a>
                            @endauth
                        </form>
                    </div>

                    <!-- Share features -->
                    <div class="border-t border-slate-100 pt-4 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Chia sẻ xe</span>
                        <div class="flex space-x-2.5">
                            <button onclick="copyShareLink()" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-500 text-xs flex items-center justify-center transition-colors" title="Copy Link">
                                <i class="fa-solid fa-copy"></i>
                            </button>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
                               class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-blue-50 hover:text-blue-600 text-slate-500 text-xs flex items-center justify-center transition-colors" title="Facebook">
                                <i class="fa-brands fa-facebook"></i>
                            </a>
                            <a href="https://sp.zalo.me/share_to_zalo?url={{ urlencode(request()->url()) }}" target="_blank"
                               class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-blue-50 hover:text-[#0068ff] text-slate-500 text-xs flex items-center justify-center transition-colors font-extrabold" title="Zalo" style="font-size: 9px; line-height: 1;">
                                Zalo
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Similar Cars Slider -->
        <div class="border-t border-slate-200/50 pt-16 mt-16">
            <h2 class="text-xl font-extrabold text-slate-900 mb-8 flex items-center">
                <i class="fa-solid fa-car-rear text-brand mr-2.5"></i> Gợi ý các dòng xe tương tự
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($similarCars as $simCar)
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition-shadow">
                        <div class="h-44 overflow-hidden relative">
                            <img src="{{ $simCar->thumbnail_url }}" 
                                 alt="{{ $simCar->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $simCar->brand }}</span>
                                <h4 class="text-sm font-bold text-slate-800 line-clamp-1"><a href="{{ route('cars.show', $simCar->slug) }}">{{ $simCar->title }}</a></h4>
                            </div>
                            <div class="flex items-center justify-between border-t border-slate-100 pt-3 mt-3">
                                <span class="text-xs font-black text-brand">{{ number_format($simCar->price_per_day, 0, ',', '.') }}đ/ngày</span>
                                <a href="{{ route('cars.show', $simCar->slug) }}" class="text-[10px] font-bold text-slate-500 hover:text-brand">Xem <i class="fa-solid fa-chevron-right text-[8px] ml-0.5"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        // Toggle Favorite function via AJAX
        function toggleFavorite(carId) {
            fetch(`/cars/${carId}/favorite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => {
                if (res.status === 401) {
                    if (window.showToast) {
                        window.showToast('Vui lòng đăng nhập để lưu xe yêu thích!', 'error');
                    } else {
                        alert('Vui lòng đăng nhập để lưu xe yêu thích!');
                    }
                    setTimeout(() => {
                        window.location.href = '/member/login';
                    }, 1000);
                    return;
                }
                return res.json();
            })
            .then(data => {
                if (data && data.success) {
                    const btn = document.getElementById('fav-btn');
                    const icon = btn.querySelector('i');
                    
                    if (data.is_favorite) {
                        btn.className = 'w-12 h-12 rounded-2xl flex items-center justify-center border border-rose-100 bg-rose-50 text-rose-500 transition-all focus:outline-none';
                        icon.className = 'fa-solid fa-heart text-xl';
                    } else {
                        btn.className = 'w-12 h-12 rounded-2xl flex items-center justify-center border border-slate-100 hover:bg-slate-50 text-slate-400 transition-all focus:outline-none';
                        icon.className = 'fa-regular fa-heart text-xl';
                    }
                    
                    // Show a toast message or alert
                    if (window.showToast) {
                        window.showToast(data.message, data.is_favorite ? 'success' : 'info');
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(err => console.error(err));
        }

        // Copy Share Link to Clipboard
        function copyShareLink() {
            const el = document.createElement('textarea');
            el.value = window.location.href;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            if (window.showToast) {
                window.showToast('Đường dẫn của xe đã được sao chép vào bộ nhớ tạm!', 'success');
            } else {
                alert('Đường dẫn của xe đã được sao chép vào bộ nhớ tạm!');
            }
        }
    </script>
@endsection
