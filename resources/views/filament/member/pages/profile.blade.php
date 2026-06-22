<x-filament-panels::page>
    <style>
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr 3fr;
            }
        }
        .profile-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        }
        .dark .profile-card {
            background-color: #111827;
            border-color: #1f2937;
        }
        .sidebar-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .dark .sidebar-card {
            background-color: #111827;
            border-color: #1f2937;
        }
        .nav-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .dark .nav-card {
            background-color: #111827;
            border-color: #1f2937;
        }
        .nav-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.75rem;
            transition: all 0.2s;
            cursor: pointer;
            text-align: left;
            border: none;
            background: transparent;
        }
        .nav-btn-active {
            background-color: #eef2ff !important;
            color: #4f46e5 !important;
        }
        .dark .nav-btn-active {
            background-color: rgba(99, 102, 241, 0.1) !important;
            color: #818cf8 !important;
        }
        .nav-btn-inactive {
            color: #4b5563;
        }
        .nav-btn-inactive:hover {
            background-color: #f9fafb;
            color: #111827;
        }
        .dark .nav-btn-inactive {
            color: #9ca3af;
        }
        .dark .nav-btn-inactive:hover {
            background-color: rgba(31, 41, 55, 0.5);
            color: #f3f4f6;
        }
        .avatar-container {
            position: relative;
            display: inline-block;
        }
        .avatar-img {
            width: 6rem;
            height: 6rem;
            border-radius: 9999px;
            object-fit: cover;
            border: 4px solid #eef2ff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        .avatar-img:hover {
            transform: scale(1.05);
        }
        .dark .avatar-img {
            border-color: #312e81;
        }
        .status-dot {
            position: absolute;
            bottom: 0;
            right: 0;
            display: block;
            height: 1rem;
            width: 1rem;
            border-radius: 9999px;
            background-color: #4ade80;
            border: 2px solid #ffffff;
        }
        .dark .status-dot {
            border-color: #111827;
        }
        .badge-green {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            border-radius: 0.375rem;
            background-color: #f0fdf4;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #15803d;
            border: 1px solid rgba(22, 163, 74, 0.2);
        }
        .dark .badge-green {
            background-color: rgba(34, 197, 94, 0.1);
            color: #4ade80;
            border-color: rgba(34, 197, 94, 0.2);
        }
        .badge-blue {
            display: inline-flex;
            align-items: center;
            border-radius: 0.375rem;
            background-color: #eff6ff;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #1d78d6;
            border: 1px solid rgba(29, 120, 214, 0.2);
        }
        .dark .badge-blue {
            background-color: rgba(96, 165, 250, 0.1);
            color: #60a5fa;
            border-color: rgba(96, 165, 250, 0.2);
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-top: 1rem;
        }
        @media (min-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        .info-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: block;
            margin-bottom: 0.25rem;
        }
        .dark .info-label {
            color: #6b7280;
        }
        .info-value-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
        }
        .dark .info-value-container {
            color: #d1d5db;
        }
        .icon-wrapper {
            background-color: #f9fafb;
            padding: 0.625rem;
            border-radius: 0.75rem;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dark .icon-wrapper {
            background-color: #1f2937;
        }
        .icon-svg {
            width: 1.25rem !important;
            height: 1.25rem !important;
            flex-shrink: 0;
        }
        .icon-svg-sm {
            width: 1rem !important;
            height: 1rem !important;
            flex-shrink: 0;
        }
        .cccd-card {
            margin-bottom: 2rem;
            max-width: 28rem;
            margin-left: auto;
            margin-right: auto;
            background-image: linear-gradient(to bottom right, #6366f1, #9333ea);
            color: #ffffff;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            border: none;
        }
        .dark .cccd-card {
            background-image: linear-gradient(to bottom right, #4f70e9, #7e22ce);
        }
        .cccd-logo {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            padding: 0.25rem 0.625rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .cccd-number {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.1em;
        }
        .avatar-edit-preview {
            width: 8rem;
            height: 8rem;
            border-radius: 9999px;
            object-fit: cover;
            border: 4px solid #f3f4f6;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .dark .avatar-edit-preview {
            border-color: #1f2937;
        }
        .flex-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .border-b-thin {
            border-bottom: 1px solid #f3f4f6;
        }
        .dark .border-b-thin {
            border-bottom: 1px solid #1f2937;
        }
        .margin-b-med {
            margin-bottom: 1.5rem;
        }
        .padding-b-sm {
            padding-bottom: 1rem;
        }
    </style>

    <div class="profile-grid">
        <!-- Cột bên trái: Thẻ tóm tắt & Tabs -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Thẻ tóm tắt -->
            <div class="sidebar-card">
                <!-- Avatar -->
                <div class="avatar-container">
                    <img class="avatar-img" 
                         src="{{ auth()->user()->avatar_url }}" 
                         alt="{{ auth()->user()->name }}">
                    <span class="status-dot"></span>
                </div>
                
                <h3 style="margin-top: 1rem; font-weight: 700; font-size: 1.125rem; color: #1f2937;" class="dark:text-gray-200">
                    {{ auth()->user()->name }}
                </h3>
                <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;" class="dark:text-gray-400">
                    {{ auth()->user()->email }}
                </p>
                
                <!-- Badge Role/Status -->
                <div style="margin-top: 0.75rem; display: flex; gap: 0.5rem; justify-content: center;">
                    <span class="badge-green">
                        {{ auth()->user()->status === 'active' ? 'Đang hoạt động' : 'Tạm khóa' }}
                    </span>
                    @if(auth()->user()->role)
                        <span class="badge-blue">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Điều hướng các Tab (Sidebar) -->
            <div class="nav-card">
                <nav style="display: flex; flex-direction: column; padding: 0.5rem; gap: 0.25rem;">
                    <!-- Tab Thông tin cá nhân -->
                    <button wire:click="$set('activeTab', 'personal')" 
                            class="nav-btn {{ $activeTab === 'personal' ? 'nav-btn-active' : 'nav-btn-inactive' }}">
                        <svg class="icon-svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Thông tin cá nhân</span>
                    </button>

                    <!-- Tab Căn cước công dân -->
                    <button wire:click="$set('activeTab', 'cccd')" 
                            class="nav-btn {{ $activeTab === 'cccd' ? 'nav-btn-active' : 'nav-btn-inactive' }}">
                        <svg class="icon-svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                        </svg>
                        <span>Căn cước công dân</span>
                    </button>

                    <!-- Tab Ảnh đại diện -->
                    <button wire:click="$set('activeTab', 'avatar')" 
                            class="nav-btn {{ $activeTab === 'avatar' ? 'nav-btn-active' : 'nav-btn-inactive' }}">
                        <svg class="icon-svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <span>Ảnh đại diện</span>
                    </button>

                    <!-- Tab Đổi mật khẩu -->
                    <button wire:click="$set('activeTab', 'password')" 
                            class="nav-btn {{ $activeTab === 'password' ? 'nav-btn-active' : 'nav-btn-inactive' }}">
                        <svg class="icon-svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                        </svg>
                        <span>Đổi mật khẩu</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Cột bên phải: Nội dung chi tiết -->
        <div>
            <div class="profile-card">
                <!-- Nội dung theo từng tab -->
                @if($activeTab === 'personal')
                    <div>
                        <div class="flex-between border-b-thin padding-b-sm margin-b-med">
                            <div>
                                <h2 style="font-size: 1.125rem; font-weight: 700; color: #1f2937;" class="dark:text-gray-200">Thông tin cá nhân</h2>
                                <p style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">Thông tin cơ bản về tài khoản của bạn trên hệ thống</p>
                            </div>
                            @if(!$isEditing)
                                <x-filament::button type="button" wire:click="enableEditing" size="sm" icon="heroicon-o-pencil-square">
                                    Chỉnh sửa
                                </x-filament::button>
                            @endif
                        </div>

                        @if(!$isEditing)
                            <!-- Chế độ Chỉ xem (Read-only) -->
                            <div class="info-grid">
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <span class="info-label">Họ và tên</span>
                                    <div class="info-value-container">
                                        <div class="icon-wrapper">
                                            <svg class="icon-svg-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </div>
                                        <span>{{ auth()->user()->name }}</span>
                                    </div>
                                </div>

                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <span class="info-label">Địa chỉ Email</span>
                                    <div class="info-value-container">
                                        <div class="icon-wrapper">
                                            <svg class="icon-svg-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </div>
                                        <span>{{ auth()->user()->email }}</span>
                                    </div>
                                </div>

                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <span class="info-label">Số điện thoại</span>
                                    <div class="info-value-container">
                                        <div class="icon-wrapper">
                                            <svg class="icon-svg-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        </div>
                                        <span>{{ auth()->user()->phone ?: 'Chưa cập nhật' }}</span>
                                    </div>
                                </div>

                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <span class="info-label">Số Zalo / Link Zalo</span>
                                    <div class="info-value-container">
                                        <div class="icon-wrapper">
                                            <svg class="icon-svg-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        </div>
                                        <span>{{ auth()->user()->zalo ?: 'Chưa cập nhật' }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Chế độ Chỉnh sửa (Form) -->
                            <form wire:submit="saveProfile" style="display: flex; flex-direction: column; gap: 1.5rem;">
                                {{ $this->profileForm }}
                                
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1rem;" class="border-t-thin">
                                    <x-filament::button type="submit">
                                        Lưu thay đổi
                                    </x-filament::button>
                                    
                                    <x-filament::button type="button" color="gray" wire:click="cancelEdit">
                                        Hủy bỏ
                                    </x-filament::button>
                                </div>
                            </form>
                        @endif
                    </div>
                @endif

                @if($activeTab === 'cccd')
                    <div>
                        <div class="border-b-thin padding-b-sm margin-b-med">
                            <h2 style="font-size: 1.125rem; font-weight: 700; color: #1f2937;" class="dark:text-gray-200">Căn cước công dân (CCCD)</h2>
                            <p style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">Thông tin định danh người dùng trên hệ thống</p>
                        </div>

                        <!-- Card hiển thị dạng Thẻ CCCD Mockup -->
                        <div class="cccd-card">
                            <div style="position: absolute; right: 0; top: 0; opacity: 0.1; transform: translate(2rem, -2rem);">
                                <svg style="width: 16rem; height: 16rem;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                            </div>
                            
                            <div class="flex-between" style="margin-bottom: 1.5rem;">
                                <div>
                                    <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.75;">CĂN CƯỚC CÔNG DÂN</span>
                                    <h4 style="font-size: 0.875rem; font-weight: 700; opacity: 0.9; margin-top: 0.125rem;">CITIZEN IDENTITY CARD</h4>
                                </div>
                                <span class="cccd-logo">
                                    NKS
                                </span>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                <div>
                                    <span style="font-size: 0.75rem; opacity: 0.75; display: block;">Số / No.:</span>
                                    <span class="cccd-number">{{ auth()->user()->cccd ?: 'CHƯA CẬP NHẬT' }}</span>
                                </div>
                                
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <span style="font-size: 0.75rem; opacity: 0.75; display: block;">Họ và tên / Full name:</span>
                                        <span style="font-size: 0.875rem; font-weight: 600;">{{ auth()->user()->name }}</span>
                                    </div>
                                    <div>
                                        <span style="font-size: 0.75rem; opacity: 0.75; display: block;">Trạng thái / Status:</span>
                                        <span style="display: inline-flex; align-items: center; border-radius: 9999px; background-color: rgba(255, 255, 255, 0.2); padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: 500; border: 1px solid rgba(255, 255, 255, 0.1);">
                                            {{ auth()->user()->cccd ? 'Đã liên kết' : 'Chưa liên kết' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form wire:submit="saveCccd" style="display: flex; flex-direction: column; gap: 1.5rem;">
                            {{ $this->cccdForm }}
                            
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1rem;" class="border-t-thin">
                                <x-filament::button type="submit">
                                    Cập nhật CCCD
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                @endif

                @if($activeTab === 'avatar')
                    <div>
                        <div class="border-b-thin padding-b-sm margin-b-med">
                            <h2 style="font-size: 1.125rem; font-weight: 700; color: #1f2937;" class="dark:text-gray-200">Ảnh đại diện</h2>
                            <p style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">Cập nhật ảnh đại diện hiển thị trên hệ thống</p>
                        </div>

                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem 0; margin-bottom: 1.5rem;">
                            <img class="avatar-edit-preview" 
                                 src="{{ auth()->user()->avatar_url }}" 
                                 alt="{{ auth()->user()->name }}">
                            <span style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.5rem;">Ảnh hiện tại của bạn</span>
                        </div>

                        <form wire:submit="saveAvatar" style="display: flex; flex-direction: column; gap: 1.5rem;">
                            {{ $this->avatarForm }}
                            
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1rem;" class="border-t-thin">
                                <x-filament::button type="submit">
                                    Cập nhật ảnh đại diện
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                @endif

                @if($activeTab === 'password')
                    <div>
                        <div class="border-b-thin padding-b-sm margin-b-med">
                            <h2 style="font-size: 1.125rem; font-weight: 700; color: #1f2937;" class="dark:text-gray-200">Đổi mật khẩu</h2>
                            <p style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">Thay đổi mật khẩu tài khoản thường xuyên để bảo mật</p>
                        </div>

                        <form wire:submit="savePassword" style="display: flex; flex-direction: column; gap: 1.5rem;">
                            {{ $this->passwordForm }}
                            
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1rem;" class="border-t-thin">
                                <x-filament::button type="submit">
                                    Cập nhật mật khẩu
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
