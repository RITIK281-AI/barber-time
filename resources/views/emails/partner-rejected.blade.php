<h2>Update on Your TrimTime Partner Application</h2>

<p>Hello {{ $barberShop->owner_name }},</p>

<p>Thank you for your interest in becoming a TrimTime partner. We have carefully reviewed your application for <strong>{{ $barberShop->name }}</strong>.</p>

<p style="margin: 20px 0; padding: 15px; background-color: #fef3cd; border-left: 4px solid #ff9800; border-radius: 4px;">
    <strong>We regret to inform you that we are unable to approve your partnership application at this time.</strong>
</p>

@if($reason)
<h3>Reason for Rejection:</h3>
<p style="padding: 12px; background-color: #f5f5f5; border-radius: 4px;">
    {{ $reason }}
</p>
@endif

<h3>What You Can Do:</h3>
<ul style="line-height: 1.8;">
    <li>Review the feedback provided above</li>
    <li>Make necessary improvements to your shop or application</li>
    <li>Feel free to reapply in the future</li>
</ul>

<p style="margin-top: 20px;">If you have any questions about this decision or would like to discuss your application further, please don't hesitate to contact us.</p>

<p><strong>Contact Us:</strong> <a href="mailto:support@trimtime.com.np">support@trimtime.com.np</a></p>

<p>We appreciate your understanding and hope to work with you in the future.<br>— The TrimTime Team</p>
