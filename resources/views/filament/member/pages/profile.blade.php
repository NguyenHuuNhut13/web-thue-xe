<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
        <!-- Cột bên trái: Thẻ tóm tắt & Tabs -->
        <div class="md:col-span-1 space-y-6">
            <!-- Thẻ tóm tắt -->
            <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm flex flex-col items-center text-center">
                <!-- Avatar -->
                <div class="relative group">
                    <img class="w-24 h-24 rounded-full object-cover border-4 border-indigo-50 dark:border-indigo-950 shadow-md transition-all duration-300 group-hover:scale-105" 
                         src="{{ auth()->user()->avatar_url }}" 
                         alt="{{ auth()->user()->name }}">
                    <span class="absolute bottom-0 right-0 block h-4 w-4 rounded-full bg-green-400 ring-2 ring-white dark:ring-gray-900"></span>
                </div>
                
                <h3 class="mt-4 font-bold text-lg text-gray-800 dark:text-gray-200">
                    {{ auth()->user()->name }}
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    {{ auth()->user()->email }}
                </p>
                
                <!-- Badge Role/Status -->
                <div class="mt-3 flex gap-2">
                    <span class="inline-flex items-center gap-1 rounded-md bg-green-50 px-2 py-1 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                        {{ auth()->user()->status === 'active' ? 'Đang hoạt động' : 'Tạm khóa' }}
                    </span>
                    @if(auth()->user()->role)
                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/20">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Điều hướng các Tab (Sidebar) -->
            <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
                <nav class="flex flex-col p-2 space-y-1">
                    <!-- Tab Thông tin cá nhân -->
                    <button wire:click="$set('activeTab', 'personal')" 
                            class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 cursor-pointer select-none text-left w-full {{ $activeTab === 'personal' ? 'bg-indigo-50 text-indigo-650 dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-100' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Thông tin cá nhân</span>
                    </button>

                    <!-- Tab Căn cước công dân -->
                    <button wire:click="$set('activeTab', 'cccd')" 
                            class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 cursor-pointer select-none text-left w-full {{ $activeTab === 'cccd' ? 'bg-indigo-50 text-indigo-650 dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-100' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                        </svg>
                        <span>Căn cước công dân</span>
                    </button>

                    <!-- Tab Ảnh đại diện -->
                    <button wire:click="$set('activeTab', 'avatar')" 
                            class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 cursor-pointer select-none text-left w-full {{ $activeTab === 'avatar' ? 'bg-indigo-50 text-indigo-650 dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-100' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <span>Ảnh đại diện</span>
                    </button>

                    <!-- Tab Đổi mật khẩu -->
                    <button wire:click="$set('activeTab', 'password')" 
                            class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 cursor-pointer select-none text-left w-full {{ $activeTab === 'password' ? 'bg-indigo-50 text-indigo-650 dark:bg-indigo-500/10 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-100' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                        </svg>
                        <span>Đổi mật khẩu</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Cột bên phải: Nội dung chi tiết -->
        <div class="md:col-span-3">
            <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                <!-- Nội dung theo từng tab -->
                @if($activeTab === 'personal')
                    <div>
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
                            <div>
                                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">Thông tin cá nhân</h2>
                                <p class="text-xs text-gray-400 mt-1">Thông tin cơ bản về tài khoản của bạn trên hệ thống</p>
                            </div>
                            @if(!$isEditing)
                                <x-filament::button type="button" wire:click="enableEditing" size="sm" icon="heroicon-o-pencil-square">
                                    Chỉnh sửa
                                </x-filament::button>
                            @endif
                        </div>

                        @if(!$isEditing)
                            <!-- Chế độ Chỉ xem (Read-only) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <div class="space-y-1">
                                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Họ và tên</span>
                                    <div class="flex items-center gap-3 text-sm font-semibold text-gray-700 dark:text-gray-300 py-1.5">
                                        <span class="bg-gray-50 dark:bg-gray-800 p-2.5 rounded-xl text-gray-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </span>
                                        <span>{{ auth()->user()->name }}</span>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Địa chỉ Email</span>
                                    <div class="flex items-center gap-3 text-sm font-semibold text-gray-700 dark:text-gray-300 py-1.5">
                                        <span class="bg-gray-50 dark:bg-gray-800 p-2.5 rounded-xl text-gray-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </span>
                                        <span>{{ auth()->user()->email }}</span>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Số điện thoại</span>
                                    <div class="flex items-center gap-3 text-sm font-semibold text-gray-700 dark:text-gray-300 py-1.5">
                                        <span class="bg-gray-50 dark:bg-gray-800 p-2.5 rounded-xl text-gray-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        </span>
                                        <span>{{ auth()->user()->phone ?: 'Chưa cập nhật' }}</span>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider block">Số Zalo / Link Zalo</span>
                                    <div class="flex items-center gap-3 text-sm font-semibold text-gray-700 dark:text-gray-300 py-1.5">
                                        <span class="bg-gray-50 dark:bg-gray-800 p-2.5 rounded-xl text-gray-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        </span>
                                        <span>{{ auth()->user()->zalo ?: 'Chưa cập nhật' }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Chế độ Chỉnh sửa (Form) -->
                            <form wire:submit="saveProfile" class="space-y-6">
                                {{ $this->profileForm }}
                                
                                <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-150 dark:border-gray-800">
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
                        <div class="border-b border-gray-150 dark:border-gray-800 pb-4 mb-6">
                            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">Căn cước công dân (CCCD)</h2>
                            <p class="text-xs text-gray-400 mt-1">Thông tin định danh người dùng trên hệ thống</p>
                        </div>

                        <!-- Card hiển thị dạng Thẻ CCCD Mockup -->
                        <div class="mb-8 max-w-md mx-auto bg-gradient-to-br from-indigo-500 to-purple-600 dark:from-indigo-600 dark:to-purple-700 text-white p-6 rounded-2xl shadow-md relative overflow-hidden">
                            <div class="absolute right-0 top-0 opacity-10 transform translate-x-8 -translate-y-8">
                                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                            </div>
                            
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <span class="text-xs uppercase tracking-wider opacity-75">CĂN CƯỚC CÔNG DÂN</span>
                                    <h4 class="text-sm font-bold opacity-90 mt-0.5">CITIZEN IDENTITY CARD</h4>
                                </div>
                                <span class="bg-white/20 backdrop-blur-sm px-2.5 py-1 rounded-md text-xs font-semibold border border-white/10">
                                    NKS
                                </span>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <span class="text-xs opacity-75 block">Số / No.:</span>
                                    <span class="text-xl font-bold tracking-widest">{{ auth()->user()->cccd ?: 'CHƯA CẬP NHẬT' }}</span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-xs opacity-75 block">Họ và tên / Full name:</span>
                                        <span class="text-sm font-semibold">{{ auth()->user()->name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs opacity-75 block">Trạng thái / Status:</span>
                                        <span class="inline-flex items-center gap-1 mt-0.5 rounded-full bg-white/20 px-2 py-0.5 text-xs font-medium border border-white/10">
                                            {{ auth()->user()->cccd ? 'Đã liên kết' : 'Chưa liên kết' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form wire:submit="saveCccd" class="space-y-6">
                            {{ $this->cccdForm }}
                            
                            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-150 dark:border-gray-800">
                                <x-filament::button type="submit">
                                    Cập nhật CCCD
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                @endif

                @if($activeTab === 'avatar')
                    <div>
                        <div class="border-b border-gray-150 dark:border-gray-800 pb-4 mb-6">
                            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">Ảnh đại diện</h2>
                            <p class="text-xs text-gray-400 mt-1">Cập nhật ảnh đại diện hiển thị trên hệ thống</p>
                        </div>

                        <div class="flex flex-col items-center justify-center py-6 mb-6">
                            <img class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 dark:border-gray-800 shadow-md" 
                                 src="{{ auth()->user()->avatar_url }}" 
                                 alt="{{ auth()->user()->name }}">
                            <span class="text-xs text-gray-400 mt-2">Ảnh hiện tại của bạn</span>
                        </div>

                        <form wire:submit="saveAvatar" class="space-y-6">
                            {{ $this->avatarForm }}
                            
                            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-150 dark:border-gray-800">
                                <x-filament::button type="submit">
                                    Cập nhật ảnh đại diện
                                </x-filament::button>
                            </div>
                        </form>
                    </div>
                @endif

                @if($activeTab === 'password')
                    <div>
                        <div class="border-b border-gray-150 dark:border-gray-800 pb-4 mb-6">
                            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">Đổi mật khẩu</h2>
                            <p class="text-xs text-gray-400 mt-1">Thay đổi mật khẩu tài khoản thường xuyên để bảo mật</p>
                        </div>

                        <form wire:submit="savePassword" class="space-y-6">
                            {{ $this->passwordForm }}
                            
                            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-150 dark:border-gray-800">
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
