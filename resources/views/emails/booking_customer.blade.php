<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt xe thành công</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
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
            background: linear-gradient(135deg, #0284c7, #0369a1);
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
        .welcome-text {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 30px;
            color: #334155;
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
            margin-bottom: 30px;
        }
        .total-price-value {
            font-size: 28px;
            font-weight: 900;
            color: #0284c7;
            margin-top: 5px;
        }
        .status-badge {
            display: inline-block;
            background-color: #fef3c7;
            color: #d97706;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 10px;
        }
        .next-steps {
            background-color: #fafaf9;
            border: 1px solid #f5f5f4;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 10px;
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
            color: #0284c7;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚗 Yêu Cầu Đặt Xe Thành Công</h1>
            <p>Cảm ơn quý khách đã tin tưởng dịch vụ thuê xe du lịch NKS</p>
        </div>
        <div class="content">
            <p class="welcome-text">
                Chào <strong>{{ $booking->customer_name }}</strong>,<br><br>
                Yêu cầu đặt thuê xe của bạn đã được ghi nhận trên hệ thống. Chủ xe và đội ngũ NKS sẽ kiểm tra và sớm liên hệ lại với bạn qua số điện thoại/Zalo để xác nhận hành trình và thủ tục bàn giao xe.
            </p>

            <!-- Trạng thái đơn hàng -->
            <div style="text-align: center; margin-bottom: 30px;">
                <div class="info-label">Trạng thái yêu cầu</div>
                <span class="status-badge">Chờ xác nhận từ chủ xe</span>
            </div>

            <!-- Thông tin xe -->
            <div class="section-title">Thông tin phương tiện đã đặt</div>
            <div class="car-details">
                <div class="car-info">
                    <h3 class="car-name">{{ $booking->car->title }}</h3>
                    <div class="car-price">
                        Đơn giá: {{ number_format($booking->car->price_per_day, 0, ',', '.') }}đ / ngày
                    </div>
                </div>
            </div>

            <!-- Thông tin lịch trình -->
            <div class="section-title">Chi tiết lịch trình thuê xe</div>
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
                    <div class="info-label">Điểm đón khách (Điểm đi)</div>
                    <div class="info-value">{{ $booking->pickup_location }}</div>
                </div>
                <div class="info-card">
                    <div class="info-label">Điểm trả khách (Điểm đến)</div>
                    <div class="info-value">{{ $booking->dropoff_location }}</div>
                </div>
                <div class="info-card full-width">
                    <div class="info-label">Dịch vụ đi kèm</div>
                    <div class="info-value">
                        @if($booking->has_driver)
                            Có kèm tài xế phục vụ
                        @else
                            Thuê xe tự lái
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tổng giá tiền -->
            <div class="total-price-box">
                <div class="info-label" style="font-size: 12px;">Tổng chi phí dự tính</div>
                <div class="total-price-value">{{ number_format($booking->total_price, 0, ',', '.') }} VNĐ</div>
            </div>

            <!-- Hướng dẫn tiếp theo -->
            <div class="section-title">Các bước tiếp theo</div>
            <div class="next-steps">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                    <!-- Step 1 -->
                    <tr>
                        <td valign="top" width="28" style="padding-bottom: 15px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="24" height="24" style="border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                <tr>
                                    <td align="center" valign="middle" bgcolor="#0284c7" width="24" height="24" style="border-radius: 12px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #ffffff; line-height: 24px; text-align: center; display: block;">
                                        1
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; line-height: 1.5; color: #44403c; padding-left: 10px; padding-bottom: 15px;">
                            Chủ xe sẽ nhận được thông báo đặt xe và gọi điện/nhắn tin Zalo trực tiếp cho bạn để thống nhất thời gian và địa điểm giao xe cụ thể.
                        </td>
                    </tr>
                    <!-- Step 2 -->
                    <tr>
                        <td valign="top" width="28" style="padding-bottom: 15px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="24" height="24" style="border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                <tr>
                                    <td align="center" valign="middle" bgcolor="#0284c7" width="24" height="24" style="border-radius: 12px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #ffffff; line-height: 24px; text-align: center; display: block;">
                                        2
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; line-height: 1.5; color: #44403c; padding-left: 10px; padding-bottom: 15px;">
                            Ký hợp đồng thuê xe, giao nhận giấy tờ liên quan (ví dụ: CCCD/Hộ chiếu, xe máy cọc nếu có).
                        </td>
                    </tr>
                    <!-- Step 3 -->
                    <tr>
                        <td valign="top" width="28" style="padding-bottom: 0;">
                            <table border="0" cellpadding="0" cellspacing="0" width="24" height="24" style="border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                <tr>
                                    <td align="center" valign="middle" bgcolor="#0284c7" width="24" height="24" style="border-radius: 12px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #ffffff; line-height: 24px; text-align: center; display: block;">
                                        3
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; line-height: 1.5; color: #44403c; padding-left: 10px; padding-bottom: 0;">
                            Nhận bàn giao xe thực tế, kiểm tra tình trạng xe và bắt đầu hành trình của bạn!
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="footer">
            <p>Hệ thống kết nối thuê xe du lịch <strong>NKS</strong></p>
            <p>Xem lịch sử đặt xe tại <a href="{{ url('/member') }}">Trang cá nhân của bạn</a></p>
        </div>
    </div>
</body>
</html>
