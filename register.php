<?php
include 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (
        !empty($full_name) &&
        !empty($email) &&
        !empty($password) &&
        !empty($confirm_password)
    ) {
        if ($password !== $confirm_password) {
            $error = "كلمتا المرور غير متطابقتين";
        } else {
            $check = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
            $check->execute([$email]);

            if ($check->rowCount() > 0) {
                $error = "البريد الإلكتروني مستخدم مسبقاً";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $role = $_POST['role'];
                $stmt = $pdo->prepare("
                    INSERT INTO users
                    (full_name, email, password, phone, address, role)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");

                $stmt->execute([
                $full_name,
                $email,
                $hashedPassword,
                $phone,
                $address,
                $role
            ]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['full_name'] = $full_name;
                $_SESSION['role'] = 'role';

                if ($role === 'admin') {
                    header("Location: admin/admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            }
        }
    } else {
        $error = "يرجى تعبئة جميع الحقول المطلوبة";
    }
}

include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>إنشاء حساب جديد</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>الاسم الكامل</label>
                <input type="text" name="full_name" required>
            </div>

            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>رقم الهاتف</label>
                <input type="text" name="phone">
            </div>

            <div class="form-group">
                <label>العنوان</label>
                <textarea name="address"></textarea>
            </div>

            <div class="form-group">
                <label>كلمة المرور</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>تأكيد كلمة المرور</label>
                <input type="password" name="confirm_password" required>
            </div>

            <div class="form-group">
                <label>نوع الحساب</label>

                <select name="role" required>
                    <option value="customer">عميل</option>
                    <option value="admin">أدمن</option>
                </select>
            </div>

            <button type="submit" class="btnSub auth-btn">إنشاء الحساب</button>
        </form>

        <p class="auth-link">
            لديك حساب بالفعل؟
            <a href="login.php">تسجيل الدخول</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>