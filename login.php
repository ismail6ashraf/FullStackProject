<?php
include 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin/admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "البريد الإلكتروني أو كلمة المرور غير صحيحة";
        }
    } else {
        $error = "يرجى تعبئة جميع الحقول";
    }
}

include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>تسجيل الدخول</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>كلمة المرور</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btnSub auth-btn">دخول</button>
        </form>

        <p class="auth-link">
            لا تملك حساب؟
            <a href="register.php">إنشاء حساب جديد</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>