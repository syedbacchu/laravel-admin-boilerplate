<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Your Contact Message</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">Thank You for Contacting Us</h1>
        </div>

        <div style="background: #f9f9f9; padding: 30px; border: 1px solid #e0e0e0; border-radius: 0 0 10px 10px;">
            <p style="font-size: 16px; margin-bottom: 20px;">
                Dear <strong>{{ $contact->name }}</strong>,
            </p>

            <p style="font-size: 16px; margin-bottom: 20px;">
                Thank you for reaching out to us. We have received your message regarding:
            </p>

            <div style="background: #ffffff; padding: 20px; border-left: 4px solid #667eea; margin-bottom: 20px; border-radius: 4px;">
                <p style="font-size: 14px; color: #666; margin: 0;"><strong>Subject:</strong> {{ $contact->subject }}</p>
                <p style="font-size: 14px; color: #666; margin: 10px 0 0 0;"><strong>Your Message:</strong></p>
                <p style="font-size: 14px; color: #333; margin: 5px 0 0 0;">{{ $contact->message }}</p>
            </div>

            <p style="font-size: 16px; margin-bottom: 20px;">
                Here is our response to your inquiry:
            </p>

            <div style="background: #ffffff; padding: 20px; border-left: 4px solid #764ba2; margin-bottom: 20px; border-radius: 4px;">
                <p style="font-size: 14px; color: #333; margin: 0; white-space: pre-wrap;">{{ $reply_message }}</p>
            </div>

            <p style="font-size: 16px; margin-bottom: 20px;">
                If you have any further questions or need additional assistance, please don't hesitate to contact us again.
            </p>

            <p style="font-size: 16px; margin-bottom: 30px;">
                Best regards,<br>
                <strong>The Team</strong>
            </p>

            <div style="text-align: center; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                <p style="font-size: 12px; color: #999; margin: 0;">
                    This email was sent in response to your contact form submission on {{ $contact->created_at->format('F j, Y') }}.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
