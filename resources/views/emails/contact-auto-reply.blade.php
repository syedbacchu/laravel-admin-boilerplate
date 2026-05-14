<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting Us</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">Thank You for Contacting Us!</h1>
        </div>

        <div style="background: #f9f9f9; padding: 30px; border: 1px solid #e0e0e0; border-radius: 0 0 10px 10px;">
            <p style="font-size: 16px; margin-bottom: 20px;">
                Dear <strong>{{ $name }}</strong>,
            </p>

            <p style="font-size: 16px; margin-bottom: 20px;">
                Thank you for reaching out to us! We have successfully received your inquiry regarding:
            </p>

            <div style="background: #ffffff; padding: 20px; border-left: 4px solid #667eea; margin-bottom: 20px; border-radius: 4px;">
                <p style="font-size: 14px; color: #666; margin: 0;"><strong>Subject:</strong> {{ $subject }}</p>
                <p style="font-size: 14px; color: #333; margin: 10px 0 0 0;"><strong>Your Message:</strong></p>
                <p style="font-size: 14px; color: #333; margin: 5px 0 0 0;">{!! $userMessage !!}</p>
            </div>

            <p style="font-size: 16px; margin-bottom: 20px;">
                We wanted to let you know that we've received your message and our team will review it shortly.
            </p>

            <div style="background: #e8f5e9; padding: 15px; border-radius: 5px; border: 1px solid #c8e6c9;">
                <p style="font-size: 14px; color: #2e7d32; margin: 0; font-weight: 600;">
                    ✅ Message Received Successfully<br>
                    ✅ Our Team Will Review Your Inquiry<br>
                    ✅ We'll Get Back To You Soon
                </p>
            </div>

            <p style="font-size: 16px; margin-bottom: 20px;">
                While we review your message, feel free to explore more about our services and offerings. If you have any urgent questions, please don't hesitate to reach us through our phone or email.
            </p>

            <p style="font-size: 16px; margin-bottom: 30px;">
                We appreciate your interest and look forward to connecting with you!
            </p>

            <p style="font-size: 16px; margin-bottom: 30px;">
                Best regards,<br>
                <strong>The Team</strong>
            </p>

            <div style="text-align: center; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                <p style="font-size: 12px; color: #999; margin: 0;">
                    This is an automated confirmation email sent on {{ $date }}.<br>
                    Reference ID: {{ $reference_id }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>