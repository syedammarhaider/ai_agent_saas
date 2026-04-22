<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Project Status Update - DS Technologies</title>
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; line-height: 1.6; color: #374151; margin: 0; padding: 20px; background: #f9fafb; }
        .container { max-width: 640px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, {{ $status_color }} 0%, {{ $status_color }} 100%); padding: 48px 40px; text-align: center; }
        .header h1 { font-size: 32px; font-weight: 800; color: white; margin: 0 0 12px; }
        .content { padding: 40px; }
        .status-badge { display: inline-block; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600; margin-bottom: 24px; }
        .project-details { background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-top: 24px; }
        .footer { padding: 32px 40px; background: #f9fafb; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { margin: 0; color: #6b7280; font-size: 14px; }
        .footer a { color: #3b82f6; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $status_title }}</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $client_name }}</strong>,</p>
            
            <p>Your project status has been updated from <strong>{{ $old_status }}</strong> to <strong>{{ $new_status }}</strong>.</p>
            
            <div class="status-badge" style="background-color: {{ $status_color }}; color: white;">
                {{ $status_title }}
            </div>
            
            @if($project_details)
            <div class="project-details">
                <h3>Project Details</h3>
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
