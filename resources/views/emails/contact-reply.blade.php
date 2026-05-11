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
        .footer { background: #f4f4f4; padding: 20px; text-align: center; color: #999; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✂ TrimTime</h1>
        </div>
        <div class="body">
            <p>Hi {{ $name }},</p>
            <p>Thank you for reaching out to us. We have received your message and will get back to you within <strong>24 hours</strong>.</p>
            <p>If your query is urgent, you can also reach us directly at <a href="tel:9762752410" style="color:#ff6b00;">9762752410</a>.</p>
            <p>Best regards,<br><strong>The TrimTime Team</strong></p>
        </div>
        <div class="footer">
            © {{ date('Y') }} TrimTime — Pokhara, Nepal
        </div>
    </div>
</body>
</html>
