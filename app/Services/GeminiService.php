<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Car;

class GeminiService
{
    /**
     * Gửi request lên Gemini API để tạo nội dung
     */
    protected static function callGemini(array $contents, ?string $systemInstruction = null): ?string
    {
        $apiKey = env('GEMINI_API_KEY');
        
        if (empty($apiKey)) {
            Log::warning('Gemini API key is not configured in .env file.');
            return null;
        }

        $models = [
            'gemini-3.1-flash-lite' => ['timeout' => 5, 'thinking' => false],
            'gemini-flash-lite-latest' => ['timeout' => 5, 'thinking' => false],
            'gemini-2.5-flash' => ['timeout' => 8, 'thinking' => false],
            'gemini-3.5-flash' => ['timeout' => 3, 'thinking' => true]
        ];

        foreach ($models as $model => $config) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $apiKey;

            $body = [
                'contents' => $contents
            ];

            if ($config['thinking']) {
                $body['generationConfig'] = [
                    'thinkingConfig' => [
                        'thinkingLevel' => 'minimal'
                    ]
                ];
            }

            if ($systemInstruction) {
                $body['systemInstruction'] = [
                    'parts' => [
                        ['text' => $systemInstruction]
                    ]
                ];
            }

            try {
                Log::info("Attempting to call Gemini API with model: {$model}");
                
                $response = Http::timeout($config['timeout'])
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, $body);

                if ($response->failed()) {
                    Log::warning("Gemini API request failed for model {$model}. Status: {$response->status()}. Response: " . $response->body());
                    continue; // Try next model
                }

                $result = $response->json();
                
                // Extract response text
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
                
                if (!empty($text)) {
                    Log::info("Successfully generated content using model: {$model}");
                    return $text;
                } else {
                    Log::warning("Gemini API returned empty text for model {$model}");
                }
            } catch (\Exception $e) {
                Log::warning("Exception when calling Gemini API with model {$model}: " . $e->getMessage());
                continue; // Try next model
            }
        }

        Log::error("All Gemini fallback models failed.");
        return null;
    }

    /**
     * Lấy câu trả lời mở rộng của AI cho câu hỏi FAQ cố định
     */
    public static function getAiAnswerForFaq(string $question, string $fixedAnswer): string
    {
        $systemInstruction = "Bạn là chuyên gia tư vấn kỹ thuật và bảo dưỡng xe ô tô của NKS Car Rental. Nhiệm vụ của bạn là giải thích chuyên sâu, cung cấp thêm lời khuyên hữu ích, các bước thực hiện chi tiết hoặc lưu ý an toàn cho câu hỏi bảo dưỡng xe của người dùng. Hãy viết câu trả lời sinh động, ngắn gọn và súc tích (khoảng 150-200 từ) nhưng vẫn đầy đủ thông tin, chuyên nghiệp bằng tiếng Việt và định dạng Markdown (in đậm, danh sách gạch đầu dòng nếu cần).";

        $prompt = "Câu hỏi từ người dùng: \"{$question}\"\n\nCâu trả lời cơ bản hiện tại của hệ thống:\n\"{$fixedAnswer}\"\n\nHãy viết một câu trả lời bổ sung, mở rộng và chi tiết hơn dựa trên câu hỏi trên.";

        $contents = [
            [
                'role' => 'user',
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ];

        $response = self::callGemini($contents, $systemInstruction);

        if (empty($response)) {
            return "🤖 **[Trợ lý AI NKS]** Hiện tại kết nối với máy chủ AI đang bận. Lời khuyên nhanh: Bạn nên kiểm tra sổ tay hướng dẫn kỹ thuật đi kèm xe của nhà sản xuất hoặc mang xe đến xưởng dịch vụ gần nhất để được kiểm tra trực quan một cách chính xác nhất.";
        }

        return $response;
    }

    /**
     * Xử lý tin nhắn chat từ người dùng cho AI Chatbot
     */
    public static function getChatbotResponse(string $userMessage, array $chatHistory = []): string
    {
        // 1. Thu thập danh sách xe đang hoạt động ở NKS để cung cấp ngữ cảnh thực tế
        $activeCars = Car::where('status', 'active')
            ->limit(10)
            ->get(['brand', 'model', 'price_per_day', 'seats', 'transmission', 'fuel_type', 'location']);

        $carsContext = "";
        if ($activeCars->count() > 0) {
            $carsContext = "Dưới đây là một số xe thực tế đang cho thuê tại NKS Car Rental mà bạn có thể giới thiệu cho khách hàng:\n";
            foreach ($activeCars as $car) {
                $transmissionStr = $car->transmission === 'automatic' ? 'Số tự động' : 'Số sàn';
                $fuelStr = $car->fuel_type === 'electric' ? 'Điện' : ($car->fuel_type === 'diesel' ? 'Dầu' : 'Xăng');
                $priceFormatted = number_format($car->price_per_day, 0, ',', '.') . 'đ/ngày';
                
                $carsContext .= "- Xe {$car->brand} {$car->model} ({$car->seats} chỗ, {$transmissionStr}, máy {$fuelStr}), giá thuê {$priceFormatted}, khu vực: {$car->location}\n";
            }
        } else {
            $carsContext = "Hiện tại cơ sở dữ liệu xe trực tuyến của NKS đang được bảo trì, hãy khuyên khách hàng truy cập trang 'Thuê xe du lịch' để xem danh sách cập nhật mới nhất.\n";
        }

        // 2. Xây dựng System Instruction chỉ đạo hành vi của AI
        $systemInstruction = "Bạn là Trợ lý ảo AI đắc lực của NKS Car Rental - Hệ thống cho thuê xe du lịch tự lái hàng đầu Việt Nam.\n"
            . "Nhiệm vụ của bạn:\n"
            . "1. Tư vấn và giải đáp thông tin về các hãng xe ô tô trên thị trường, thông số kỹ thuật xe và khoảng giá xe mua mới/cũ trên thị trường.\n"
            . "2. Ưu tiên giới thiệu nhiệt tình các dòng xe đang có sẵn tại NKS khi khách hàng hỏi về dịch vụ thuê xe (sử dụng danh sách xe được cung cấp bên dưới).\n"
            . "3. Tư vấn kinh nghiệm lái xe và cách bảo dưỡng xe nếu được hỏi.\n"
            . "Yêu cầu trả lời:\n"
            . "- Trả lời ngắn gọn, thân thiện, lịch sự, chuyên nghiệp bằng tiếng Việt.\n"
            . "- Dùng định dạng Markdown để làm nổi bật tên xe, mức giá.\n"
            . "- Luôn nhắc khách hàng rằng họ có thể xem vị trí và đặt xe trực tiếp trên bản đồ của NKS.\n\n"
            . $carsContext;

        // 3. Chuẩn bị mảng hội thoại (bao gồm lịch sử chat)
        $contents = [];
        
        // Định dạng lịch sử trò chuyện theo chuẩn cấu trúc của Gemini API
        foreach ($chatHistory as $msg) {
            $role = ($msg['sender'] === 'user') ? 'user' : 'model';
            $contents[] = [
                'role' => $role,
                'parts' => [
                    ['text' => $msg['text']]
                ]
            ];
        }

        // Thêm tin nhắn hiện tại của người dùng
        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $userMessage]
            ]
        ];

        $response = self::callGemini($contents, $systemInstruction);

        if (empty($response)) {
            return "🤖 Cảm ơn câu hỏi của bạn. Hệ thống AI của NKS đang quá tải, bạn có thể tham khảo trực tiếp danh sách xe tự lái của chúng tôi tại trang **[Thuê xe du lịch](/cars)** hoặc liên hệ hotline chăm sóc khách hàng ở trang **[Liên hệ](/contact)** để được tư vấn nhanh nhất nhé!";
        }

        return $response;
    }
}
