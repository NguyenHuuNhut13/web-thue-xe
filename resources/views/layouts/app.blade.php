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
                    <a href="{{ route('faq') }}" class="text-slate-600 hover:text-brand transition-colors py-2 {{ request()->routeIs('faq') ? 'text-brand font-semibold border-b-2 border-brand' : '' }}">
                        Hỏi đáp bảo dưỡng
                    </a>
                    <a href="{{ route('contact') }}" class="text-slate-600 hover:text-brand transition-colors py-2 {{ request()->routeIs('contact') ? 'text-brand font-semibold border-b-2 border-brand' : '' }}">
                        Liên hệ
                    </a>
                </nav>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <!-- Save user info to localStorage for remember login feature -->
                        <script>
                            (function() {
                                const email = "{{ auth()->user()->email }}";
                                const name = "{{ auth()->user()->name }}";
                                const avatar = "{{ auth()->user()->avatar_url }}";
                                
                                localStorage.setItem("nks_last_user_email", email);
                                localStorage.setItem("nks_last_user_name", name);
                                localStorage.setItem("nks_last_user_avatar", avatar);
                                
                                let accounts = [];
                                try {
                                    accounts = JSON.parse(localStorage.getItem("nks_saved_accounts")) || [];
                                } catch(e) { accounts = []; }
                                if (!Array.isArray(accounts)) accounts = [];
                                
                                let existIndex = accounts.findIndex(acc => acc.email === email);
                                if (existIndex > -1) {
                                    accounts[existIndex].name = name;
                                    accounts[existIndex].avatar = avatar;
                                } else {
                                    accounts.push({
                                        email: email,
                                        name: name,
                                        avatar: avatar,
                                        password: '',
                                        remember: false
                                    });
                                }
                                localStorage.setItem("nks_saved_accounts", JSON.stringify(accounts));
                            })();
                        </script>
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
            <a href="{{ route('faq') }}" class="block px-4 py-2.5 rounded-xl text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-all">
                Hỏi đáp bảo dưỡng
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

            // AI Chatbot Alpine.js controller
            Alpine.data('nksChatbot', () => ({
                isOpen: false,
                hasOpened: false,
                loading: false,
                inputMessage: '',
                messages: [],
                
                init() {
                    if (localStorage.getItem('nks_chatbot_opened')) {
                        this.hasOpened = true;
                    }
                },
                
                toggleChat() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen) {
                        this.hasOpened = true;
                        localStorage.setItem('nks_chatbot_opened', 'true');
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    }
                },
                
                sendSuggested(text) {
                    this.inputMessage = text;
                    this.sendMessage();
                },
                
                sendMessage() {
                    if (this.inputMessage.trim() === '' || this.loading) return;
                    
                    const userText = this.inputMessage;
                    const msgId = Date.now();
                    
                    this.messages.push({
                        id: msgId,
                        sender: 'user',
                        text: userText
                    });
                    
                    this.inputMessage = '';
                    this.loading = true;
                    
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                    
                    fetch('{{ route('chatbot.message') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: userText })
                    })
                    .then(res => {
                        if (res.status === 419 || res.status === 401) {
                            localStorage.removeItem("nks_last_user_email");
                            localStorage.removeItem("nks_last_user_name");
                            localStorage.removeItem("nks_last_user_avatar");
                            alert('Phiên làm việc của bạn đã hết hạn. Hệ thống sẽ tự động tải lại trang.');
                            window.location.reload();
                            return;
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (!data) return;
                        if (data.success) {
                            this.messages.push({
                                id: Date.now() + 1,
                                sender: 'model',
                                text: data.response
                            });
                        } else {
                            this.messages.push({
                                id: Date.now() + 1,
                                sender: 'model',
                                text: '🤖 Xin lỗi, tôi đang gặp trục trặc khi tải câu trả lời từ máy chủ AI.'
                            });
                        }
                    })
                    .catch(err => {
                        this.messages.push({
                            id: Date.now() + 1,
                            sender: 'model',
                            text: '🤖 Không thể kết nối với máy chủ AI. Vui lòng kiểm tra lại kết nối mạng.'
                        });
                    })
                    .finally(() => {
                        this.loading = false;
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    });
                },
                
                clearHistory() {
                    if (!confirm('Bạn có muốn làm mới và xóa toàn bộ cuộc hội thoại hiện tại không?')) return;
                    
                    fetch('{{ route('chatbot.clear') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.messages = [];
                    });
                },
                
                scrollToBottom() {
                    const container = this.$refs.messages;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }
            }));
        });
    </script>

    <!-- Floating AI Chatbot Widget -->
    <div x-data="nksChatbot" class="fixed bottom-6 right-6 z-50">
        <!-- Chat Bubble Button -->
        <button @click="toggleChat()" 
                class="w-14 h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full flex items-center justify-center shadow-lg transition-transform duration-200 hover:scale-110 focus:outline-none relative">
            <i class="fa-solid fa-comments text-2xl" x-show="!isOpen"></i>
            <i class="fa-solid fa-xmark text-2xl" x-show="isOpen" style="display: none;"></i>
            <!-- Notification dot -->
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 border-2 border-white rounded-full flex items-center justify-center text-[9px] font-bold text-white" x-show="!hasOpened">1</span>
        </button>

        <!-- Chat Panel -->
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             style="display: none;"
             class="absolute bottom-18 right-0 w-85 sm:w-96 h-[500px] bg-white border border-slate-100 rounded-3xl shadow-2xl flex flex-col overflow-hidden max-w-[calc(100vw-2rem)]">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-violet-600 p-4 text-white flex items-center justify-between shadow-md">
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-2 rounded-xl">
                        <i class="fa-solid fa-robot text-lg text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Trợ lý ảo NKS AI</h4>
                        <p class="text-[10px] text-indigo-200 flex items-center gap-1"><span class="w-1.5 h-1.5 bg-emerald-400 rounded-full inline-block animate-pulse"></span> Sẵn sàng hỗ trợ bạn</p>
                    </div>
                </div>
                <button @click="clearHistory()" class="text-indigo-200 hover:text-white text-xs p-1" title="Làm mới hội thoại">
                    <i class="fa-solid fa-rotate-left"></i>
                </button>
            </div>

            <!-- Message Area -->
            <div class="flex-grow p-4 overflow-y-auto space-y-4 bg-slate-50/50" id="chatbot-messages-container" x-ref="messages">
                <!-- Welcome message -->
                <div class="flex items-start gap-2.5 max-w-[85%]">
                    <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-robot text-indigo-600 text-xs"></i>
                    </div>
                    <div class="bg-white border border-slate-100 rounded-2xl rounded-tl-none p-3 shadow-sm text-xs sm:text-sm text-slate-700 leading-relaxed">
                        Xin chào! Tôi là Trợ lý ảo AI của **NKS Car Rental** 🚗. Tôi có thể giúp bạn tìm thông tin xe, báo giá các hãng xe, tư vấn giá xe thị trường hoặc kinh nghiệm bảo dưỡng xe. Bạn cần tôi hỗ trợ gì hôm nay?
                    </div>
                </div>

                <!-- Loop user & model messages -->
                <template x-for="msg in messages" :key="msg.id">
                    <div :class="msg.sender === 'user' ? 'justify-end' : 'justify-start'" class="flex items-start gap-2.5">
                        <template x-if="msg.sender !== 'user'">
                            <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-robot text-indigo-600 text-xs"></i>
                            </div>
                        </template>
                        <div :class="msg.sender === 'user' ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white border border-slate-100 text-slate-700 rounded-tl-none shadow-sm'" 
                             class="max-w-[80%] rounded-2xl p-3 text-xs sm:text-sm leading-relaxed"
                             x-html="msg.text">
                        </div>
                    </div>
                </template>

                <!-- Loading spinner -->
                <div x-show="loading" class="flex items-start gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-robot text-indigo-600 text-xs"></i>
                    </div>
                    <div class="bg-white border border-slate-100 rounded-2xl rounded-tl-none p-3 shadow-sm flex items-center space-x-1.5">
                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0.2s;"></span>
                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0.4s;"></span>
                    </div>
                </div>
            </div>

            <!-- Suggested Prompts -->
            <div class="px-4 py-2 border-t border-slate-100 bg-white flex gap-2 overflow-x-auto whitespace-nowrap scrollbar-none select-none cursor-grab active:cursor-grabbing" 
                 x-show="messages.length === 0"
                 x-data="{ isDown: false, startX: 0, scrollLeft: 0 }"
                 @mousedown="isDown = true; startX = $event.pageX - $el.offsetLeft; scrollLeft = $el.scrollLeft"
                 @mouseleave="isDown = false"
                 @mouseup="isDown = false"
                 @mousemove="if(!isDown) return; $event.preventDefault(); const x = $event.pageX - $el.offsetLeft; const walk = (x - startX) * 1.5; $el.scrollLeft = scrollLeft - walk">
                <button @click="sendSuggested('Có xe 7 chỗ tự lái nào không?')" class="flex-shrink-0 text-xs bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 px-3 py-1.5 rounded-full transition-colors font-medium border border-slate-100">🚙 Thuê xe 7 chỗ</button>
                <button @click="sendSuggested('Giá xe VinFast Fadil lăn bánh khoảng bao nhiêu?')" class="flex-shrink-0 text-xs bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 px-3 py-1.5 rounded-full transition-colors font-medium border border-slate-100">⚡ Giá xe VinFast</button>
                <button @click="sendSuggested('Bảo dưỡng xe ô tô cần làm gì định kỳ?')" class="flex-shrink-0 text-xs bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 px-3 py-1.5 rounded-full transition-colors font-medium border border-slate-100">🔧 Bảo dưỡng xe</button>
            </div>

            <!-- Input area -->
            <form @submit.prevent="sendMessage()" class="p-3 border-t border-slate-100 bg-white flex items-center gap-2">
                <input type="text" 
                       x-model="inputMessage" 
                       placeholder="Nhập câu hỏi của bạn..." 
                       class="flex-grow px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500 text-xs sm:text-sm text-slate-800"
                       :disabled="loading">
                <button type="submit" 
                        :disabled="loading || inputMessage.trim() === ''"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white p-2.5 rounded-xl disabled:bg-slate-200 disabled:text-slate-400 transition-colors shadow-md shadow-indigo-100">
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                </button>
            </form>
        </div>
    </div>
    <script>
        // Check session status when page gets focus or becomes visible
        (function() {
            @auth
                const wasAuthenticated = true;
            @else
                const wasAuthenticated = false;
            @endauth

            function checkSessionStatus() {
                if (!wasAuthenticated) return;
                
                fetch('/session-status')
                    .then(res => {
                        if (res.status === 401 || res.status === 419) {
                            handleSessionExpiry();
                            return;
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data && data.authenticated === false) {
                            handleSessionExpiry();
                        }
                    })
                    .catch(() => {
                        // Ignore network failures for background status check
                    });
            }

            function handleSessionExpiry() {
                localStorage.removeItem("nks_last_user_email");
                localStorage.removeItem("nks_last_user_name");
                localStorage.removeItem("nks_last_user_avatar");
                
                alert('Phiên làm việc của bạn đã hết hạn. Hệ thống sẽ tự động tải lại trang.');
                window.location.reload();
            }

            // Check when user returns to the tab
            window.addEventListener('focus', checkSessionStatus);
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    checkSessionStatus();
                }
            });
        })();
    </script>
</body>
</html>
