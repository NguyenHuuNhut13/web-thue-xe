<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Car;
use App\Models\Booking;
use App\Models\Blog;
use App\Models\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'NKS Admin',
            'email' => 'admin@nks.vn',
            'password' => Hash::make('password'),
            'phone' => '0932030958',
            'zalo' => '0932030958',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $owner1 = User::create([
            'name' => 'Nguyễn Văn Hùng',
            'email' => 'hung.owner@nks.vn',
            'password' => Hash::make('password'),
            'phone' => '0932030958',
            'zalo' => '0932030958',
            'role' => 'member',
            'status' => 'active',
            'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150&q=80',
        ]);

        $owner2 = User::create([
            'name' => 'Trần Thị Mai',
            'email' => 'mai.owner@nks.vn',
            'password' => Hash::make('password'),
            'phone' => '0911223344',
            'zalo' => '0911223344',
            'role' => 'member',
            'status' => 'active',
            'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&h=150&q=80',
        ]);

        $renter = User::create([
            'name' => 'Lê Minh Khoa',
            'email' => 'client@nks.vn',
            'password' => Hash::make('password'),
            'phone' => '0988776655',
            'zalo' => '0988776655',
            'role' => 'member',
            'status' => 'active',
            'avatar' => 'https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&fit=crop&w=150&h=150&q=80',
        ]);

        // 2. Create Cars
        $carsData = [
            [
                'user_id' => $owner1->id,
                'title' => 'Toyota Fortuner Legender 2022',
                'brand' => 'Toyota',
                'model' => 'Fortuner',
                'year' => 2022,
                'fuel_type' => 'diesel',
                'transmission' => 'automatic',
                'seats' => 7,
                'price_per_day' => 1200000.00,
                'description' => 'Xe Toyota Fortuner Legender 2022 máy dầu, số tự động cực tiết kiệm nhiên liệu. Xe gia đình đi giữ gìn kỹ, trang bị màn hình Android, camera 360 độ, cảnh báo lệch làn, cốp điện tiện lợi.',
                'images' => [
                    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80'
                ],
                'location' => '222 Lê Văn Sỹ, Phường 14, Quận 3, TP.HCM',
                'latitude' => 10.7904,
                'longitude' => 106.6713,
                'status' => 'active',
            ],
            [
                'user_id' => $owner1->id,
                'title' => 'Honda Civic RS 2023',
                'brand' => 'Honda',
                'model' => 'Civic',
                'year' => 2023,
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'seats' => 5,
                'price_per_day' => 950000.00,
                'description' => 'Honda Civic RS 2023 thể thao, năng động. Cảm giác lái cực bốc, trang bị gói an toàn Honda SENSING, phanh tay điện tử, sạc không dây. Xe mới cứng, rửa dọn sạch sẽ trước khi bàn giao.',
                'images' => [
                    'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80'
                ],
                'location' => 'Sân bay Tân Sơn Nhất, Quận Tân Bình, TP.HCM',
                'latitude' => 10.8185,
                'longitude' => 106.6588,
                'status' => 'active',
            ],
            [
                'user_id' => $owner2->id,
                'title' => 'VinFast VF8 Plus 2023',
                'brand' => 'VinFast',
                'model' => 'VF8',
                'year' => 2023,
                'fuel_type' => 'electric',
                'transmission' => 'automatic',
                'seats' => 5,
                'price_per_day' => 1300000.00,
                'description' => 'Xe điện VinFast VF8 Plus 2023 cao cấp. Xe êm ái, tăng tốc nhanh, sạc đầy đi được hơn 400km. Có hỗ trợ giao xe tận nơi. Tích hợp trợ lý ảo thông minh tiếng Việt.',
                'images' => [
                    'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1580273916550-e323be2ae537?auto=format&fit=crop&w=800&q=80'
                ],
                'location' => 'Khu đô thị Thảo Điền, Quận 2, TP.HCM',
                'latitude' => 10.8038,
                'longitude' => 106.7314,
                'status' => 'active',
            ],
            [
                'user_id' => $owner2->id,
                'title' => 'Mitsubishi Xpander Premium 2022',
                'brand' => 'Mitsubishi',
                'model' => 'Xpander',
                'year' => 2022,
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'seats' => 7,
                'price_per_day' => 800000.00,
                'description' => 'Xe 7 chỗ quốc dân Mitsubishi Xpander đời 2022 bản Premium cao cấp. Gầm cao thoáng mát, điều hòa 2 dàn lạnh siêu mát lạnh, khoang hành lý rộng rãi, rất thích hợp du lịch gia đình.',
                'images' => [
                    'https://images.unsplash.com/photo-1617788138017-80ad40651399?auto=format&fit=crop&w=800&q=80'
                ],
                'location' => 'Khu đô thị Phú Mỹ Hưng, Quận 7, TP.HCM',
                'latitude' => 10.7294,
                'longitude' => 106.7218,
                'status' => 'active',
            ],
            [
                'user_id' => $owner1->id,
                'title' => 'Ford Ranger Wildtrak 2.0L 2021',
                'brand' => 'Ford',
                'model' => 'Ranger',
                'year' => 2021,
                'fuel_type' => 'diesel',
                'transmission' => 'automatic',
                'seats' => 5,
                'price_per_day' => 1100000.00,
                'description' => 'Bán tải Ford Ranger Wildtrak 2.0 Bi-Turbo mạnh mẽ. Thích hợp đi các cung đường đồi dốc, phượt Tây Nguyên hoặc chở nhiều đồ đạc dã ngoại. Xe trang bị sẵn nắp thùng cuộn điện che mưa.',
                'images' => [
                    'https://images.unsplash.com/photo-1532581291347-9c39cf10a73c?auto=format&fit=crop&w=800&q=80'
                ],
                'location' => 'Công viên Gia Định, Quận Gò Vấp, TP.HCM',
                'latitude' => 10.8122,
                'longitude' => 106.6784,
                'status' => 'active',
            ],
            [
                'user_id' => $owner2->id,
                'title' => 'Hyundai Accent 1.4 AT 2021',
                'brand' => 'Hyundai',
                'model' => 'Accent',
                'year' => 2021,
                'fuel_type' => 'gasoline',
                'transmission' => 'automatic',
                'seats' => 5,
                'price_per_day' => 600000.00,
                'description' => 'Hyundai Accent số tự động đời 2021, xe đi mượt mà, điều hòa mát rượi, rất tiết kiệm xăng. Xe nhỏ gọn cực kỳ phù hợp luồn lách trong phố đông đúc hoặc đi lại cự ly ngắn.',
                'images' => [
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80'
                ],
                'location' => 'Chợ Bến Thành, Quận 1, TP.HCM',
                'latitude' => 10.7719,
                'longitude' => 106.6983,
                'status' => 'active',
            ]
        ];

        $cars = [];
        foreach ($carsData as $data) {
            $data['slug'] = Str::slug($data['title']);
            $cars[] = Car::create($data);
        }

        // 3. Create Bookings
        Booking::create([
            'car_id' => $cars[0]->id, // Fortuner
            'user_id' => $renter->id,
            'start_date' => now()->addDays(2)->format('Y-m-d'),
            'end_date' => now()->addDays(5)->format('Y-m-d'),
            'total_price' => 3600000.00,
            'status' => 'approved',
            'notes' => 'Tôi cần thuê xe tự lái đi Vũng Tàu cùng gia đình. Giao nhận xe tại nhà riêng.',
        ]);

        Booking::create([
            'car_id' => $cars[1]->id, // Civic
            'user_id' => $renter->id,
            'start_date' => now()->addDays(7)->format('Y-m-d'),
            'end_date' => now()->addDays(9)->format('Y-m-d'),
            'total_price' => 1900000.00,
            'status' => 'pending',
            'notes' => 'Mình cần thuê đi công tác tại Bình Dương.',
        ]);

        // 4. Create Blogs
        Blog::create([
            'user_id' => $admin->id,
            'title' => 'Cẩm nang thuê xe du lịch tự lái tại TP.HCM từ A đến Z',
            'slug' => 'cam-nang-thue-xe-du-lich-tu-lai-tai-tphcm',
            'summary' => 'Những lưu ý quan trọng về thủ tục, kiểm tra xe, và hợp đồng khi thuê xe tự lái giúp bạn có chuyến đi an toàn và trọn vẹn nhất.',
            'content' => '<h2>1. Chuẩn bị giấy tờ thủ tục cần thiết</h2><p>Khi thuê xe tự lái tại Việt Nam, bạn cần chuẩn bị các giấy tờ cơ bản sau: Hộ khẩu hoặc CCCD gắn chip (có nơi yêu cầu tài sản thế chấp hoặc tiền mặt đặt cọc), Bằng lái xe hạng B1 hoặc B2 trở lên còn hạn sử dụng...</p><h2>2. Kiểm tra xe trước khi nhận bàn giao</h2><p>Điều này rất quan trọng để tránh tranh chấp khi trả xe. Hãy chụp ảnh, quay video quanh vỏ xe, lốp xe, kính chắn gió. Kiểm tra mức nhiên liệu và kiểm tra hệ thống điều hòa, còi, gạt mưa...</p>',
            'image' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
            'status' => 'published',
        ]);

        Blog::create([
            'user_id' => $admin->id,
            'title' => 'Top 5 cung đường phượt bằng ô tô gần Sài Gòn cuối tuần',
            'slug' => 'top-5-cung-duong-phuot-bang-o-to-gan-sai-gon',
            'summary' => 'Gợi ý những địa điểm du lịch cuối tuần cực chill bằng xe tự lái chỉ cách TP.HCM từ 2 - 4 tiếng lái xe.',
            'content' => '<h2>1. Vũng Tàu - Cung đường biển quen thuộc</h2><p>Chỉ cách Sài Gòn khoảng 100km đi theo hướng Cao tốc Long Thành - Dầu Giây rồi rẽ QL51. Vũng Tàu là lựa chọn hoàn hảo cho kỳ nghỉ ngắn ngày.</p><h2>2. Hồ Tràm - Bình Châu</h2><p>Hoang sơ hơn Vũng Tàu, Hồ Tràm có nhiều resort cao cấp, bãi biển đẹp và suối khoáng nóng thích hợp nghỉ dưỡng cùng gia đình.</p>',
            'image' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80',
            'status' => 'published',
        ]);

        // 5. Create Contacts
        Contact::create([
            'name' => 'Lê Văn Nam',
            'email' => 'namle@gmail.com',
            'phone' => '0944556677',
            'subject' => 'Hỏi về thủ tục thuê xe ô tô điện VinFast',
            'message' => 'Xin chào NKS, tôi muốn hỏi thuê xe VF8 tự lái 3 ngày thì có kèm thẻ sạc pin miễn phí của VinFast không? Thủ tục nhận xe thế nào?',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Phạm Thanh Thủy',
            'email' => 'thuypt@gmail.com',
            'phone' => '0933445566',
            'subject' => 'Đăng ký xe cho thuê trên NKS',
            'message' => 'Tôi có xe Hyundai Santafe 2022 muốn đăng tin cho thuê trên hệ thống của các bạn, vui lòng liên hệ tư vấn giúp tôi.',
            'status' => 'new',
        ]);
    }
}
