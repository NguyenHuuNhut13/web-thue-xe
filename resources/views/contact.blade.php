@extends('layouts.app')

@section('title', 'Liên hệ với NKS - Thuê xe du lịch')

@section('styles')
    <!-- Maplibre GL JS Styles -->
    <link href="https://unpkg.com/maplibre-gl@4.0.0/dist/maplibre-gl.css" rel="stylesheet" />
    <style>
        #contact-map {
            width: 100%;
            height: 350px;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
    </style>
@endsection

@section('content')
    <!-- Banner -->
    <div class="bg-slate-900 py-16 border-b border-slate-800 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-brand font-bold text-xs uppercase tracking-widest">Kết nối với chúng tôi</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Liên Hệ Với NKS</h1>
            <p class="text-sm text-slate-400 mt-2.5 max-w-lg mx-auto">
                Chúng tôi luôn sẵn sàng lắng nghe và trả lời mọi thắc mắc của bạn về thủ tục thuê xe hoặc đăng tin xe.
            </p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        <!-- Status Messages -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl flex items-center space-x-3 text-sm mb-10 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- 1. Left Col (lg:col-span-5): Contact Info -->
            <div class="lg:col-span-5 space-y-8">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Thông tin liên hệ</h2>
                    <p class="text-slate-500 text-sm mt-1.5 leading-relaxed">
                        Bạn có thể liên lạc với NKS qua các phương thức trực tiếp dưới đây hoặc để lại tin nhắn trên biểu mẫu.
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- Address -->
                    <div class="flex items-start space-x-4 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-location-dot text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Địa chỉ trụ sở</h4>
                            <p class="text-slate-500 text-xs mt-1">222 Le Van Sy St., Ward Nhiêu Lộc, HCMC</p>
                        </div>
                    </div>

                    <!-- Phone call -->
                    <div class="flex items-start space-x-4 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-phone text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Điện thoại / Zalo</h4>
                            <p class="text-slate-500 text-xs mt-1">Hotline: <a href="tel:+84932030958" class="hover:text-brand transition-colors">(+84) 932.030.958</a></p>
                            <p class="text-slate-500 text-[10px] mt-0.5">Nhắn Zalo: <a href="https://zalo.me/0932030958" target="_blank" class="hover:text-brand text-brand font-semibold transition-colors">0932.030.958</a></p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start space-x-4 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-envelope text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Hòm thư điện tử</h4>
                            <p class="text-slate-500 text-xs mt-1">Email: <a href="mailto:system@nks.vn" class="hover:text-brand transition-colors">system@nks.vn</a></p>
                            <p class="text-slate-500 text-[10px] mt-0.5">Website: <a href="http://www.nks.com.vn" target="_blank" class="hover:text-brand transition-colors">www.nks.com.vn</a></p>
                        </div>
                    </div>
                </div>

                <!-- HQ Map -->
                <div>
                    <h3 class="font-bold text-slate-900 text-sm mb-3">Vị trí của NKS trên bản đồ</h3>
                    <div id="contact-map"></div>
                </div>
            </div>

            <!-- 2. Right Col (lg:col-span-7): Form -->
            <div class="lg:col-span-7 bg-white p-8 rounded-3xl border border-slate-100 shadow-xl">
                <div class="mb-6">
                    <h2 class="text-2xl font-extrabold text-slate-900">Gửi lời nhắn cho NKS</h2>
                    <p class="text-slate-500 text-xs mt-1">Ý kiến đóng góp hoặc thắc mắc của bạn sẽ được phản hồi sớm nhất.</p>
                </div>

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Họ và tên *</label>
                            <input type="text" name="name" required placeholder="Ví dụ: Nguyễn Văn A"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold">
                        </div>

                        <!-- Email -->
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Địa chỉ Email *</label>
                            <input type="email" name="email" required placeholder="Ví dụ: name@example.com"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Phone -->
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Số điện thoại</label>
                            <input type="tel" name="phone" placeholder="Ví dụ: 0932030958"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold">
                        </div>

                        <!-- Subject -->
                        <div class="flex flex-col">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tiêu đề liên hệ</label>
                            <input type="text" name="subject" placeholder="Ví dụ: Hợp tác đăng xe cho thuê"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold">
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="flex flex-col">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nội dung liên hệ *</label>
                        <textarea name="message" required rows="5" placeholder="Nhập nội dung tin nhắn chi tiết tại đây..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-brand text-xs font-semibold"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-brand hover:bg-brand-hover text-white text-sm font-semibold py-3.5 rounded-xl shadow-lg shadow-brand/20 transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Gửi tin nhắn đi</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <!-- Maplibre GL JS Library -->
    <script src="https://unpkg.com/maplibre-gl@4.0.0/dist/maplibre-gl.js"></script>
    <script>
        // Coords for 222 Lê Văn Sỹ, HCMC
        const lng = 106.6713;
        const lat = 10.7904;

        // Initialize Map
        const map = new maplibregl.Map({
            container: 'contact-map',
            style: 'https://basemaps.cartocdn.com/gl/voyager-gl-style/style.json', // Voyager Style
            center: [lng, lat],
            zoom: 15
        });

        // Add Zoom Controls
        map.addControl(new maplibregl.NavigationControl());

        // Add Marker
        map.on('load', () => {
            // Create a custom element for marker
            const el = document.createElement('div');
            el.className = 'marker';
            el.innerHTML = `
                <div style="background-color: #0077bb; color: white; width: 36px; height: 36px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.2); display: flex; items-center; justify-content: center; align-items: center;">
                    <i class="fa-solid fa-location-dot" style="font-size: 16px;"></i>
                </div>
            `;

            // Create Popup
            const popupHTML = `
                <div style="padding: 10px; font-family: Outfit, sans-serif;">
                    <h4 style="font-weight: 800; font-size: 13px; color: #0077bb; margin-bottom: 2px;">Trụ sở NKS</h4>
                    <p style="font-size: 11px; color: #64748b; line-height: 1.3; margin: 0;">222 Lê Văn Sỹ, Phường 14, Quận 3, TP.HCM</p>
                </div>
            `;
            const popup = new maplibregl.Popup({ offset: 15 })
                .setHTML(popupHTML);

            // Add marker to map
            new maplibregl.Marker({ element: el })
                .setLngLat([lng, lat])
                .setPopup(popup)
                .addTo(map)
                .togglePopup(); // Open popup by default
        });
    </script>
@endsection
