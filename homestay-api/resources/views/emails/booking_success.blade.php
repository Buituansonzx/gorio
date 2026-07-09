<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đặt phòng thành công</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family:Arial, Helvetica, sans-serif;">
<table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:auto; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
    <tr>
        <td style="background:linear-gradient(90deg, #4facfe 0%, #00f2fe 100%); padding:24px; text-align:center;">
            <h1 style="margin:0; color:#fff; font-size:24px;">XÁC NHẬN ĐẶT PHÒNG THÀNH CÔNG</h1>
        </td>
    </tr>

    <tr>
        <td style="padding:32px 24px; color:#333333;">
            <p style="font-size:16px;">Xin chào <strong>{{ $booking->user->name }}</strong>,</p>
            <p style="font-size:15px; line-height:1.6;">Chúc mừng bạn đã <strong>đặt phòng thành công</strong> 🎉<br>
                Dưới đây là thông tin chi tiết đặt phòng của bạn:</p>

            <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse; margin-top:16px;">
                <tr style="background-color:#f8f9fa;">
                    <td style="width:40%; font-weight:bold;">Mã đặt phòng:</td>
                    <td>{{ $booking->code }}</td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Tên phòng:</td>
                    <td>{{ $booking->room->title }}</td>
                </tr>
                <tr style="background-color:#f8f9fa;">
                    <td style="font-weight:bold;">Địa chỉ:</td>
                    <td>{{ $booking->room->address }}, {{ $booking->room->district->name }}, {{ $booking->room->district->province->name }}</td>
                </tr>
                <tr style="background-color:#f8f9fa;">
                    <td style="font-weight:bold;">Ngày nhận phòng:</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Ngày trả phòng:</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y H:i') }}</td>
                </tr>
            </table>

            <p style="margin-top:24px; font-size:15px; line-height:1.6;">
                Cảm ơn bạn đã tin tưởng và chọn dịch vụ của chúng tôi 💐<br>
                Chúc bạn có một kỳ nghỉ thật tuyệt vời!
            </p>
        </td>
    </tr>

    <tr>
        <td style="background-color:#f4f4f4; text-align:center; padding:16px; font-size:12px; color:#777;">
            © {{ date('Y') }} YourHotel. Mọi quyền được bảo lưu.
        </td>
    </tr>
</table>
</body>
</html>
