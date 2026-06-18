<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Rate limiting
session_start();
$now = time();
if (isset($_SESSION['last_submit']) && ($now - $_SESSION['last_submit']) < 60) {
    http_response_code(429);
    echo json_encode(['error' => 'Please wait before submitting again']);
    exit;
}

// Collect and sanitize
$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$interest = htmlspecialchars(trim($_POST['interest'] ?? ''));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

if (!$name || !$email) {
    http_response_code(400);
    echo json_encode(['error' => 'Name and valid email are required']);
    exit;
}

// Resend API - key loaded from environment or .env file
$apiKey = getenv('RESEND_API_KEY') ?: '';
if (!$apiKey && file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
    $apiKey = $env['RESEND_API_KEY'] ?? '';
}

$payload = json_encode([
    'from' => 'KenyaNLP <noreply@kenyanlp.org>',
    'to' => ['info@kenyanlp.org'],
    'reply_to' => $email,
    'subject' => "KenyaNLP: $name - $interest",
    'html' => "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style='background: #3B6D11; padding: 24px 32px; border-radius: 12px 12px 0 0;'>
                <img src='https://kenyanlp.org/logo-light.png' alt='KenyaNLP' style='height: 40px; margin-bottom: 12px;' />
                <h1 style='color: white; margin: 0; font-size: 20px;'>New Contact Submission</h1>
            </div>
            <div style='background: #ffffff; border: 1px solid #e5e5e5; border-top: none; padding: 32px; border-radius: 0 0 12px 12px;'>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 12px 0; border-bottom: 1px solid #f0f0f0; color: #888; font-size: 13px; width: 120px;'>Name</td>
                        <td style='padding: 12px 0; border-bottom: 1px solid #f0f0f0; font-size: 15px; font-weight: 600;'>$name</td>
                    </tr>
                    <tr>
                        <td style='padding: 12px 0; border-bottom: 1px solid #f0f0f0; color: #888; font-size: 13px;'>Email</td>
                        <td style='padding: 12px 0; border-bottom: 1px solid #f0f0f0; font-size: 15px;'><a href='mailto:$email' style='color: #3B6D11;'>$email</a></td>
                    </tr>
                    <tr>
                        <td style='padding: 12px 0; border-bottom: 1px solid #f0f0f0; color: #888; font-size: 13px;'>Interest</td>
                        <td style='padding: 12px 0; border-bottom: 1px solid #f0f0f0; font-size: 15px;'>$interest</td>
                    </tr>
                </table>
                <div style='margin-top: 20px;'>
                    <p style='color: #888; font-size: 13px; margin: 0 0 8px 0;'>Message</p>
                    <div style='background: #f9f9f7; border-radius: 8px; padding: 16px; font-size: 14px; line-height: 1.7; color: #333;'>$message</div>
                </div>
                <div style='margin-top: 24px; padding-top: 16px; border-top: 1px solid #f0f0f0;'>
                    <p style='color: #888; font-size: 12px; margin: 0 0 8px 0;'>Sent from kenyanlp.org contact form</p>
                    <p style='margin: 0;'>
                        <a href='https://kenyanlp.org' style='color: #3B6D11; font-size: 12px; text-decoration: none; margin-right: 16px;'>kenyanlp.org</a>
                        <a href='https://www.linkedin.com/company/kenya-nlp/' style='color: #3B6D11; font-size: 12px; text-decoration: none;'>LinkedIn</a>
                    </p>
                </div>
            </div>
        </div>
    "
]);

$ch = curl_init('https://api.resend.com/emails');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json'
    ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$_SESSION['last_submit'] = $now;

if ($httpCode === 200) {
    echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send. Please email us directly at info@kenyanlp.org']);
}
