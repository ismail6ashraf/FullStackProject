<?php
include 'includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<h2 class="section-title">سلة التسوق 🛒</h2>

<div class="cart-container">

    <?php if (!empty($cart)): ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>اسم المنتج</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>الإجمالي</th>
                    <th>حذف</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($cart as $id => $item): ?>
                    <?php
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <img
                                src="<?php echo htmlspecialchars($item['image']); ?>"
                                width="70"
                                height="70"
                                style="object-fit: cover; border-radius: 8px;"
                            >
                        </td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>₪ <?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <div class="quantity-box">

                                <a href="actions/update_quantity.php?id=<?php echo $id; ?>&action=decrease"  class="qty-btn"> - </a>

                                <span class="qty-number">
                                    <?php echo $item['quantity']; ?>
                                </span>

                                <a href="actions/update_quantity.php?id=<?php echo $id; ?>&action=increase"
                                    class="qty-btn"> + </a>

                            </div>

                        </td>
                        <td>₪ <?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <a href="actions/remove_cart.php?id=<?php echo $id; ?>" class="delete-btn">
                                حذف
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-summary">
            <h3 class="total-price">
                المجموع الكلي: ₪ <?php echo number_format($total, 2); ?>
            </h3>
            
            <form action="actions/checkout.php" method="POST">
                <button type="submit" class="btn checkout-btn">
                    إتمام الشراء
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="empty-cart">
            <h3>السلة فارغة حالياً</h3>
            <p>لم تقم بإضافة أي منتجات بعد.</p>
            <a href="products.php" class="btnSub">تصفح المنتجات</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>