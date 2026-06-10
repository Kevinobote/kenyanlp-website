<?php
header('Content-Type: application/json');

// CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Rate limiting via session
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

// Email config — update these
$to = 'info@kenyanlp.org';
$subject = "KenyaNLP Contact: $interest";
$body = "Name: $name\nEmail: $email\nInterest: $interest\n\nMessage:\n$message";
$headers = "From: noreply@kenyanlp.org\r\nReply-To: $email\r\n";

$sent = mail($to, $subject, $body, $headers);

$_SESSION['last_submit'] = $now;

if ($sent) {
    echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send. Please email us directly.']);
}
