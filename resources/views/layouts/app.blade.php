<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NKS - Website Cho Thuê Xe Du Lịch Tự Lái Hàng Đầu')</title>
    <meta name="description" content="@yield('meta_description', 'Hệ thống kết nối chủ xe có nhu cầu cho thuê xe và khách hàng thực hiện đặt xe tự lái, có tài xế nhanh chóng, uy tín tại TP.HCM.')">
    
    <!-- Google Fonts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- FontAwesome for Premium Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AlpineJS for Interactive UI (Dropdowns, Mobile Menu) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @yield('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800">

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-100" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="bg-brand text-white p-2 rounded-xl flex items-center justify-center shadow-md shadow-brand/20">
                            <i class="fa-solid fa-car-side text-xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-extrabold tracking-tight text-brand">NKS</span>
                            <span class="text-[9px] uppercase tracking-wider font-semibold text-slate-400 -mt-1">Connecting Brand</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8 font-medium">
                    <a href="{{ route('home') }}" class="text-slate-600 hover:text-brand transition-colors py-2 {{ request()->routeIs('home') ? 'text-brand font-semibold border-b-2 border-brand' : '' }}">
                        Trang chủ
                    </a>
                    <a href="{{ route('cars.index') }}" class="text-slate-600 hover:text-brand transition-colors py-2 {{ request()->routeIs('cars.index') && !request()->routeIs('cars.map') ? 'text-brand font-semibold border-b-2 border-brand' : '' }}">
                        Thuê xe du lịch
                    </a>
                    <a href="{{ route('cars.map') }}" class="text-slate-600 hover:text-brand transition-colors py-2 {{ request()->routeIs('cars.map') ? 'text-brand font-semibold border-b-2 border-brand' : '' }}">
                        Bản đồ tìm xe
                    </a>
                    <a href="{{ route('blogs.index') }}" class="text-slate-600 hover:text-brand transition-colors py-2 {{ request()->routeIs('blogs.*') ? 'text-brand font-semibold border-b-2 border-brand' : '' }}">
                        Cẩm nang & Tin tức
                    </a>
                    <a href="{{ route('contact') }}" class="text-slate-600 hover:text-brand transition-colors py-2 {{ request()->routeIs('contact') ? 'text-brand font-semibold border-b-2 border-brand' : '' }}">
                        Liên hệ
                    </a>
                </nav>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center space-x-2 text-slate-700 hover:text-brand transition-colors py-2 focus:outline-none">
                                <img src="{{ auth()->user()->avatar_url }}" 
                                     alt="Avatar" class="w-9 h-9 rounded-full object-cover border-2 border-brand/20">
                                <span class="font-medium text-sm max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                            </button>
                            <!-- Dropdown -->
                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-1 z-50">
                                <a href="{{ auth()->user()->role === 'admin' ? '/admin' : '/member' }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    <i class="fa-solid fa-user text-slate-400 w-5"></i> Trang quản lý
                                </a>
                                <a href="{{ route('cars.favorites') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    <i class="fa-solid fa-heart text-slate-400 w-5"></i> Xe yêu thích
                                </a>
                                <a href="/member/cars" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    <i class="fa-solid fa-car text-slate-400 w-5"></i> Xe của tôi
                                </a>
                                <a href="/member/bookings" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors border-b border-slate-100">
                                    <i class="fa-solid fa-receipt text-slate-400 w-5"></i> Đơn đặt xe
                                </a>
                                <form method="POST" action="/member/logout">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors font-medium">
                                        <i class="fa-solid fa-sign-out-alt text-rose-400 w-5"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="/member/login" class="text-sm font-semibold text-slate-600 hover:text-brand transition-colors">
                            Đăng nhập
                        </a>
                        <a href="/member/register" class="bg-brand hover:bg-brand-hover text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-brand/20 transition-all hover:-translate-y-0.5">
                            Đăng ký chủ xe
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-600 hover:text-brand focus:outline-none p-2 rounded-lg hover:bg-slate-50 transition-colors">
                        <i x-show="!mobileMenuOpen" class="fa-solid fa-bars text-2xl"></i>
                        <i x-show="mobileMenuOpen" class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-slate-100 bg-white py-4 px-4 space-y-3 shadow-inner">
            <a href="{{ route('home') }}" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-all">
                Trang chủ
            </a>
            <a href="{{ route('cars.index') }}" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-all">
                Thuê xe du lịch
            </a>
            <a href="{{ route('cars.map') }}" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-all">
                Bản đồ tìm xe
            </a>
            <a href="{{ route('blogs.index') }}" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-all">
                Cẩm nang & Tin tức
            </a>
            <a href="{{ route('contact') }}" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-all">
                Liên hệ
            </a>
            <div class="border-t border-slate-100 pt-4 px-4 flex flex-col space-y-3">
                @auth
                    <div class="flex items-center space-x-3 mb-2">
                        <img src="{{ auth()->user()->avatar_url }}" 
                             alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <div class="font-semibold text-slate-800 text-sm">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-400">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <a href="{{ auth()->user()->role === 'admin' ? '/admin' : '/member' }}" class="block py-2 text-sm font-medium text-slate-600 hover:text-brand"><i class="fa-solid fa-user w-6"></i> Trang quản lý</a>
                    <a href="{{ route('cars.favorites') }}" class="block py-2 text-sm font-medium text-slate-600 hover:text-brand"><i class="fa-solid fa-heart w-6"></i> Xe yêu thích</a>
                    <a href="/member/cars" class="block py-2 text-sm font-medium text-slate-600 hover:text-brand"><i class="fa-solid fa-car w-6"></i> Xe của tôi</a>
                    <a href="/member/bookings" class="block py-2 text-sm font-medium text-slate-600 hover:text-brand"><i class="fa-solid fa-receipt w-6"></i> Đơn đặt xe</a>
                    <form method="POST" action="/member/logout">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-sm font-semibold text-rose-600 hover:text-rose-700">
                            <i class="fa-solid fa-sign-out-alt w-6"></i> Đăng xuất
                        </button>
                    </form>
                @else
                    <a href="/member/login" class="block text-center py-2.5 border border-slate-200 rounded-xl text-base font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        Đăng nhập
                    </a>
                    <a href="/member/register" class="block text-center py-2.5 bg-brand text-white rounded-xl text-base font-semibold shadow-md shadow-brand/20">
                        Đăng ký chủ xe
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Area -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                
                <!-- Col 1: About NKS -->
                <div class="space-y-4">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="bg-brand text-white p-2 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-car-side text-lg"></i>
                        </div>
                        <span class="text-2xl font-extrabold tracking-tight text-white">NKS</span>
                    </a>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Hệ thống kết nối chủ xe có nhu cầu cho thuê xe du lịch tự lái/có tài xế và khách hàng có nhu cầu thuê xe nhanh chóng, bảo mật và uy tín.
                    </p>
                    <div class="flex space-x-4 pt-2">
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-brand text-white flex items-center justify-center transition-colors"><i class="fa-brands fa-facebook-f text-sm"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-brand text-white flex items-center justify-center transition-colors"><i class="fa-brands fa-youtube text-sm"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-brand text-white flex items-center justify-center transition-colors"><i class="fa-brands fa-instagram text-sm"></i></a>
                    </div>
                </div>

                <!-- Col 2: Useful Links -->
                <div>
                    <h3 class="text-white font-bold text-base uppercase tracking-wider mb-6">Liên kết nhanh</h3>
                    <ul class="space-y-3.5 text-sm font-medium">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Trang chủ</a></li>
                        <li><a href="{{ route('cars.index') }}" class="hover:text-white transition-colors">Thuê xe tự lái</a></li>
                        <li><a href="{{ route('cars.map') }}" class="hover:text-white transition-colors">Bản đồ xe cho thuê</a></li>
                        <li><a href="{{ route('blogs.index') }}" class="hover:text-white transition-colors">Tin tức & Kinh nghiệm</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Liên hệ hỗ trợ</a></li>
                    </ul>
                </div>

                <!-- Col 3: Categories -->
                <div>
                    <h3 class="text-white font-bold text-base uppercase tracking-wider mb-6">Dòng xe cho thuê</h3>
                    <ul class="space-y-3.5 text-sm font-medium">
                        <li><a href="{{ route('cars.index') }}?seats=5" class="hover:text-white transition-colors">Xe du lịch 4 - 5 chỗ</a></li>
                        <li><a href="{{ route('cars.index') }}?seats=7" class="hover:text-white transition-colors">Xe du lịch 7 chỗ</a></li>
                        <li><a href="{{ route('cars.index') }}?transmission=automatic" class="hover:text-white transition-colors">Xe tự lái số tự động</a></li>
                        <li><a href="{{ route('cars.index') }}?fuel_type=electric" class="hover:text-white transition-colors">Thuê xe điện VinFast</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact info -->
                <div>
                    <h3 class="text-white font-bold text-base uppercase tracking-wider mb-6">Thông tin liên hệ</h3>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start space-x-3">
                            <i class="fa-solid fa-location-dot text-brand text-base mt-0.5"></i>
                            <span class="text-slate-400">222 Le Van Sy St., Ward Nhiêu Lộc, HCMC</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fa-solid fa-phone text-brand text-base"></i>
                            <a href="tel:+84932030958" class="text-slate-400 hover:text-white transition-colors">(+84) 932.030.958</a>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fa-solid fa-message text-brand text-base"></i>
                            <a href="https://zalo.me/0932030958" target="_blank" class="text-slate-400 hover:text-white transition-colors">Nhắn tin Zalo (0932.030.958)</a>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fa-solid fa-envelope text-brand text-base"></i>
                            <a href="mailto:system@nks.vn" class="text-slate-400 hover:text-white transition-colors">system@nks.vn</a>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Footer copyright -->
            <div class="border-t border-slate-800 pt-8 mt-8 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500 font-medium">
                <div>
                    &copy; {{ date('Y') }} NKS. Bảo lưu mọi quyền. Phát triển bởi NKS Team.
                </div>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="http://www.nks.com.vn" target="_blank" class="hover:text-slate-400 transition-colors">www.nks.com.vn</a>
                    <a href="#" class="hover:text-slate-400 transition-colors">Điều khoản dịch vụ</a>
                    <a href="#" class="hover:text-slate-400 transition-colors">Chính sách bảo mật</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-24 right-4 z-[99999] flex flex-col gap-3 max-w-sm w-full pointer-events-none px-4 sm:px-0"></div>

    <script>
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = 'transform translate-x-full opacity-0 transition-all duration-300 ease-out pointer-events-auto bg-white border rounded-2xl shadow-xl p-4 flex items-start gap-3 border-slate-100 w-full';
            toast.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
            
            let icon = '';
            let bgIcon = '';
            
            if (type === 'success') {
                icon = '<i class="fa-solid fa-circle-check text-sm"></i>';
                bgIcon = 'background-color: #ecfdf5; color: #10b981; border: 1px solid #d1fae5;';
            } else if (type === 'info') {
                icon = '<i class="fa-solid fa-circle-info text-sm"></i>';
                bgIcon = 'background-color: #f0f9ff; color: #0284c7; border: 1px solid #e0f2fe;';
            } else if (type === 'error') {
                icon = '<i class="fa-solid fa-circle-xmark text-sm"></i>';
                bgIcon = 'background-color: #fff1f2; color: #f43f5e; border: 1px solid #ffe4e6;';
            }
            
            toast.innerHTML = `
                <div style="${bgIcon} padding: 6px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; width: 26px; height: 26px; box-sizing: border-box;">
                    ${icon}
                </div>
                <div style="flex: 1; min-width: 0; padding-top: 2px;">
                    <p style="margin: 0; font-size: 0.8125rem; font-weight: 700; color: #1e293b; line-height: 1.35; word-break: break-word;">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" style="padding: 2px; color: #94a3b8; border: none; background: transparent; cursor: pointer; border-radius: 4px; display: flex; align-items: center; justify-content: center; outline: none; margin-left: 4px;" onmouseover="this.style.color='#475569'" onmouseout="this.style.color='#94a3b8'">
                    <i class="fa-solid fa-xmark text-xs" style="width: 12px; height: 12px; display: block;"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Trigger animation in next frame
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            // Auto remove after 3.5s
            setTimeout(() => {
                toast.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3500);
        };
    </script>

    @yield('scripts')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('searchSuggestions', () => ({
                query: '',
                suggestions: [],
                isOpen: false,
                loading: false,
                timeout: null,

                init() {
                    this.$watch('query', (value) => {
                        if (this.timeout) clearTimeout(this.timeout);

                        if (value.trim().length < 2) {
                            this.suggestions = [];
                            this.isOpen = false;
                            return;
                        }

                        this.loading = true;
                        this.isOpen = true;

                        this.timeout = setTimeout(() => {
                            fetch(`/cars/suggest?search=${encodeURIComponent(value)}`)
                                .then(res => res.json())
                                .then(data => {
                                    this.suggestions = data;
                                    this.loading = false;
                                })
                                .catch(() => {
                                    this.loading = false;
                                });
                        }, 300);
                    });
                },

                close() {
                    this.isOpen = false;
                }
            }));
        });
    </script>
</body>
</html>
