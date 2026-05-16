<?php
session_start();

include '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$product_id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT * FROM products
    WHERE product_id = ?
");

$stmt->execute([$product_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$product_id])) {

    $_SESSION['cart'][$product_id]['quantity']++;

} else {

    $_SESSION['cart'][$product_id] = [
        'id' => $product['product_id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'image' => $product['image_url'],
        'quantity' => 1
    ];
}

/* يرجعك لنفس الصفحة */
header("Location: " . $_SERVER['HTTP_REFERER']);

exit;
?>