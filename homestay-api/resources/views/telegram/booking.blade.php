@php
    $statusText = match($order->status) {
        'pending' => '⏳ Chờ xác nhận',
        'paid' => '✅ Đặt phòng thành công',
        'cancelled'  => '❌ Đã hủy',
        'expired'  => '⌛ Hết hạn',
        default   => $order->status,
    };
@endphp

📦 <b>ĐƠN ĐẶT PHÒNG</b>
<b>Mã đơn:</b> {{ $order->code }}
<b>Trạng thái:</b> {{ $statusText }}

🏨 <b>Phòng:</b> {{ $order->room->name }}

👤 <b>Tên:</b> {{ $order->guest_name }}
📞 <b>SĐT:</b> {{ $order->guest_phone ?? 'Không có' }}

⏰ <b>Checkin:</b> {{ $order->check_in }}
🏁 <b>Checkout:</b> {{ $order->check_out }}
⚙️ <b>Voucher: </b> {{$order->voucher->code ?? 'Không có'}}

📝 <b>Ghi chú:</b> {{ $order->note ?? 'Không có' }}

💵 <b>Tổng tiền:</b> {{ number_format($order->total, 0, ',', '.') }} VND
@php
    $hasVoucher = !empty($order->voucher_id);
    $priceCalculator = new class {
        use \App\Ship\Traits\RoomPriceCalcTrait;
    };
    $buffer = $priceCalculator->calcPrice(
        Carbon\Carbon::parse($order->check_in),
        Carbon\Carbon::parse($order->check_out),
        $order->number_of_guests,
        $order->room,
        $order->user,
        false,
        $hasVoucher, 
        $order
    )['buffer'];
@endphp
😋 <b>Buffer: </b> {{ number_format(($order->buffer_price > 0 ? $order->buffer_price : $buffer), 0, ',', '.') }} VND
📅 <b>Ngày tạo:</b> {{ $order->created_at->format('H:i d/m/Y') }}
