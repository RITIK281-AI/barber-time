<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Appointment Assigned</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f6f6f6; margin:0; padding:24px;">
    <div style="max-width:620px; margin:0 auto; background:#ffffff; border:1px solid #ececec; border-radius:10px; overflow:hidden;">
        <div style="background:#222; color:#fff; padding:16px 20px; font-size:20px; font-weight:700;">
            TrimTime Barber Notice
        </div>
        <div style="padding:20px; color:#222;">
            <p style="margin-top:0;">Hi {{ $booking->barber?->name ?? 'Barber' }},</p>
            <p>You have a confirmed appointment assigned to you.</p>

            @php
                $serviceNames = $booking->items->pluck('service_name')->join(', ')
                    ?: ($booking->service?->name ?? 'Service');
            @endphp

            <table style="width:100%; border-collapse:collapse; margin-top:12px;">
                <tr>
                    <td style="padding:8px 0; color:#666; width:160px;">Customer Name</td>
                    <td style="padding:8px 0;">{{ $booking->user?->name ?? 'Customer' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#666;">Customer Phone</td>
                    <td style="padding:8px 0;">{{ $booking->user?->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#666;">Service</td>
                    <td style="padding:8px 0;">{{ $serviceNames }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#666;">Date</td>
                    <td style="padding:8px 0;">{{ $booking->booking_date->format('D, d M Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#666;">Time</td>
                    <td style="padding:8px 0;">
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} -
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#666;">Shop</td>
                    <td style="padding:8px 0;">{{ $booking->barberShop?->name ?? 'Your Shop' }}</td>
                </tr>
            </table>

            <p style="margin:16px 0 0;">Please be ready before the appointment time.</p>
        </div>
    </div>
</body>
</html>
