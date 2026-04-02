<!DOCTYPE html>
<html>
<head>
    <title>Password Reset - AI Agent SaaS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #3b82f6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #666;
            margin-top: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🤖 AI Agent SaaS</h1>
        <p>Password Reset Request</p>
    </div>
    
    <div class="content">
        <h2>Hello!</h2>
        <p>You requested to reset your password for your AI Agent SaaS account.</p>
        <p>Click the button below to reset your password:</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/reset-password?token=' . $token) }}" class="button">
                Reset Password
            </a>
        </div>
        
        <p>This link will expire in 60 minutes.</p>
        <p>If you didn't request this password reset, you can safely ignore this email.</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} AI Agent SaaS. All rights reserved.</p>
        <p>This is an automated message, please do not reply.</p>
    </div>
</body>
</html>
