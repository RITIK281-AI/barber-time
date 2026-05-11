<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #ff6b00; padding: 24px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .header p { color: rgba(255,255,255,0.85); margin: 6px 0 0; font-size: 14px; }
        .body { padding: 30px; color: #333; }
        .alert-box { background: #fff4ee; border-left: 4px solid #ff6b00; border-radius: 6px; padding: 14px 18px; margin-bottom: 24px; }
        .alert-box p { margin: 0; font-weight: bold; color: #ff6b00; font-size: 15px; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .label { font-weight: bold; color: #555; }
        .badge { background: #ff6b00; color: #fff; padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 13px; }
        .footer { background: #f9f9f9; text-align: center; padding: 16px; font-size: 12px; color: #999; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 28px; background: #ff6b00; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✂️ TrimTime</h1>
            <p>Appointment Reminder</p>
        </div>
        <div class="body">
            <h2>Hi {{ $booking->user->name }}, your appointment is soon!</h2>

            <div class="alert-box">
                <p>⏰ Your appointment starts in 30 minutes. Get ready!</p>
            </div>

            <div class="detail-row">
                <span class="label">Shop</span>
                <span>{{ $booking->barberShop?->name ?? '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Barber</span>
                <span>{{ $booking->barber?->name ?? '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Service</span>
                <span>
                    @php
                        $serviceNames = $booking->services->pluck('name')->join(', ')
                                        ?: ($booking->service?->name ?? '—');
                    @endphp
                    {{ $serviceNames }}
                </span>
            </div>
            <div class="detail-row">
                <span class="label">Date</span>
                <span>{{ \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Time</span>
                <span>
                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                    –
                    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                </span>
            </div>
            <div class="detail-row">
                <span class="label">Amount</span>
                <span>Rs. {{ number_format($booking->final_amount ?? $booking->total_price ?? 0, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Status</span>
                <span class="badge">{{ ucfirst($booking->status) }}</span>
            </div>

            <p style="margin-top: 20px; color: #555;">
                Please make sure you arrive on time. Late cancellations may incur a fine.
            </p>
            <a href="{{ url('/dashboard?tab=bookings') }}" class="btn">View My Booking</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} TrimTime &mdash; Barber Booking System, Nepal<br>
            <small>You received this because you have an upcoming appointment.</small>
        </div>
    </div>
</body>
</html>
