<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #1a1a2e; padding: 24px; text-align: center; }
        .header h1 { color: #f5c518; margin: 0; font-size: 24px; }
        .body { padding: 30px; color: #333; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .label { font-weight: bold; color: #555; }
        .badge { background: #f5c518; color: #1a1a2e; padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 13px; }
        .footer { background: #f9f9f9; text-align: center; padding: 16px; font-size: 12px; color: #999; }
        .btn { display: inline-block; margin-top: 20px; padding: 12px 28px; background: #1a1a2e; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✂️ BarberTime</h1>
        </div>
        <div class="body">
            <h2>New Booking Request!</h2>
            <p>You have received a new appointment booking. Please review and confirm it from your dashboard.</p>

            <div class="detail-row">
                <span class="label">Customer</span>
                <span>{{ $booking->user->name }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Service</span>
                <span>{{ $booking->service->name }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Date & Time</span>
                <span>{{ \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y') }} at {{ $booking->booking_time }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Amount</span>
                <span>Rs. {{ number_format($booking->total_amount, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Status</span>
                <span class="badge">{{ ucfirst($booking->status) }}</span>
            </div>

            <p style="margin-top: 20px;">Please log in to your dashboard to confirm or manage this booking.</p>
            <a href="{{ url('/barber/bookings') }}" class="btn">View Booking</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BarberTime &mdash; Barber Booking System, Nepal
        </div>
    </div>
</body>
</html>
