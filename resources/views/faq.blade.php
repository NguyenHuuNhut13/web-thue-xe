@extends('layouts.app')

@section('title', 'Hỏi Đáp Chăm Sóc & Bảo Dưỡng Xe - NKS Car Rental')
@section('meta_description', 'Khám phá 100 câu hỏi thường gặp về bảo dưỡng xe ô tô định kỳ, động cơ, phanh, lốp và hệ thống điện. Mở rộng tư vấn chuyên sâu cùng trí tuệ nhân tạo AI.')

@section('content')
<div class="bg-slate-50 min-h-screen py-12" x-data="{
    searchQuery: '',
    activeCategory: 'all',
    openFaq: null,
    aiAnswers: {},
    loadingFaq: null,
    
    getFilteredFaqs() {
        return faqs.filter(faq => {
            const matchesCategory = this.activeCategory === 'all' || faq.category === this.activeCategory;
            const matchesSearch = faq.question.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').includes(this.searchQuery.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')) || 
                                  faq.answer.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').includes(this.searchQuery.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, ''));
            return matchesCategory && matchesSearch;
        });
    },
    
    toggleFaq(faqId) {
        if (this.openFaq === faqId) {
            this.openFaq = null;
        } else {
            this.openFaq = faqId;
        }
    },
    
    loadAiAnswer(faqId) {
        if (this.aiAnswers[faqId]) return;
        this.loadingFaq = faqId;
        
        fetch('{{ route('faq.ai-answer') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: faqId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.aiAnswers[faqId] = data.ai_answer;
            } else {
                this.aiAnswers[faqId] = '🤖 Lỗi tải dữ liệu AI: ' + (data.message || 'Không thể kết nối.');
            }
        })
        .catch(err => {
            this.aiAnswers[faqId] = '🤖 Lỗi kết nối với máy chủ AI.';
        })
        .finally(() => {
            this.loadingFaq = null;
        });
    }
}">
    <!-- Header Banner -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <div class="bg-gradient-to-r from-indigo-600 to-violet-600 rounded-3xl p-8 sm:p-12 text-white shadow-xl shadow-indigo-200 relative overflow-hidden">
            <!-- Background circles decoration -->
            <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-white/10 blur-xl"></div>
            <div class="absolute -left-20 -bottom-20 w-60 h-60 rounded-full bg-white/10 blur-xl"></div>
            
            <div class="relative z-10 max-w-3xl">
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4 inline-block">Cẩm Nang Kỹ Thuật</span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-4">
                    Hỏi Đáp Chăm Sóc & Bảo Dưỡng Xe
                </h1>
                <p class="text-indigo-100 text-base sm:text-lg mb-8 leading-relaxed">
                    Hệ thống 100 câu hỏi bảo dưỡng định kỳ, chăm sóc động cơ, lốp phanh và hệ thống điện giúp xế cưng hoạt động ổn định nhất. Hãy nhấp chọn nút AI để nhận thêm góc nhìn tư vấn chuyên sâu tức thời từ trí tuệ nhân tạo.
                </p>
                
                <!-- Search Box -->
                <div class="relative max-w-xl shadow-lg rounded-2xl overflow-hidden bg-white">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    </div>
                    <input type="text" 
                           x-model="searchQuery"
                           placeholder="Nhập từ khóa tìm kiếm câu hỏi hoặc câu trả lời..."
                           class="block w-full pl-12 pr-4 py-4 border-none text-slate-800 focus:outline-none focus:ring-0 text-sm sm:text-base">
                    <button type="button" 
                            x-show="searchQuery !== ''" 
                            @click="searchQuery = ''"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Navigation Categories -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm sticky top-24">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Chủ đề bảo dưỡng</h3>
                    <nav class="space-y-1">
                        <!-- Category: ALL -->
                        <button type="button"
                                @click="activeCategory = 'all'; openFaq = null"
                                :class="activeCategory === 'all' ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                                class="w-full text-left flex items-center justify-between px-4 py-3 rounded-xl transition-all text-sm">
                            <span class="flex items-center gap-2.5">
                                <i class="fa-solid fa-list-check w-4"></i> Tất cả câu hỏi
                            </span>
                            <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full" 
                                  :class="activeCategory === 'all' && 'bg-indigo-100 text-indigo-600'"
                                  x-text="faqs.length">100</span>
                        </button>
                        
                        @foreach($categories as $key => $name)
                            <button type="button"
                                    @click="activeCategory = '{{ $key }}'; openFaq = null"
                                    :class="activeCategory === '{{ $key }}' ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                                    class="w-full text-left flex items-center justify-between px-4 py-3 rounded-xl transition-all text-sm">
                                <span class="flex items-center gap-2.5">
                                    @if($key === 'engine')
                                        <i class="fa-solid fa-gears w-4"></i>
                                    @elseif($key === 'tires')
                                        <i class="fa-solid fa-circle-notch w-4"></i>
                                    @elseif($key === 'electrical')
                                        <i class="fa-solid fa-car-battery w-4"></i>
                                    @elseif($key === 'fluids')
                                        <i class="fa-solid fa-droplet w-4"></i>
                                    @elseif($key === 'exterior')
                                        <i class="fa-solid fa-wand-magic-sparkles w-4"></i>
                                    @endif
                                    {{ $name }}
                                </span>
                                <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded-full"
                                      :class="activeCategory === '{{ $key }}' && 'bg-indigo-100 text-indigo-600'"
                                      x-text="faqs.filter(f => f.category === '{{ $key }}').length">20</span>
                            </button>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- FAQs Content Area -->
            <div class="lg:col-span-3">
                <!-- Status & Filters summary -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        Danh sách câu hỏi 
                        <span class="text-xs bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full font-semibold" x-text="getFilteredFaqs().length + ' câu hỏi được tìm thấy'"></span>
                    </h2>
                </div>

                <!-- Empty State -->
                <div x-show="getFilteredFaqs().length === 0" class="bg-white rounded-3xl p-12 border border-slate-100 text-center shadow-sm">
                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-circle-question text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Không tìm thấy kết quả</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto">
                        Chúng tôi không tìm thấy câu hỏi nào trùng khớp với từ khóa "<span class="font-semibold text-slate-700" x-text="searchQuery"></span>". Hãy thử nhập từ khóa ngắn hơn hoặc thay đổi chủ đề lọc.
                    </p>
                </div>

                <!-- Accordions List -->
                <div class="space-y-4">
                    <template x-for="faq in getFilteredFaqs()" :key="faq.id">
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden transition-all duration-200"
                             :class="openFaq === faq.id && 'ring-2 ring-indigo-500/20 border-indigo-200'">
                            <!-- Accordion Header -->
                            <button type="button" 
                                    @click="toggleFaq(faq.id)"
                                    class="w-full px-6 py-5 flex items-start justify-between text-left focus:outline-none select-none">
                                <div class="pr-4">
                                    <span class="font-semibold text-slate-800 text-sm sm:text-base block mb-1.5" x-text="faq.question"></span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500"
                                          x-text="categoriesMap[faq.category]"></span>
                                </div>
                                <span class="text-slate-400 mt-1 flex-shrink-0">
                                    <i class="fa-solid fa-chevron-down text-sm transition-transform duration-200" 
                                       :class="openFaq === faq.id && 'transform rotate-180 text-indigo-500'"></i>
                                </span>
                            </button>

                            <!-- Accordion Body -->
                            <div x-show="openFaq === faq.id" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 max-h-0"
                                 x-transition:enter-end="opacity-100 max-h-[1000px]"
                                 class="px-6 pb-6 border-t border-slate-50 pt-5">
                                
                                <!-- Standard Answer -->
                                <div class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6">
                                    <p x-text="faq.answer"></p>
                                </div>

                                <!-- AI Expand Container -->
                                <div class="border-t border-slate-100 pt-5">
                                    <!-- Toggle Button to request AI response -->
                                    <div x-show="!aiAnswers[faq.id]" class="flex justify-start">
                                        <button type="button"
                                                @click="loadAiAnswer(faq.id)"
                                                :disabled="loadingFaq === faq.id"
                                                class="inline-flex items-center gap-2 px-4 py-2 border border-violet-200 text-violet-600 hover:bg-violet-50 text-xs sm:text-sm font-semibold rounded-xl transition-all shadow-sm shadow-violet-50/50 disabled:opacity-50">
                                            <!-- Spinner when loading -->
                                            <template x-if="loadingFaq === faq.id">
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-violet-600" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </template>
                                            <template x-if="loadingFaq !== faq.id">
                                                <i class="fa-solid fa-wand-magic-sparkles text-xs sm:text-sm"></i>
                                            </template>
                                            <span x-text="loadingFaq === faq.id ? 'Đang gọi trợ lý AI...' : 'Xem câu trả lời chuyên sâu từ AI 🤖'"></span>
                                        </button>
                                    </div>

                                    <!-- AI Response box -->
                                    <div x-show="aiAnswers[faq.id]" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="bg-gradient-to-r from-violet-500/5 to-fuchsia-500/5 border border-violet-100 rounded-2xl p-5 shadow-inner">
                                        
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="bg-violet-600 text-white px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                                <i class="fa-solid fa-robot"></i> Trợ lý AI NKS
                                            </div>
                                            <span class="text-xs text-slate-400 font-medium">Phân tích kỹ thuật chuyên sâu</span>
                                        </div>

                                        <!-- Rendered Markdown HTML -->
                                        <div class="text-slate-700 text-sm sm:text-base leading-relaxed font-normal antialiased" 
                                             x-html="aiAnswers[faq.id]"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Load faqs array statically from Controller
    const faqs = @json($faqs);
    const categoriesMap = @json($categories);
</script>
@endsection
