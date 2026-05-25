<h2>🎉 Congratulations! Your BarberTime Partner Account is Approved!</h2>

<p>Hello {{ $barberShop->owner_name }},</p>

<p>We're thrilled to let you know that <strong>{{ $barberShop->name }}</strong> has been approved as a BarberTime partner!</p>

<p style="margin: 20px 0; padding: 15px; background-color: #c8e6c9; border-left: 4px solid #4caf50; border-radius: 4px;">
    ✅ Your shop is now live on our platform and you can start receiving online bookings from customers!
</p>

<h3>Your Login Credentials</h3>
<table style="border-collapse: collapse; width: 100%; max-width: 450px;">
    <tr style="background-color: #f5f5f5;">
        <td style="padding: 12px; border: 1px solid #ddd; font-weight: bold; width: 150px;">Email (Username)</td>
        <td style="padding: 12px; border: 1px solid #ddd; background-color: #fff9e6; font-weight: bold;">{{ $barberShop->email }}</td>
    </tr>
    <tr>
        <td style="padding: 12px; border: 1px solid #ddd; font-weight: bold;">Temporary Password (4-digit PIN)</td>
        <td style="padding: 12px; border: 1px solid #ddd; background-color: #fff3e0; font-weight: bold; font-size: 18px; letter-spacing: 2px;">{{ $plainPin }}</td>
    </tr>
</table>

<p style="margin-top: 20px; padding: 12px; background-color: #ffebee; border-left: 4px solid #f44336; border-radius: 4px;">
    <strong>⚠️ Important:</strong> Please change your password immediately after your first login for security.
</p>

<h3>Next Steps:</h3>
<ol style="line-height: 2;">
    <li>Visit <a href="{{ url('/login') }}" style="color: #ff6b00; text-decoration: none;"><strong>{{ url('/login') }}</strong></a> to log in</li>
    <li>Use the credentials above (email and 4-digit PIN)</li>
    <li>Change your password to something secure</li>
    <li>Set up your barbers and services in the dashboard</li>
    <li>Start managing bookings!</li>
</ol>

@if($barberShop->admin_remarks && $barberShop->admin_remarks !== '')
<p style="margin-top: 20px; padding: 12px; background-color: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
    <strong>Admin Notes:</strong> {{ $barberShop->admin_remarks }}
</p>
@endif

<p style="margin-top: 30px;">Need help? Contact us at <a href="mailto:support@trimtime.com.np">support@trimtime.com.np</a></p>

<p>Welcome aboard!<br><strong>— The BarberTime Team</strong></p>
