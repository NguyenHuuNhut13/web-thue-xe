<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu đặt xe mới</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #0077bb, #005599);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 35px 30px;
        }
        .section-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 15px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 8px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-card {
            background-color: #f8fafc;
            border-radius: 16px;
            padding: 16px;
            border: 1px solid #f1f5f9;
        }
        .info-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }
        .full-width {
            grid-column: span 2;
        }
        .car-details {
            display: flex;
            align-items: center;
            background-color: #f0f9ff;
            border: 1px solid #e0f2fe;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .car-info {
            margin-left: 0px;
        }
        .car-name {
            font-size: 16px;
            font-weight: 850;
            color: #0369a1;
            margin: 0 0 5px 0;
        }
        .car-price {
            font-size: 13px;
            color: #0284c7;
            font-weight: 600;
        }
        .total-price-box {
            text-align: center;
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 20px;
            padding: 25px;
            margin-top: 10px;
        }
        .total-price-value {
            font-size: 28px;
            font-weight: 900;
            color: #0077bb;
            margin-top: 5px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 25px 30px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #f1f5f9;
        }
        .footer a {
            color: #0077bb;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Yêu Cầu Đặt Xe Mới</h1>
            <p>Mã đơn đặt xe: #{{ $booking->id }} - Ngày đặt: {{ $booking->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="content">
            <!-- Thông tin xe -->
            <div class="section-title">Thông tin phương tiện</div>
            <div class="car-details">
                <div class="car-info">
                    <h3 class="car-name">{{ $booking->car->title }}</h3>
                    <div class="car-price">
                        Đơn giá: {{ number_format($booking->car->price_per_day, 0, ',', '.') }}đ / ngày
                    </div>
                </div>
            </div>

            <!-- Thông tin khách hàng -->
            <div class="section-title">Thông tin khách thuê</div>
            <div class="grid">
                <div class="info-card">
                    <div class="info-label">Họ và tên</div>
                    <div class="info-value">{{ $booking->customer_name }}</div>
                </div>
                <div class="info-card">
                    <div class="info-label">Số điện thoại</div>
                    <div class="info-value">{{ $booking->customer_phone }}</div>
                </div>
                <div class="info-card full-width">
                    <div class="info-label">Địa chỉ Email</div>
                    <div class="info-value">{{ $booking->customer_email }}</div>
                </div>
            </div>

            <!-- Thông tin chuyến đi -->
            <div class="section-title">Chi tiết lịch trình</div>
            <div class="grid">
                <div class="info-card">
                    <div class="info-label">Ngày nhận xe</div>
                    <div class="info-value">{{ $booking->start_date->format('d/m/Y') }}</div>
                </div>
                <div class="info-card">
                    <div class="info-label">Ngày trả xe</div>
                    <div class="info-value">{{ $booking->end_date->format('d/m/Y') }}</div>
                </div>
                <div class="info-card">
                    <div class="info-label">Điểm nhận xe (Điểm đi)</div>
                    <div class="info-value">{{ $booking->pickup_location }}</div>
                </div>
                <div class="info-card">
                    <div class="info-label">Điểm trả xe (Điểm đến)</div>
                    <div class="info-value">{{ $booking->dropoff_location }}</div>
                </div>
                <div class="info-card full-width">
                    <div class="info-label">Dịch vụ tài xế kèm theo</div>
                    <div class="info-value">
                        @if($booking->has_driver)
                            <span style="color: #16a34a; font-weight: bold;">Có kèm tài xế</span>
                        @else
                            <span style="color: #475569; font-weight: bold;">Tự lái (Không kèm tài xế)</span>
                        @endif
                    </div>
                </div>
                @if($booking->notes)
                    <div class="info-card full-width">
                        <div class="info-label">Ghi chú từ khách hàng</div>
                        <div class="info-value" style="font-weight: 500; font-style: italic;">"{{ $booking->notes }}"</div>
                    </div>
                @endif
            </div>

            <!-- Tổng cộng tiền -->
            <div class="total-price-box">
                <div class="info-label" style="font-size: 12px;">Tổng doanh thu dự tính</div>
                <div class="total-price-value">{{ number_format($booking->total_price, 0, ',', '.') }} VNĐ</div>
            </div>
        </div>
        <div class="footer">
            <p>Hệ thống kết nối thuê xe du lịch cao cấp <strong>NKS</strong></p>
            <p>Quản lý đơn đặt xe trực tuyến tại <a href="{{ url('/admin') }}">Trang Admin</a></p>
        </div>
    </div>
</body>
</html>
