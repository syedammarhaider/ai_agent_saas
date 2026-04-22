<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Log;

/**
 * ClientEmailService
 *
 * ✅ Uses PHP's native mail infrastructure via Swift/Symfony Mailer
 *    through Laravel's Mail facade with inline HTML — no Blade view
 *    dependency that can silently fail.
 *
 * ✅ All three methods (welcome, status-change, custom-message) build
 *    self-contained HTML strings so they work even if Blade views are
 *    missing or misconfigured.
 *
 * ✅ Every send is wrapped in try/catch with detailed logging so you
 *    can diagnose failures instantly.
 */
class ClientEmailService
{
    // ──────────────────────────────────────────────────────────────────────────
    // 1. WELCOME EMAIL  (sent immediately when a new client is created)
    // ──────────────────────────────────────────────────────────────────────────

    public function sendWelcomeEmail(Client $client): bool
    {
        $subject = '👋 Welcome to DS Technologies — We\'ve Received Your Inquiry';

        $projectHtml = $client->project_details
            ? '<p style="font-size:14px;color:#374151;margin:0 0 8px;font-weight:700;">Project Details:</p>
               <div style="background:#f3f4f6;border-radius:8px;padding:16px;font-size:13px;color:#374151;line-height:1.6;">'
                . nl2br(htmlspecialchars($client->project_details))
               . '</div>'
            : '';

        $html = $this->wrapHtml(
            title: 'Welcome to DS Technologies!',
            headerColor: '#4338ca',
            body: '
            <p style="font-size:15px;color:#374151;margin:0 0 16px;">
                Hi <strong>' . htmlspecialchars($client->name) . '</strong>,
            </p>
            <p style="font-size:14px;color:#6b7280;margin:0 0 16px;">
                Thank you for reaching out to <strong style="color:#4338ca;">DS Technologies Pvt. Limited</strong>.
                We have received your inquiry and our team will get back to you within
                <strong style="color:#7c3aed;">2 business hours</strong>.
            </p>
            <table style="width:100%;background:#f5f3ff;border:1px solid #e0e7ff;border-radius:10px;margin:20px 0;border-collapse:collapse;">
                <tr>
                    <td style="padding:12px 16px;font-size:12px;color:#7c3aed;font-weight:700;text-transform:uppercase;width:110px;">Name</td>
                    <td style="padding:12px 16px;font-size:14px;color:#1e1b4b;font-weight:600;">' . htmlspecialchars($client->name) . '</td>
                </tr>
                <tr style="border-top:1px solid #e0e7ff;">
                    <td style="padding:12px 16px;font-size:12px;color:#7c3aed;font-weight:700;text-transform:uppercase;">Email</td>
                    <td style="padding:12px 16px;font-size:14px;color:#4338ca;">' . htmlspecialchars($client->email) . '</td>
                </tr>
                ' . ($client->phone ? '<tr style="border-top:1px solid #e0e7ff;"><td style="padding:12px 16px;font-size:12px;color:#7c3aed;font-weight:700;text-transform:uppercase;">Phone</td><td style="padding:12px 16px;font-size:14px;color:#1e1b4b;">' . htmlspecialchars($client->phone) . '</td></tr>' : '') . '
            </table>
            ' . $projectHtml . '
            <p style="font-size:13px;color:#9ca3af;margin:20px 0 0;">
                Best regards,<br><strong>DS Technologies Team</strong>
            </p>'
        );

        return $this->send($client->email, $client->name, $subject, $html);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // 2. STATUS CHANGE EMAIL
    // ──────────────────────────────────────────────────────────────────────────

    public function sendStatusChangeEmail(Client $client, string $oldStatus, string $newStatus): bool
    {
        $map = [
            'in_progress' => [
                'subject'      => '🚀 Your Project is Now In Progress — DS Technologies',
                'title'        => 'Project In Progress',
                'headerColor'  => '#4F46E5',
                'emoji'        => '🚀',
                'message'      => 'Great news! Our team has started working on your project and is fully committed to delivering excellent results on time.',
            ],
            'completed' => [
                'subject'      => '✅ Your Project is Complete — DS Technologies',
                'title'        => 'Project Completed',
                'headerColor'  => '#059669',
                'emoji'        => '✅',
                'message'      => 'Congratulations! Your project has been successfully completed. It has been a pleasure working with you.',
            ],
            'cancelled' => [
                'subject'      => '❌ Project Status Update — DS Technologies',
                'title'        => 'Project Cancelled',
                'headerColor'  => '#DC2626',
                'emoji'        => '❌',
                'message'      => 'Your project has been cancelled. If you have any questions or would like to restart, please reply to this email.',
            ],
        ];

        $info         = $map[$newStatus] ?? $map['in_progress'];
        $oldLabel     = ucwords(str_replace('_', ' ', $oldStatus));
        $newLabel     = ucwords(str_replace('_', ' ', $newStatus));

        $projectHtml  = $client->project_details
            ? '<p style="font-size:14px;color:#374151;margin:16px 0 8px;font-weight:700;">Your Project Details:</p>
               <div style="background:#f3f4f6;border-radius:8px;padding:14px;font-size:13px;color:#374151;line-height:1.6;">'
                . nl2br(htmlspecialchars($client->project_details))
               . '</div>'
            : '';

        $html = $this->wrapHtml(
            title: $info['emoji'] . ' ' . $info['title'],
            headerColor: $info['headerColor'],
            body: '
            <p style="font-size:15px;color:#374151;margin:0 0 12px;">
                Hi <strong>' . htmlspecialchars($client->name) . '</strong>,
            </p>
            <p style="font-size:14px;color:#6b7280;margin:0 0 16px;">' . htmlspecialchars($info['message']) . '</p>
            <table style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;margin:16px 0;border-collapse:collapse;">
                <tr>
                    <td style="padding:12px 16px;font-size:12px;color:#6b7280;font-weight:700;text-transform:uppercase;width:130px;">Previous Status</td>
                    <td style="padding:12px 16px;font-size:14px;color:#374151;">' . $oldLabel . '</td>
                </tr>
                <tr style="border-top:1px solid #e5e7eb;">
                    <td style="padding:12px 16px;font-size:12px;color:#6b7280;font-weight:700;text-transform:uppercase;">New Status</td>
                    <td style="padding:12px 16px;">
                        <span style="display:inline-block;padding:4px 12px;border-radius:6px;background:' . $info['headerColor'] . ';color:white;font-size:13px;font-weight:700;">'
                            . $newLabel
                        . '</span>
                    </td>
                </tr>
            </table>
            ' . $projectHtml . '
            <p style="font-size:13px;color:#9ca3af;margin:20px 0 0;">
                Best regards,<br><strong>DS Technologies Team</strong>
            </p>'
        );

        return $this->send($client->email, $client->name, $info['subject'], $html);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // 3. CUSTOM MESSAGE EMAIL
    // ──────────────────────────────────────────────────────────────────────────

    public function sendCustomMessage(Client $client, string $messageContent): bool
    {
        $subject = 'Message from DS Technologies';

        $projectHtml = $client->project_details
            ? '<div style="background:#fef9c3;border:1px solid #fde047;border-radius:8px;padding:14px;margin-top:18px;">
                 <p style="font-size:12px;font-weight:700;color:#92400e;text-transform:uppercase;margin:0 0 8px;">Your Project Details</p>
                 <p style="font-size:13px;color:#374151;margin:0;line-height:1.6;">' . nl2br(htmlspecialchars($client->project_details)) . '</p>
               </div>'
            : '';

        $html = $this->wrapHtml(
            title: '📩 New Message',
            headerColor: '#4338ca',
            body: '
            <p style="font-size:15px;color:#374151;margin:0 0 16px;">
                Hi <strong>' . htmlspecialchars($client->name) . '</strong>,
            </p>
            <div style="background:#f3f4f6;border-left:4px solid #4338ca;border-radius:0 8px 8px 0;padding:18px;font-size:14px;color:#374151;line-height:1.7;">
                ' . nl2br(htmlspecialchars($messageContent)) . '
            </div>
            ' . $projectHtml . '
            <p style="font-size:13px;color:#9ca3af;margin:20px 0 0;">
                Best regards,<br><strong>DS Technologies Team</strong>
            </p>'
        );

        // Update last contacted
        $client->update(['last_contacted_at' => now()]);

        return $this->send($client->email, $client->name, $subject, $html);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Sends the email using Laravel's mailer (configured via .env MAIL_* vars).
     * Falls back gracefully and logs every failure with full details.
     */
    private function send(string $toEmail, string $toName, string $subject, string $htmlBody): bool
    {
        try {
            // Clean email by removing platform suffixes
            $toEmail = $this->cleanEmail($toEmail);
            
            // Validate email before attempting send
            if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                Log::error("ClientEmailService: invalid email address '{$toEmail}'");
                return false;
            }

            \Illuminate\Support\Facades\Mail::html($htmlBody, function ($message) use ($toEmail, $toName, $subject) {
                $message->to($toEmail, $toName)
                        ->subject($subject)
                        ->from(
                            config('mail.from.address', env('MAIL_FROM_ADDRESS', env('EMAIL_USER', 'noreply@dstechnologies.com'))),
                            config('mail.from.name',    'DS Technologies')
                        );
            });

            Log::info("ClientEmailService: email sent ✅ to {$toEmail} | subject: {$subject}");
            return true;

        } catch (\Exception $e) {
            Log::error("ClientEmailService: email FAILED ❌ to {$toEmail} | {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Cleans email address by removing platform suffixes
     */
    private function cleanEmail(string $email): string
    {
        // Remove platform suffixes like -whatsapp, -slack, etc.
        $email = preg_replace('/(@.+)-(whatsapp|slack|api|web)$/', '$1', $email);
        return $email;
    }

    /**
     * Wraps body HTML in a consistent branded email shell.
     */
    private function wrapHtml(string $title, string $headerColor, string $body): string
    {
        $year = date('Y');
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>{$title}</title>
</head>
<body style="margin:0;padding:0;background:#f8fafc;font-family:'Segoe UI',system-ui,Arial,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;">
  <tr><td style="padding:40px 20px;" align="center">
    <table role="presentation" width="100%" style="max-width:600px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 6px rgba(0,0,0,0.07);">

      <!-- HEADER -->
      <tr><td style="background:{$headerColor};padding:36px 40px;text-align:center;">
        <div style="display:inline-block;background:rgba(255,255,255,0.2);border-radius:8px;padding:8px 20px;margin-bottom:16px;">
          <span style="font-size:20px;font-weight:900;color:#fff;">DS</span>
          <span style="display:inline-block;width:1px;height:18px;background:rgba(255,255,255,0.4);vertical-align:middle;margin:0 10px;"></span>
          <span style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.85);letter-spacing:0.5px;">Technologies</span>
        </div>
        <h1 style="font-size:26px;font-weight:800;color:#ffffff;margin:0;">{$title}</h1>
      </td></tr>

      <!-- BODY -->
      <tr><td style="padding:36px 40px;">
        {$body}
      </td></tr>

      <!-- FOOTER -->
      <tr><td style="background:#1e1b4b;padding:28px 40px;text-align:center;border-radius:0 0 16px 16px;">
        <p style="margin:0;color:#c7d2fe;font-weight:700;font-size:14px;">DS Technologies Pvt. Limited</p>
        <p style="margin:8px 0 0;color:#6366f1;font-size:12px;">&copy; {$year} DS Technologies. All rights reserved.</p>
      </td></tr>

    </table>
  </td></tr>
</table>
</body>
</html>
HTML;
    }
}