<?php
include '../config/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    $stmt = $pdo->prepare("
        INSERT INTO contacts
        (name, email, subject, message)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $name,
        $email,
        $subject,
        $message
    ]);

    echo "
    <script>
    alert('تم إرسال رسالتك بنجاح ✅');
    window.location.href='../contact.php';
    </script>
    ";
}