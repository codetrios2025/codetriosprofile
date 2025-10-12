<?php
header("Content-Type: application/json");
require __DIR__ . '/db.php';

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // make sure PHPMailer is installed via Composer

$adminEmail = "mr.hitesh7@gmail.com";
$secretKey = "6Ldc9-YrAAAAAA09FaRcduF5PQSvFMhAEvHfrSMQ";

// SMTP credentials
$smtpHost = "smtp.gmail.com";           // your SMTP host
$smtpUsername = "codetrio2025@gmail.com"; // your SMTP email
$smtpPassword = "wabx badx dgjf gpks";    // your SMTP password or Gmail app password
$smtpPort = 587;                         // usually 587 for TLS
function verifyRecaptcha($secret, $response) {
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $secret,
        'response' => $response
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2); // 2 seconds max
    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result);
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $message = trim($_POST["message"] ?? '');
    $recaptcha = $_POST["g-recaptcha-response"] ?? '';

    $errors = [];

    // Validation
    if (empty($name)) $errors[] = "Please enter your name.";
    if (empty($email)) $errors[] = "Please enter your email.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (empty($message)) $errors[] = "Please enter your message.";
    if (empty($recaptcha)) $errors[] = "Please verify reCAPTCHA.";

    // reCAPTCHA verify
    if (empty($errors)) {
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptcha}");
        $captchaSuccess = json_decode($verify);
        if (!$captchaSuccess->success) $errors[] = "reCAPTCHA verification failed.";
        // $captchaSuccess = verifyRecaptcha($secretKey, $recaptcha);
        // if (!$captchaSuccess->success) {
        //     $errors[] = "reCAPTCHA verification failed.";
        // }
    }

    if (!empty($errors)) {
        echo json_encode(["status" => "error", "message" => implode("<br>", $errors)]);
        exit;
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("sss", $name, $email, $message);
    if ($stmt->execute()) {
        // Prepare email message
        $subject = "New Contact Form Submission";
        $body = "
        <h3>New Message Received</h3>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
        <p><small>Sent on " . date("Y-m-d H:i:s") . "</small></p>
        ";

        // Send email via PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUsername;
            $mail->Password   = $smtpPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port       = $smtpPort;

            // Email headers and content
            $mail->setFrom($smtpUsername, "Website Contact Form");
            $mail->addAddress($adminEmail);
            $mail->addReplyTo($email, $name);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            echo json_encode(["status" => "success", "message" => "Message saved and email notification sent successfully."]);
        } catch (Exception $e) {
            echo json_encode(["status" => "success", "message" => "Message saved, but failed to send email. Mailer Error: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save message."]);
    }

    $stmt->close();
    $conn->close();
}
?>
