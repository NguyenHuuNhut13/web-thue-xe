@extends('layouts.app')

@section('title', 'Xe yêu thích của tôi - NKS Thuê xe du lịch')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Page Header -->
        <div class="mb-10 text-center sm:text-left flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/60 pb-6">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 flex items-center justify-center sm:justify-start gap-3">
                    <span class="p-2 bg-rose-50 text-rose-500 rounded-2xl">
                        <i class="fa-solid fa-heart"></i>
                    </span>
                    <span>Xe yêu thích của tôi</span>
                </h1>
                <p class="text-sm text-slate-400 font-medium mt-2 leading-relaxed">
                    Xem lại và đặt thuê nhanh chóng các mẫu xe du lịch bạn đã lưu trữ.
                </p>
            </div>
            <a href="{{ route('cars.index') }}" class="self-center sm:self-auto inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-brand text-white font-bold text-xs rounded-xl shadow-md transition-all hover:-translate-y-0.5">
                <i class="fa-solid fa-car-rear text-[10px]"></i>
                Khám phá thêm xe khác
            </a>
        </div>

        <!-- Favorites Grid -->
        @if($cars->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cars as $car)
                    <div class="car-card-container bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col justify-between relative">
                        
                        <!-- Unfavorite quick button -->
                        <button type="button" onclick="toggleFavoriteOnList(this, {{ $car->id }})" 
                                class="absolute top-3 left-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm border border-rose-100 flex items-center justify-center text-rose-500 hover:bg-white shadow-sm hover:scale-105 transition-all focus:outline-none z-10" 
                                title="Bỏ lưu yêu thích">
                            <i class="fa-solid fa-heart text-sm"></i>
                        </button>

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
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $cars->links() }}
            </div>
        @else
            <!-- Empty state -->
            <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 max-w-2xl mx-auto shadow-sm">
                <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <i class="fa-regular fa-heart text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Danh sách yêu thích trống</h3>
                <p class="text-sm text-slate-400 mt-2 max-w-sm mx-auto leading-relaxed">
                    Bạn chưa lưu chiếc xe nào. Hãy nhấn nút <i class="fa-regular fa-heart text-slate-400 mx-0.5"></i> ở chi tiết xe để lưu và xem lại tại đây nhé!
                </p>
                <div class="mt-8">
                    <a href="{{ route('cars.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-hover text-white text-sm font-bold rounded-xl shadow-lg shadow-brand/20 transition-all hover:-translate-y-0.5">
                        Khám phá danh sách xe
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        function toggleFavoriteOnList(btn, carId) {
            fetch(`/cars/${carId}/favorite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => {
                if (res.status === 401) {
                    window.location.href = '/member/login';
                    return;
                }
                return res.json();
            })
            .then(data => {
                if (data && data.success) {
                    if (window.showToast) {
                        window.showToast(data.message, 'success');
                    }
                    
                    const card = btn.closest('.car-card-container');
                    if (card) {
                        card.style.transition = 'all 0.35s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            card.remove();
                            const remainingCards = document.querySelectorAll('.car-card-container');
                            if (remainingCards.length === 0) {
                                window.location.reload();
                            }
                        }, 350);
                    }
                }
            })
            .catch(err => console.error(err));
        }
    </script>
@endsection
