<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;
use Illuminate\Support\Facades\File;

class FaqController extends Controller
{
    /**
     * Hiển thị trang FAQ cố định
     */
    public function index()
    {
        $faqPath = resource_path('data/faq.json');
        $faqs = [];

        if (File::exists($faqPath)) {
            $faqs = json_decode(File::get($faqPath), true);
        }

        $categories = [
            'engine' => 'Động cơ & Truyền động',
            'tires' => 'Lốp & Hệ thống phanh',
            'electrical' => 'Hệ thống điện, Ắc quy & Điều hòa',
            'fluids' => 'Bảo dưỡng định kỳ & Chất lỏng',
            'exterior' => 'Chăm sóc nội ngoại thất xe'
        ];

        return view('faq', compact('faqs', 'categories'));
    }

    /**
     * Lấy câu trả lời mở rộng bằng AI cho câu hỏi FAQ qua AJAX
     */
    public function getAiAnswer(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        $id = $request->input('id');
        $faqPath = resource_path('data/faq.json');
        
        if (!File::exists($faqPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Tệp câu hỏi FAQ không tồn tại.',
            ], 404);
        }

        $faqs = json_decode(File::get($faqPath), true);
        $faqItem = collect($faqs)->firstWhere('id', $id);

        if (!$faqItem) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy câu hỏi tương ứng.',
            ], 404);
        }

        // Gọi dịch vụ Gemini để lấy câu trả lời chuyên sâu
        $aiAnswer = GeminiService::getAiAnswerForFaq($faqItem['question'], $faqItem['answer']);

        // Chuyển đổi Markdown thô từ AI sang HTML cơ bản để hiển thị đẹp mắt
        $parsedHtml = $this->parseMarkdownToHtml($aiAnswer);

        return response()->json([
            'success' => true,
            'ai_answer' => $parsedHtml,
        ]);
    }

    /**
     * Hàm phân tích cú pháp Markdown cơ bản sang HTML
     */
    protected function parseMarkdownToHtml(string $markdown): string
    {
        // Chuyển dòng trống thành thẻ xuống dòng hoặc ngắt đoạn
        $html = nl2br(e($markdown));

        // Chuyển các ký tự in đậm **text** thành <strong>text</strong>
        $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);

        // Chuyển các ký tự in nghiêng *text* hoặc _text_ thành <em>text</em>
        $html = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $html);

        // Chuyển danh sách gạch đầu dòng "- text" thành danh sách HTML
        $html = preg_replace('/^\s*-\s+(.*?)$/m', '<li>$1</li>', $html);
        
        // Bọc các nhóm <li> cạnh nhau trong <ul>
        $html = preg_replace('/(<li>.*?<\/li>)+/s', '<ul class="list-disc pl-5 my-2 space-y-1 font-sans text-sm">$0</ul>', $html);

        return $html;
    }
}
