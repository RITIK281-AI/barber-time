<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
        .container { max-width: 580px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; }
        .header { background: #1a1a1a; padding: 24px 32px; }
        .header h1 { color: #ff6b00; margin: 0; font-size: 20px; }
        .body { padding: 32px; color: #333; line-height: 1.7; }
        .field { margin-bottom: 16px; }
        .label { font-size: 12px; font-weight: 700; color: #999; text-transform: uppercase; letter-spacing: 0.5px; }
        .value { font-size: 15px; color: #111; margin-top: 4px; }
        .message-box { background: #f9f9f9; border-left: 4px solid #ff6b00; padding: 16px; border-radius: 4px; }
        .footer { background: #f4f4f4; padding: 20px; text-align: center; color: #999; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✂ TrimTime — New Contact Message</h1>
        </div>
        <div class="body">
            <div class="field">
                <div class="label">From</div>
                <div class="value">{{ $name }} &lt;{{ $email }}&gt;</div>
            </div>
            <div class="field">
                <div class="label">Subject</div>
                <div class="value">{{ $subject }}</div>
            </div>
            <div class="field">
                <div class="label">Message</div>
                <div class="value message-box">{{ $contactMessage }}</div>
            </div>
            <p style="margin-top:24px; color:#666; font-size:13px;">
                Received at {{ now()->format('D, d M Y — h:i A') }} (Nepal Time)
            </p>
        </div>
        <div class="footer">
            TrimTime Admin Notification — Do not reply to this email
        </div>
    </div>
</body>
</html>
