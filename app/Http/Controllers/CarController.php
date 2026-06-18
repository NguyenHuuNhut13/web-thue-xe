<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingAdminNotification;
use App\Mail\BookingCustomerNotification;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::where('status', 'active');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('seats')) {
            $query->where('seats', $request->seats);
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('price_min')) {
            $query->where('price_per_day', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price_per_day', '<=', $request->price_max);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        if ($sort === 'price_asc') {
            $query->orderBy('price_per_day', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price_per_day', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $cars = $query->paginate(9)->withQueryString();
        $brands = Car::where('status', 'active')->distinct()->pluck('brand');

        return view('cars.index', compact('cars', 'brands'));
    }

    public function suggest(Request $request)
    {
        $search = $request->query('search');

        if (empty($search)) {
            return response()->json([]);
        }

        $cars = Car::where('status', 'active')
            ->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('brand', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%');
            })
            ->limit(6)
            ->get();

        $results = $cars->map(function($car) {
            $thumbnail = 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80';
            if (is_array($car->images) && count($car->images) > 0) {
                $img = $car->images[0];
                if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                    $thumbnail = $img;
                } else {
                    $thumbnail = asset('storage/' . $img);
                }
            }

            return [
                'id' => $car->id,
                'title' => $car->title,
                'brand' => $car->brand,
                'model' => $car->model,
                'price' => number_format($car->price_per_day, 0, ',', '.') . 'đ/ngày',
                'slug' => $car->slug,
                'thumbnail' => $thumbnail,
                'url' => route('cars.show', $car->slug),
            ];
        });

        return response()->json($results);
    }

    public function map(Request $request)
    {
        $cars = Car::where('status', 'active')->get();
        return view('cars.map', compact('cars'));
    }

    public function favorites(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/member/login');
        }

        $cars = auth()->user()->favorites()->where('status', 'active')->paginate(9);
        return view('cars.favorites', compact('cars'));
    }

    public function show($slug)
    {
        $car = Car::where('slug', $slug)->where('status', 'active')->firstOrFail();
        
        $isFavorite = false;
        if (auth()->check()) {
            $isFavorite = auth()->user()->favorites()->where('car_id', $car->id)->exists();
        }

        $similarCars = Car::where('status', 'active')
            ->where('brand', $car->brand)
            ->where('id', '!=', $car->id)
            ->limit(3)
            ->get();

        if ($similarCars->isEmpty()) {
            $similarCars = Car::where('status', 'active')
                ->where('id', '!=', $car->id)
                ->limit(3)
                ->get();
        }

        return view('cars.show', compact('car', 'isFavorite', 'similarCars'));
    }

    public function toggleFavorite($id)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để lưu yêu thích!'], 401);
        }

        $user = auth()->user();
        $exists = $user->favorites()->where('car_id', $id)->exists();

        if ($exists) {
            $user->favorites()->detach($id);
            return response()->json(['success' => true, 'is_favorite' => false, 'message' => 'Đã bỏ lưu xe yêu thích!']);
        } else {
            $user->favorites()->attach($id);
            return response()->json(['success' => true, 'is_favorite' => true, 'message' => 'Đã thêm xe vào danh sách yêu thích!']);
        }
    }

    public function book($id, Request $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để thực hiện đặt xe!');
        }

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'customer_email' => 'required|email|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $car = Car::findOrFail($id);

        $startDate = new \DateTime($request->start_date);
        $endDate = new \DateTime($request->end_date);
        $days = $startDate->diff($endDate)->days;
        if ($days <= 0) {
            $days = 1;
        }

        $totalPrice = $car->price_per_day * $days;

        $booking = Booking::create([
            'car_id' => $car->id,
            'user_id' => auth()->id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'notes' => $request->notes,
            'pickup_location' => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'has_driver' => $car->has_driver,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
        ]);

        // Gửi email thông báo cho Admin
        try {
            $adminEmail = env('TEST_ADMIN_EMAIL', 'admin@nks.vn');
            Mail::to($adminEmail)->send(new BookingAdminNotification($booking));
        } catch (\Exception $e) {
            \Log::error('Lỗi gửi email cho Admin: ' . $e->getMessage());
        }

        // Gửi email thông báo cho Khách hàng
        try {
            $customerEmail = env('TEST_CUSTOMER_EMAIL', $booking->customer_email);
            Mail::to($customerEmail)->send(new BookingCustomerNotification($booking));
        } catch (\Exception $e) {
            \Log::error('Lỗi gửi email cho Khách hàng: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Yêu cầu đặt xe thành công! Chủ xe sẽ sớm liên hệ với bạn qua số điện thoại/Zalo để xác nhận.');
    }
}
