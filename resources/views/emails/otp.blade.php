<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Inter, Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 30px 0;
        }
        .container {
            max-width: 480px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .header {
            background: #1a1a2e;
            padding: 32px 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            margin: 0;
            letter-spacing: 1px;
        }
        .header p {
            color: #a0a0b0;
            margin: 4px 0 0;
            font-size: 13px;
        }
        .body {
            padding: 40px;
        }
        .body p {
            color: #444;
            font-size: 15px;
            line-height: 1.6;
            margin: 0 0 20px;
        }
        .otp-box {
            background: #f8f8ff;
            border: 2px dashed #4f46e5;
            border-radius: 10px;
            text-align: center;
            padding: 24px;
            margin: 24px 0;
        }
        .otp-box .otp-label {
            color: #888;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
        }
        .otp-box .otp-code {
            font-size: 42px;
            font-weight: 700;
            letter-spacing: 10px;
            color: #1a1a2e;
        }
        .otp-box .otp-expiry {
            color: #e53e3e;
            font-size: 13px;
            margin-top: 10px;
        }
        .footer {
            background: #f8f8f8;
            padding: 20px 40px;
            text-align: center;
            color: #aaa;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✂ BarberTime</h1>
            <p>Premium Barber Booking Platform</p>
        </div>
        <div class="body">
            <p>Hello,</p>
            <p>We received a request to reset your BarberTime account password. Use the OTP below to proceed:</p>

            <div class="otp-box">
                <div class="otp-label">Your One-Time Password</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-expiry">⏱ Expires in 5 minutes</div>
            </div>

            <p>If you did not request a password reset, please ignore this email. Your account remains secure.</p>
            <p>— The BarberTime Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BarberTime. All rights reserved.
        </div>
    </div>
</body>
</html>
