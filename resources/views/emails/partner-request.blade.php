<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
        .container { max-width: 580px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; }
        .header { background: #ff6b00; padding: 32px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .body { padding: 32px; color: #333; line-height: 1.7; }
        .details { background: #f9f9f9; padding: 20px; border-left: 4px solid #ff6b00; margin: 20px 0; border-radius: 4px; }
        .details p { margin: 8px 0; }
        .button { display: inline-block; background: #ff6b00; color: #fff; padding: 12px 28px; text-decoration: none; border-radius: 4px; margin: 20px 0; }
        .footer { background: #f4f4f4; padding: 20px; text-align: center; color: #999; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✂ TrimTime</h1>
        </div>
        <div class="body">
            <h2>New Partner Request</h2>
            <p>A new barber shop has submitted a request to become a TrimTime partner.</p>

            <div class="details">
                <p><strong>Shop Name:</strong> {{ $barberShop->name }}</p>
                <p><strong>Owner Name:</strong> {{ $barberShop->owner_name }}</p>
                <p><strong>Email:</strong> {{ $barberShop->email }}</p>
                <p><strong>Phone:</strong> {{ $barberShop->phone }}</p>
                <p><strong>Address:</strong> {{ $barberShop->address }}</p>
                <p><strong>District:</strong> {{ $barberShop->district }}</p>
                <p><strong>Number of Barbers:</strong> {{ $barberShop->number_of_barbers }}</p>
                <p><strong>Services Offered:</strong> {{ $barberShop->services_offered }}</p>
                @if($barberShop->description)
                <p><strong>Description:</strong> {{ $barberShop->description }}</p>
                @endif
            </div>

            <p>
                <a href="{{ route('admin.partners.show', $barberShop) }}" class="button">Review Partner Request</a>
            </p>

            <p>Thanks,<br><strong>The TrimTime Team</strong></p>
        </div>
        <div class="footer">
            © {{ date('Y') }} TrimTime — Pokhara, Nepal
        </div>
    </div>
</body>
</html>
