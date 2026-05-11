<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #1a1a2e; padding: 24px; text-align: center; }
        .header h1 { color: #f5c518; margin: 0; font-size: 24px; }
        .body { padding: 30px; color: #333; }
        .success-icon { text-align: center; font-size: 56px; margin: 10px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .label { font-weight: bold; color: #555; }
        .total { font-size: 18px; font-weight: bold; color: #28a745; }
        .footer { background: #f9f9f9; text-align: center; padding: 16px; font-size: 12px; color: #999; }
        .thank-you { background: #fffbea; border-left: 4px solid #f5c518; padding: 16px; margin-top: 24px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1> TrimTime</h1>
        </div>
        <div class="body">
            <div class="success-icon">✅</div>
            <h2 style="text-align:center;">Payment Successful!</h2>
            <p>Hi <strong>{{ $booking->user->name }}</strong>, your payment has been received. Your appointment is all set!</p>

            <div class="detail-row">
                <span class="label">Booking ID</span>
                <span>#{{ $booking->id }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Service</span>
                <span>{{ $booking->service->name }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Barber Shop</span>
                <span>{{ $booking->barberShop->name }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Date & Time</span>
                <span>{{ \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y') }} at {{ $booking->start_time }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Amount Paid</span>
                <span class="total">Rs. {{ number_format($booking->final_amount, 2) }}</span>
            </div>

            <div class="thank-you">
                <strong>Thank you for choosing TrimTime! </strong><br>
                We hope you enjoy your experience at {{ $booking->barberShop->name }}. See you there!
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} TrimTime &mdash; Barber Booking System, Nepal
        </div>
    </div>
</body>
</html>
