<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class ChatbotController extends Controller
{
    /**
     * Xử lý tin nhắn và lưu trữ lịch sử hội thoại trong Session
     */
    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userMessage = $request->input('message');
        
        // Lấy lịch sử hội thoại từ session (mặc định rỗng)
        $chatHistory = session('nks_chat_history', []);

        // Gọi Gemini API lấy phản hồi
        $aiResponse = GeminiService::getChatbotResponse($userMessage, $chatHistory);

        // Cập nhật lịch sử: Thêm tin nhắn của User và AI
        $chatHistory[] = ['sender' => 'user', 'text' => $userMessage];
        $chatHistory[] = ['sender' => 'model', 'text' => $aiResponse];

        // Giới hạn lịch sử chỉ giữ lại 10 tin nhắn gần nhất để tránh tràn bộ nhớ
        if (count($chatHistory) > 10) {
            $chatHistory = array_slice($chatHistory, -10);
        }

        // Lưu lại lịch sử vào session
        session(['nks_chat_history' => $chatHistory]);

        // Chuyển đổi Markdown từ AI sang HTML để hiển thị mượt mà trên khung chat
        $parsedHtml = $this->parseMarkdownToHtml($aiResponse);

        return response()->json([
            'success' => true,
            'response' => $parsedHtml,
        ]);
    }

    /**
     * Hàm phân tích cú pháp Markdown sang HTML cơ bản
     */
    protected function parseMarkdownToHtml(string $markdown): string
    {
        $html = nl2br(e($markdown));

        // Bold **text** -> <strong>text</strong>
        $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);

        // Italic *text* -> <em>text</em>
        $html = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $html);

        // List bullet "- text" -> <li>text</li>
        $html = preg_replace('/^\s*-\s+(.*?)$/m', '<li>$1</li>', $html);
        
        // Wrap <li> groups in <ul>
        $html = preg_replace('/(<li>.*?<\/li>)+/s', '<ul class="list-disc pl-4 my-1 space-y-0.5">$0</ul>', $html);

        return $html;
    }

    /**
     * Xóa lịch sử chat để bắt đầu hội thoại mới
     */
    public function clearHistory()
    {
        session()->forget('nks_chat_history');
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa lịch sử trò chuyện.',
        ]);
    }
}
