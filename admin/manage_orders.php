<?php
include '../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: ../login.php");
    exit;
}

/* Update Order Status */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {

    $order_id = (int) $_POST['order_id'];
    $status = $_POST['status'];

    $allowedStatuses = [
        'pending',
        'paid',
        'shipped',
        'delivered',
        'cancelled'
    ];

    if (in_array($status, $allowedStatuses)) {
        $stmt = $pdo->prepare("
            UPDATE orders
            SET status = ?
            WHERE order_id = ?
        ");

        $stmt->execute([$status, $order_id]);
    }

    header("Location: manage_orders.php");
    exit;
}

/* Fetch Orders */
$stmt = $pdo->query("
    SELECT orders.*, users.full_name
    FROM orders
    LEFT JOIN users
    ON orders.user_id = users.user_id
    ORDER BY orders.order_id DESC
");

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<section class="admin-page">

    <div class="admin-container">

        <h1 class="admin-title">إدارة الطلبات</h1>

        <div class="table-wrapper">

            <table class="admin-table">

                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>اسم العميل</th>
                        <th>التاريخ</th>
                        <th>الإجمالي</th>
                        <th>العنوان</th>
                        <th>الحالة</th>
                        <th>تحديث</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($orders) > 0): ?>

                        <?php foreach ($orders as $order): ?>

                            <tr>
                                <td><?php echo $order['order_id']; ?></td>

                                <td>
                                    <?php echo htmlspecialchars($order['full_name'] ?? 'غير معروف'); ?>
                                </td>

                                <td><?php echo $order['order_date']; ?></td>

                                <td>
                                    ₪ <?php echo number_format($order['total_amount'], 2); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($order['shipping_address']); ?>
                                </td>

                                <td>
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                        <?php echo $order['status']; ?>
                                    </span>
                                </td>

                                <td>
                                    <form method="POST" class="status-form">
                                        <input
                                            type="hidden"
                                            name="order_id"
                                            value="<?php echo $order['order_id']; ?>"
                                        >

                                        <select name="status">
                                            <option value="pending" <?php if ($order['status'] === 'pending') echo 'selected'; ?>>
                                                Pending
                                            </option>

                                            <option value="paid" <?php if ($order['status'] === 'paid') echo 'selected'; ?>>
                                                Paid
                                            </option>

                                            <option value="shipped" <?php if ($order['status'] === 'shipped') echo 'selected'; ?>>
                                                Shipped
                                            </option>

                                            <option value="delivered" <?php if ($order['status'] === 'delivered') echo 'selected'; ?>>
                                                Delivered
                                            </option>

                                            <option value="cancelled" <?php if ($order['status'] === 'cancelled') echo 'selected'; ?>>
                                                Cancelled
                                            </option>
                                        </select>

                                        <button type="submit" class="small-btn">
                                            حفظ
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7">لا توجد طلبات حالياً</td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</section>

<?php include '../includes/footer.php'; ?>