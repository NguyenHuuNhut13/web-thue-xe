<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SCarController extends Controller
{
    /**
     * Display the S-Car page
     */
    public function index()
    {
        $scarPath = resource_path('data/scar.json');
        $cars = [];

        if (File::exists($scarPath)) {
            $cars = json_decode(File::get($scarPath), true);
        }

        $brands = [
            'toyota' => [
                'name' => 'Toyota',
                'logo' => 'toyota.png'
            ],
            'vinfast' => [
                'name' => 'VinFast',
                'logo' => 'vinfast.png'
            ],
            'hyundai' => [
                'name' => 'Hyundai',
                'logo' => 'hyundai.png'
            ],
            'kia' => [
                'name' => 'Kia',
                'logo' => 'kia.png'
            ],
            'mazda' => [
                'name' => 'Mazda',
                'logo' => 'mazda.png'
            ],
            'honda' => [
                'name' => 'Honda',
                'logo' => 'honda.png'
            ],
            'ford' => [
                'name' => 'Ford',
                'logo' => 'ford.png'
            ],
            'mitsubishi' => [
                'name' => 'Mitsubishi',
                'logo' => 'mitsubishi.png'
            ],
            'suzuki' => [
                'name' => 'Suzuki',
                'logo' => 'suzuki.png'
            ],
            'mercedes_benz' => [
                'name' => 'Mercedes-Benz',
                'logo' => 'mercedes-benz.png'
            ],
            'bmw' => [
                'name' => 'BMW',
                'logo' => 'bmw.png'
            ],
            'audi' => [
                'name' => 'Audi',
                'logo' => 'audi.png'
            ],
            'lexus' => [
                'name' => 'Lexus',
                'logo' => 'lexus.png'
            ],
            'porsche' => [
                'name' => 'Porsche',
                'logo' => 'porsche.png'
            ],
            'byd' => [
                'name' => 'BYD',
                'logo' => 'byd.png'
            ],
            'isuzu' => [
                'name' => 'Isuzu',
                'logo' => 'isuzu.png'
            ],
            'aion' => [
                'name' => 'Aion',
                'logo' => 'gac-group.png'
            ],
            'aston_martin' => [
                'name' => 'Aston Martin',
                'logo' => 'aston-martin.png'
            ],
            'bentley' => [
                'name' => 'Bentley',
                'logo' => 'bentley.png'
            ],
            'dongfeng' => [
                'name' => 'Dongfeng',
                'logo' => 'dongfeng.png'
            ],
            'gac' => [
                'name' => 'GAC',
                'logo' => 'gac-group.png'
            ],
            'geely' => [
                'name' => 'Geely',
                'logo' => 'geely.png'
            ],
            'haima' => [
                'name' => 'Haima',
                'logo' => 'haima.png'
            ],
            'haval' => [
                'name' => 'Haval',
                'logo' => 'haval.png'
            ],
            'hongqi' => [
                'name' => 'Hongqi',
                'logo' => 'hongqi.png'
            ]
        ];

        $categories = [
            'sedan' => 'Sedan',
            'suv' => 'SUV',
            'crossover' => 'Crossover',
            'mpv' => 'MPV',
            'pickup' => 'Bán tải',
            'hatchback' => 'Hatchback',
            'coupe' => 'Coupe',
            'wagon' => 'Station wagon',
            'convertible' => 'Convertible',
            'ev' => 'Ô tô điện',
            'hybrid' => 'Hybrid',
            'van' => 'Van'
        ];

        return view('s-car', compact('cars', 'brands', 'categories'));
    }

    /**
     * Handle the negotiation lead submission
     */
    public function negotiate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:2000',
            'car_brand' => 'required|string|max:100',
            'car_model' => 'required|string|max:100',
            'car_version' => 'required|string|max:100',
            'list_price' => 'required|numeric',
        ]);

        $brand = $request->input('car_brand');
        $model = $request->input('car_model');
        $version = $request->input('car_version');
        $listPrice = number_format($request->input('list_price') / 1000000, 0, ',', '.') . ' triệu';

        $subject = "[S-Car] Đàm phán mua xe: {$brand} {$model} {$version}";
        
        $customerMsg = $request->input('message') ?: 'Không có lời nhắn bổ sung.';
        
        $messageBody = "Yêu cầu đàm phán giá xe mới từ khách hàng:\n"
            . "- Dòng xe: {$brand} {$model} ({$version})\n"
            . "- Giá niêm yết: {$listPrice} VND\n"
            . "- Lời nhắn của khách hàng: {$customerMsg}";

        Contact::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'subject' => $subject,
            'message' => $messageBody,
            'status' => 'new',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Yêu cầu đàm phán của bạn đã được gửi thành công! Chuyên viên NKS sẽ liên hệ lại sớm nhất để tư vấn ưu đãi tốt nhất.',
        ]);
    }
}
