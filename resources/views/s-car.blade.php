@extends('layouts.app')

@section('title', 'Thông Tin & Bảng Giá Xe Ô Tô S-Car - NKS Car Rental')
@section('meta_description', 'Tra cứu thông tin, thông số kỹ thuật và bảng giá niêm yết mới nhất của hơn 300 dòng xe ô tô từ 16 thương hiệu hàng đầu. Nhận tư vấn đàm phán giá tốt từ NKS.')

@section('content')
<div class="bg-slate-50 min-h-screen py-12" x-data="{
    searchQuery: '',
    activeBrand: 'all',
    activeCategory: 'all',
    limit: 20,
    showAllBrands: false,
    
    // Modal & Đàm phán
    modalOpen: false,
    selectedCar: null,
    clientName: '{{ auth()->check() ? auth()->user()->name : '' }}',
    clientPhone: '{{ auth()->check() ? auth()->user()->phone : '' }}',
    clientEmail: '{{ auth()->check() ? auth()->user()->email : '' }}',
    clientMessage: '',
    submitting: false,

    // Toast Notification
    toast: {
        show: false,
        message: '',
        type: 'success'
    },

    showToast(message, type = 'success') {
        this.toast.show = true;
        this.toast.message = message;
        this.toast.type = type;
        setTimeout(() => {
            this.toast.show = false;
        }, 4000);
    },

    getFilteredCars() {
        return cars.filter(car => {
            const matchesBrand = this.activeBrand === 'all' || car.brand === this.activeBrand;
            const matchesCategory = this.activeCategory === 'all' || car.category === this.activeCategory;
            
            const normalizedQuery = this.searchQuery.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            const normalizedModel = car.model.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            const normalizedBrand = car.brand_name.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            const normalizedVersion = car.version.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            const normalizedEngine = car.engine.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            
            const matchesSearch = normalizedModel.includes(normalizedQuery) || 
                                  normalizedBrand.includes(normalizedQuery) ||
                                  normalizedVersion.includes(normalizedQuery) ||
                                  normalizedEngine.includes(normalizedQuery);
                                  
            return matchesBrand && matchesCategory && matchesSearch;
        });
    },

    openNegotiate(car) {
        this.selectedCar = car;
        this.clientMessage = 'Tôi muốn nhận báo giá đàm phán tốt nhất cho dòng xe ' + car.brand_name + ' ' + car.model + ' (' + car.version + ')';
        this.modalOpen = true;
    },

    closeModal() {
        this.modalOpen = false;
        this.selectedCar = null;
    },

    submitNegotiate() {
        if (!this.clientName || !this.clientPhone || !this.clientEmail) {
            this.showToast('Vui lòng điền đầy đủ Họ tên, Số điện thoại và Email.', 'danger');
            return;
        }

        this.submitting = true;

        fetch('{{ route('scar.negotiate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                name: this.clientName,
                phone: this.clientPhone,
                email: this.clientEmail,
                message: this.clientMessage,
                car_brand: this.selectedCar.brand_name,
                car_model: this.selectedCar.model,
                car_version: this.selectedCar.version,
                list_price: this.selectedCar.list_price
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.showToast(data.message, 'success');
                this.closeModal();
            } else {
                this.showToast('Lỗi gửi yêu cầu: ' + (data.message || 'Vui lòng thử lại.'), 'danger');
            }
        })
        .catch(err => {
            this.showToast('Lỗi kết nối máy chủ. Vui lòng thử lại sau.', 'danger');
        })
        .finally(() => {
            this.submitting = false;
        });
    }
}">
    <!-- Toast Notification -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-24 right-4 z-50 max-w-sm w-full bg-white shadow-2xl rounded-2xl border-l-4 p-4 flex items-start gap-3"
         :class="toast.type === 'success' ? 'border-emerald-500' : 'border-rose-500'"
         style="display: none;">
        <div class="flex-shrink-0 mt-0.5">
            <template x-if="toast.type === 'success'">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            </template>
            <template x-if="toast.type !== 'success'">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg"></i>
            </template>
        </div>
        <div class="flex-grow">
            <p class="text-sm font-bold text-slate-800" x-text="toast.type === 'success' ? 'Thành công' : 'Đã xảy ra lỗi'"></p>
            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed" x-text="toast.message"></p>
        </div>
        <button @click="toast.show = false" class="text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Header Banner -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
            <!-- Decorative light vectors -->
            <div class="absolute -right-16 -top-16 w-72 h-72 rounded-full bg-indigo-500/10 blur-3xl"></div>
            <div class="absolute -left-16 -bottom-16 w-72 h-72 rounded-full bg-violet-600/10 blur-3xl"></div>

            <div class="relative z-10 max-w-3xl">
                <span class="bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4 inline-block">S-Car Catalog</span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-4">
                    Thông Tin & Bảng Giá Xe Thị Trường
                </h1>
                <p class="text-slate-300 text-sm sm:text-base mb-8 leading-relaxed">
                    Tra cứu giá niêm yết chính hãng và nhận báo giá đàm phán ưu đãi tốt nhất từ mạng lưới đối tác đại lý ô tô của NKS. Hỗ trợ lọc theo thương hiệu hoặc kiểu dáng động cơ xăng, dầu, Hybrid, điện.
                </p>

                <!-- Search Input -->
                <div class="relative max-w-xl shadow-lg rounded-2xl overflow-hidden bg-white">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    </div>
                    <input type="text" 
                           x-model="searchQuery"
                           @input="limit = 20"
                           placeholder="Tìm kiếm dòng xe, phiên bản, thông số động cơ..."
                           class="block w-full pl-12 pr-4 py-4 border-none text-slate-800 focus:outline-none focus:ring-0 text-sm sm:text-base">
                    <button type="button" 
                            x-show="searchQuery !== ''" 
                            @click="searchQuery = ''; limit = 20;"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600"
                            style="display: none;">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Blocks Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Brand Filter Grid (Left, Span 2) -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-slate-800 uppercase tracking-wide flex items-center gap-2">
                        <i class="fa-solid fa-copyright text-indigo-500"></i> Hãng xe
                    </h3>
                    <button type="button" 
                            @click="showAllBrands = !showAllBrands" 
                            class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                        <span x-text="showAllBrands ? 'Thu gọn' : 'Xem thêm'"></span>
                        <i class="fa-solid" :class="showAllBrands ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                </div>

                <!-- Brand Grid Buttons -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <!-- Brand: ALL -->
                    <button type="button" 
                            @click="activeBrand = 'all'; limit = 20;"
                            class="flex flex-col items-center justify-center py-4 px-3 rounded-2xl border-2 transition-all text-center gap-1.5"
                            :class="activeBrand === 'all' ? 'border-indigo-600 bg-indigo-50/50 text-indigo-600 font-bold' : 'border-slate-100 hover:border-slate-200 text-slate-600 bg-slate-50/30'">
                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-sm font-extrabold shadow-inner"
                             :class="activeBrand === 'all' && 'bg-indigo-600 text-white'">
                            *
                        </div>
                        <span class="text-xs">Tất cả hãng</span>
                    </button>

                    <!-- Dynamically Render Brands -->
                    @foreach($brands as $key => $brand)
                        <button type="button" 
                                @click="activeBrand = '{{ $key }}'; limit = 20;"
                                x-show="showAllBrands || {{ $loop->index < 7 ? 'true' : 'false' }}"
                                class="flex flex-col items-center justify-center py-4 px-3 rounded-2xl border-2 transition-all text-center gap-1.5"
                                :class="activeBrand === '{{ $key }}' ? 'border-indigo-600 bg-indigo-50/50 text-indigo-600 font-bold' : 'border-slate-100 hover:border-slate-200 text-slate-600 bg-slate-50/30'">
                            
                            <!-- Brand logo container with fallback -->
                            <div class="w-10 h-10 rounded-xl bg-white border border-slate-150 shadow-sm flex items-center justify-center p-1.5 overflow-hidden"
                                 :class="activeBrand === '{{ $key }}' && 'border-indigo-200 bg-indigo-50'">
                                <img src="https://cdn.jsdelivr.net/gh/filippofilip95/car-logos-dataset@master/logos/thumb/{{ $brand['logo'] }}" 
                                     alt="{{ $brand['name'] }}" 
                                     class="w-full h-full object-contain"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($brand['name']) }}&background=f8fafc&color=334155&font-size=0.45&bold=true'">
                            </div>
                            <span class="text-xs truncate max-w-full">{{ $brand['name'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Body Style Filter (Right, Span 1) -->
            <div class="lg:col-span-1 bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 uppercase tracking-wide flex items-center gap-2 mb-5">
                    <i class="fa-solid fa-car text-indigo-500"></i> Kiểu dáng & Động cơ
                </h3>

                <!-- Category Style List -->
                <div class="grid grid-cols-2 gap-2">
                    <!-- Category: ALL -->
                    <button type="button" 
                            @click="activeCategory = 'all'; limit = 20;"
                            class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl border transition-all text-left text-xs font-semibold"
                            :class="activeCategory === 'all' ? 'border-indigo-500 bg-indigo-50/35 text-indigo-600' : 'border-slate-100 hover:bg-slate-50 text-slate-600'">
                        <i class="fa-solid fa-list-check w-4 text-center"></i>
                        <span>Tất cả kiểu dáng</span>
                    </button>

                    <!-- Render Categories -->
                    @foreach($categories as $key => $name)
                        <button type="button" 
                                @click="activeCategory = '{{ $key }}'; limit = 20;"
                                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl border transition-all text-left text-xs font-semibold"
                                :class="activeCategory === '{{ $key }}' ? 'border-indigo-500 bg-indigo-50/35 text-indigo-600' : 'border-slate-100 hover:bg-slate-50 text-slate-600'">
                            
                            @if($key === 'sedan')
                                <i class="fa-solid fa-car-side w-4 text-center text-slate-400" :class="activeCategory === '{{ $key }}' && 'text-indigo-500'"></i>
                            @elseif($key === 'suv')
                                <i class="fa-solid fa-truck-monster w-4 text-center text-slate-400" :class="activeCategory === '{{ $key }}' && 'text-indigo-500'"></i>
                            @elseif($key === 'crossover')
                                <i class="fa-solid fa-car w-4 text-center text-slate-400" :class="activeCategory === '{{ $key }}' && 'text-indigo-500'"></i>
                            @elseif($key === 'mpv')
                                <i class="fa-solid fa-van-shuttle w-4 text-center text-slate-400" :class="activeCategory === '{{ $key }}' && 'text-indigo-500'"></i>
                            @elseif($key === 'pickup')
                                <i class="fa-solid fa-truck-pickup w-4 text-center text-slate-400" :class="activeCategory === '{{ $key }}' && 'text-indigo-500'"></i>
                            @elseif($key === 'ev')
                                <i class="fa-solid fa-charging-station w-4 text-center text-slate-400" :class="activeCategory === '{{ $key }}' && 'text-indigo-500'"></i>
                            @elseif($key === 'hybrid')
                                <i class="fa-solid fa-leaf w-4 text-center text-slate-400" :class="activeCategory === '{{ $key }}' && 'text-indigo-500'"></i>
                            @else
                                <i class="fa-solid fa-car w-4 text-center text-slate-400" :class="activeCategory === '{{ $key }}' && 'text-indigo-500'"></i>
                            @endif
                            <span class="truncate">{{ $name }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Catalog Table Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Table Header Area -->
            <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        Bảng giá xe mới 
                        <span class="text-xs bg-indigo-50 text-indigo-600 px-2.5 py-0.5 rounded-full font-bold" 
                              x-text="getFilteredFaqs = getFilteredCars().length + ' phiên bản'"></span>
                    </h2>
                </div>
            </div>

            <!-- Empty State for filtered results -->
            <div x-show="getFilteredCars().length === 0" class="p-12 text-center" style="display: none;">
                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-car text-3xl"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">Không tìm thấy dòng xe nào</h3>
                <p class="text-slate-500 text-xs max-w-sm mx-auto">
                    Chúng tôi không tìm thấy kết quả phù hợp với từ khóa hoặc bộ lọc đã chọn. Vui lòng thử tìm từ khóa khác hoặc thiết lập lại bộ lọc.
                </p>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto" x-show="getFilteredCars().length > 0">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                            <th class="py-4 px-6">Hãng xe</th>
                            <th class="py-4 px-6">Dòng xe</th>
                            <th class="py-4 px-6">Phiên bản</th>
                            <th class="py-4 px-6">Phân khúc</th>
                            <th class="py-4 px-6">Động cơ</th>
                            <th class="py-4 px-6">Giá niêm yết</th>
                            <th class="py-4 px-6 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        <template x-for="(car, index) in getFilteredCars().slice(0, limit)" :key="car.id">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <!-- Hãng xe -->
                                <td class="py-4 px-6 font-bold text-slate-800">
                                    <span class="inline-flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-white border border-slate-100 shadow-sm flex items-center justify-center p-1 overflow-hidden flex-shrink-0">
                                            <img :src="'https://cdn.jsdelivr.net/gh/filippofilip95/car-logos-dataset@master/logos/thumb/' + car.logo" 
                                                 :alt="car.brand_name"
                                                 class="w-full h-full object-contain"
                                                 x-on:error="$el.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(car.brand_name) + '&background=f8fafc&color=334155&font-size=0.45&bold=true'">
                                        </div>
                                        <span x-text="car.brand_name"></span>
                                    </span>
                                </td>
                                <!-- Dòng xe -->
                                <td class="py-4 px-6 font-semibold" x-text="car.model"></td>
                                <!-- Phiên bản -->
                                <td class="py-4 px-6 text-slate-600" x-text="car.version"></td>
                                <!-- Phân khúc -->
                                <td class="py-4 px-6 text-slate-500">
                                    <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-md font-semibold" x-text="car.category_name"></span>
                                </td>
                                <!-- Động cơ -->
                                <td class="py-4 px-6 text-slate-600" x-text="car.engine"></td>
                                <!-- Giá niêm yết -->
                                <td class="py-4 px-6 font-extrabold text-indigo-600">
                                    <span x-text="numberFormat(car.list_price)"></span>
                                </td>
                                <!-- Hành động Đàm Phán -->
                                <td class="py-4 px-6 text-center">
                                    <button type="button" 
                                            @click="openNegotiate(car)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm shadow-indigo-100">
                                        <i class="fa-solid fa-comments-dollar"></i> Đàm phán
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- View More Button -->
            <div x-show="getFilteredCars().length > limit" 
                 class="px-6 py-5 bg-slate-50/50 border-t border-slate-100 text-center" 
                 style="display: none;">
                <button type="button" 
                        @click="limit += 20"
                        class="px-5 py-2.5 border border-slate-200 hover:border-slate-300 bg-white text-slate-700 hover:text-slate-800 text-xs font-bold rounded-xl transition-all shadow-sm">
                    Tải thêm dòng xe <i class="fa-solid fa-angle-down ml-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Negotiation Modal -->
    <div x-show="modalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true" 
         style="display: none;">
        
        <!-- Background backdrop -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-filter backdrop-blur-sm transition-opacity" 
                 @click="closeModal()" 
                 aria-hidden="true"></div>

            <!-- Modal position helper -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel Box -->
            <div x-show="modalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative z-10 inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-slate-900 to-indigo-950 px-6 py-5 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-bold" id="modal-title">Yêu cầu đàm phán giá xe</h3>
                        <p class="text-[11px] text-slate-300 mt-0.5" x-text="selectedCar ? selectedCar.brand_name + ' ' + selectedCar.model + ' (' + selectedCar.version + ')' : ''"></p>
                    </div>
                    <button type="button" @click="closeModal()" class="text-slate-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body Form -->
                <form @submit.prevent="submitNegotiate()" class="p-6 flex flex-col gap-4">
                    <!-- Display Car Basic Price Info -->
                    <div class="bg-indigo-50/50 rounded-2xl p-4 border border-indigo-50 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Giá niêm yết hãng</span>
                            <span class="text-lg font-black text-indigo-600" x-text="selectedCar ? numberFormat(selectedCar.list_price) : ''"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Hỗ trợ NKS</span>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md flex items-center gap-1">
                                <i class="fa-solid fa-tags"></i> Tối ưu chi phí
                            </span>
                        </div>
                    </div>

                    <!-- Client Name input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Họ và tên *</label>
                        <input type="text" 
                               x-model="clientName"
                               required
                               placeholder="Ví dụ: Nguyễn Văn A"
                               class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500 transition-colors">
                    </div>

                    <!-- Row: Phone & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Số điện thoại *</label>
                            <input type="tel" 
                                   x-model="clientPhone"
                                   required
                                   placeholder="Ví dụ: 0932030958"
                                   class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Địa chỉ Email *</label>
                            <input type="email" 
                                   x-model="clientEmail"
                                   required
                                   placeholder="Ví dụ: a@example.com"
                                   class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                    </div>

                    <!-- Message input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Lời nhắn / Yêu cầu chi tiết</label>
                        <textarea x-model="clientMessage"
                                  rows="3"
                                  placeholder="Ví dụ: Cần tư vấn chiết khấu đại lý tốt nhất, màu xe sẵn giao..."
                                  class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-indigo-500 transition-colors resize-none"></textarea>
                    </div>

                    <p class="text-[11px] text-slate-400 leading-relaxed italic">
                        * Bằng việc gửi yêu cầu này, thông tin của bạn sẽ được lưu trữ an toàn và chuyên viên NKS sẽ liên hệ các đối lý đại lý trực thuộc để gửi ưu đãi mua xe tốt nhất đến bạn.
                    </p>

                    <!-- Modal Actions footer -->
                    <div class="border-t border-slate-100 pt-4 mt-2 flex items-center justify-end gap-2.5">
                        <button type="button" 
                                @click="closeModal()" 
                                class="px-4 py-2.5 border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-colors">
                            Hủy bỏ
                        </button>
                        <button type="submit" 
                                :disabled="submitting"
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm shadow-indigo-100 flex items-center gap-1.5 disabled:opacity-50">
                            <template x-if="submitting">
                                <svg class="animate-spin -ml-0.5 mr-1.5 h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <span x-text="submitting ? 'Đang gửi...' : 'Gửi yêu cầu đàm phán'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Load static database of 320 cars
    const cars = @json($cars);

    // Format helper for currency (VND)
    function numberFormat(val) {
        if (!val) return '0 đ';
        // Convert to million representation
        const mil = val / 1000000;
        if (mil >= 1000) {
            const bil = mil / 1000;
            return bil.toFixed(1).replace('.0', '') + ' tỷ';
        }
        return mil.toFixed(0) + ' triệu';
    }
</script>
@endsection
