<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipientEmail = $_POST['email'];
    $subject = $_POST['subject'];
    $content = $_POST['content'];

    $headers = 'From: houda@gmail.com' . "\r\n";
    $headers .= 'Reply-To: houda@gmail.com' . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();

    if (mail($recipientEmail, $subject, $content, $headers)) {
        $message = 'success';
    } else {
        $message = 'error';
    }

    $redirectUrl = isset($_GET['redirect']) ? $_GET['redirect'] : './view_absences.php';
    header("Location: $redirectUrl?message=$message");
    exit();
}
?>