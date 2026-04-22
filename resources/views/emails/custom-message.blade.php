<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>New Message - DS Technologies</title>
    <style>
        body { font-family:'Segoe UI',system-ui,sans-serif; line-height:1.6; color:#374151; margin:0; padding:20px; background:#f9fafb; }
        .email-container { max-width:640px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.10); border-top:3px solid; border-image:linear-gradient(90deg,#4F46E5,#7C3AED,#4F46E5) 1; }

        .email-header { padding:48px 40px; text-align:center; background:linear-gradient(135deg,#1e1b4b,#4338ca 55%,#6366F1); position:relative; overflow:hidden; }
        .email-header::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse at 70% 50%,rgba(255,255,255,.12),transparent 60%); }
        .email-header h1 { font-size:30px; font-weight:800; color:white; margin:0 0 8px; letter-spacing:-.5px; position:relative; }
        .email-header p  { font-size:15px; color:rgba(255,255,255,.8); margin:0; position:relative; }

        .email-content { padding:36px 40px; }
        .email-greeting { font-size:15px; color:#374151; margin-bottom:24px; }
        .email-greeting strong { color:#111827; }

        .email-message-box {
            background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:20px 24px; margin-bottom:8px;
            border-left:3px solid #6366F1; font-size:14px; color:#374151; line-height:1.75;
        }

        .email-project-details {
            background:#fffbeb; border:1px solid #FCD34D; border-radius:12px; padding:20px 24px; margin-top:20px;
            border-left:3px solid #F59E0B;
        }
        .email-project-details h3 { font-size:13px; text-transform:uppercase; letter-spacing:.8px; color:#92400E; margin:0 0 12px; font-weight:700; }
        .email-project-details p  { font-size:14px; color:#78350F; margin:0; line-height:1.7; }

        .email-footer { padding:28px 40px; background:#f9fafb; text-align:center; border-top:1px solid #E5E7EB; }
        .email-footer p { margin:0; color:#9CA3AF; font-size:13px; line-height:1.8; }
        .email-footer a { color:#6366F1; text-decoration:none; font-weight:500; }
        .email-footer .brand { font-size:15px; font-weight:800; color:#1F2937; letter-spacing:-.3px; }

        @media (max-width:600px) {
            .email-header, .email-content, .email-footer { padding-left:24px; padding-right:24px; }
            .email-header h1 { font-size:24px; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📧 New Message</h1>
            <p>You have received a new message from DS Technologies</p>
        </div>

        <div class="email-content">
            <p class="email-greeting">Hello <strong>{{ $client_name }}</strong>,</p>

            <div class="email-message-box">
                {{ $message_content }}
            </div>

            @if($project_details)
            <div class="email-project-details">
                <h3>Current Project Details</h3>
                <p>{{ $project_details }}</p>
            </div>
            @endif
        </div>

        <div class="email-footer">
            <p class="brand">NexusAI</p>
            <p>Best regards,<br>DS Technologies Team</p>
            <p style="margin-top:12px;">
                <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
