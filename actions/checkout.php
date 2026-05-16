<?php
include '../config/db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: ../cart.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$total = 0;

foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

$userStmt = $pdo->prepare("SELECT address FROM users WHERE user_id = ?");
$userStmt->execute([$user_id]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

$shipping_address = !empty($user['address'])
    ? $user['address']
    : 'Gaza - Palestine';

$stmt = $pdo->prepare("
    INSERT INTO orders
    (user_id, total_amount, status, shipping_address)
    VALUES (?, ?, 'pending', ?)
");

$stmt->execute([
    $user_id,
    $total,
    $shipping_address
]);

$order_id = $pdo->lastInsertId();

foreach ($_SESSION['cart'] as $item) {
    $stmt = $pdo->prepare("
        INSERT INTO order_items
        (order_id, product_id, quantity, unit_price)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $order_id,
        $item['id'],
        $item['quantity'],
        $item['price']
    ]);
}

unset($_SESSION['cart']);

echo "
<script>
alert('تم استلام طلبك بنجاح ✅ سيتم التواصل معك قريبًا');
window.location.href='../index.php';
</script>
";
exit;