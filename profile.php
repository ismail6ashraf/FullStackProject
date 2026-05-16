<?php
include 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $updateStmt = $pdo->prepare("
        UPDATE users
        SET full_name = ?, phone = ?, address = ?
        WHERE user_id = ?
    ");

    $updateStmt->execute([
        $full_name,
        $phone,
        $address,
        $user_id
    ]);

    $_SESSION['full_name'] = $full_name;

    $success = "تم تحديث بياناتك بنجاح";
}

$stmt = $pdo->prepare("
    SELECT *
    FROM users
    WHERE user_id = ?
");

$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

$orderStmt = $pdo->prepare("
    SELECT *
    FROM orders
    WHERE user_id = ?
    ORDER BY order_id DESC
");

$orderStmt->execute([$user_id]);

$orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<section class="profile-page">

    <div class="profile-container">

        <div class="profile-header">

            <div class="profile-avatar">
                <i class="fa-solid fa-user"></i>
            </div>

            <h1>
                <?php echo htmlspecialchars($user['full_name']); ?>
            </h1>

            <p>
                حساب العميل
            </p>

        </div>

        <div class="profile-info">

            <div class="profile-card">

                <h3>الاسم الكامل</h3>

                <p>
                    <?php echo htmlspecialchars($user['full_name']); ?>
                </p>

            </div>

            <div class="profile-card">

                <h3>البريد الإلكتروني</h3>

                <p>
                    <?php echo htmlspecialchars($user['email']); ?>
                </p>

            </div>

            <div class="profile-card">

                <h3>رقم الهاتف</h3>

                <p>
                    <?php echo htmlspecialchars($user['phone']); ?>
                </p>

            </div>

            <div class="profile-card">

                <h3>العنوان</h3>

                <p>
                    <?php echo htmlspecialchars($user['address']); ?>
                </p>

            </div>

            <div class="profile-card">

                <h3>نوع الحساب</h3>

                <p>
                    <?php echo htmlspecialchars($user['role']); ?>
                </p>

            </div>

            <div class="profile-card">

                <h3>تاريخ إنشاء الحساب</h3>

                <p>
                    <?php echo htmlspecialchars($user['created_at']); ?>
                </p>

            </div>

        </div>

    </div>

</section>

<div class="form-container">

    <h2>تعديل بيانات الحساب</h2>

    <?php if (!empty($success)): ?>
        <div class="success-message">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label>الاسم الكامل</label>
            <input
                type="text"
                name="full_name"
                value="<?php echo htmlspecialchars($user['full_name']); ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>رقم الهاتف</label>
            <input
                type="text"
                name="phone"
                value="<?php echo htmlspecialchars($user['phone']); ?>"
            >
        </div>

        <div class="form-group">
            <label>العنوان</label>
            <textarea name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>
        </div>

        <button type="submit" class="btnSub auth-btn">
            حفظ التعديلات
        </button>

    </form>

</div>

<section class="profile-orders">

    <h2 class="section-title">طلباتي السابقة</h2>

    <div class="table-wrapper">

        <table class="admin-table">

            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>التاريخ</th>
                    <th>الإجمالي</th>
                    <th>الحالة</th>
                    <th>عنوان الشحن</th>
                </tr>
            </thead>

            <tbody>

                <?php if (count($orders) > 0): ?>

                    <?php foreach ($orders as $order): ?>

                        <tr>
                            <td><?php echo $order['order_id']; ?></td>

                            <td><?php echo $order['order_date']; ?></td>

                            <td>
                                ₪ <?php echo number_format($order['total_amount'], 2); ?>
                            </td>

                            <td>
                                <span class="status-badge status-<?php echo $order['status']; ?>">
                                    <?php echo $order['status']; ?>
                                </span>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($order['shipping_address']); ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5">
                            لا توجد طلبات سابقة
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</section>

<?php include 'includes/footer.php'; ?>