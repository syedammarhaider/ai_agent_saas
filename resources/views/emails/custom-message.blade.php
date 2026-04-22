<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>New Message - DS Technologies</title>
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; line-height: 1.6; color: #374151; margin: 0; padding: 20px; background: #f9fafb; }
        .container { max-width: 640px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 55%); padding: 48px 40px; text-align: center; }
        .header h1 { font-size: 32px; font-weight: 800; color: white; margin: 0 0 12px; }
        .header p { font-size: 18px; color: rgba(255,255,255,0.9); margin: 0; }
        .content { padding: 40px; }
        .message-box { background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; padding: 24px; margin-bottom: 24px; }
        .project-details { background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 20px; margin-top: 24px; }
        .footer { padding: 32px 40px; background: #f9fafb; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { margin: 0; color: #6b7280; font-size: 14px; }
        .footer a { color: #3b82f6; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 New Message</h1>
            <p>You have received a new message from DS Technologies</p>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $client_name }}</strong>,</p>
            
            <div class="message-box">
                <p>{{ $message_content }}</p>
            </div>
            
            @if($project_details)
            <div class="project-details">
                <h3>Current Project Details</h3>
                <p>{{ $project_details }}</p>
            </div>
            @endif
        </div>
        
        <div class="footer">
            <p>Best regards,<br>DS Technologies Team</p>
            <p style="margin-top: 16px;">
                <a href="mailto:{{ config('mail.from.address') }}" style="color: #3b82f6;">{{ config('mail.from.address') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
