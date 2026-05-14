<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">✅ Test Email Successful!</h1>
        </div>

        <div style="background: #f9f9f9; padding: 30px; border: 1px solid #e0e0e0; border-radius: 0 0 10px 10px;">
            <p style="font-size: 16px; margin-bottom: 20px;">
                Congratulations! Your mail configuration is working perfectly.
            </p>

            <div style="background: #ffffff; padding: 20px; border-left: 4px solid #667eea; margin-bottom: 20px; border-radius: 4px;">
                <p style="font-size: 14px; color: #666; margin: 0;"><strong>Site Name:</strong> {{ $site_name ?? 'Your Application' }}</p>
                <p style="font-size: 14px; color: #666; margin: 10px 0 0 0;"><strong>Test Time:</strong> {{ $test_time ?? now()->format('Y-m-d H:i:s') }}</p>
            </div>

            <p style="font-size: 14px; color: #666; margin-bottom: 20px;">
                {{ $test_message }}
            </p>

            <div style="background: #e8f5e9; padding: 15px; border-radius: 5px; border: 1px solid #c8e6c9;">
                <p style="font-size: 14px; color: #2e7d32; margin: 0; font-weight: 600;">
                    ✓ SMTP Settings: Configured<br>
                    ✓ Email Delivery: Working<br>
                    ✓ Admin Panel: Connected
                </p>
            </div>

            <p style="font-size: 16px; margin-bottom: 20px;">
                Your mail settings are ready to send emails to your users!
            </p>

            <div style="text-align: center; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                <p style="font-size: 12px; color: #999; margin: 0;">
                    This is an automated test email sent from your admin panel.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
