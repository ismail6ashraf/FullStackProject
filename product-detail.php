<?php
include 'config/db.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$product_id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM products
    WHERE product_id = ?
");

$stmt->execute([$product_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: products.php");
    exit;
}

include 'includes/header.php';
?>

<section class="product-details-page">

    <div class="product-details-container">

        <div class="product-details-image">

            <img
                src="<?php echo htmlspecialchars($product['image_url']); ?>"
                alt="<?php echo htmlspecialchars($product['name']); ?>"
            >

        </div>

        <div class="product-details-content">

            <h1>
                <?php echo htmlspecialchars($product['name']); ?>
            </h1>

            <p class="product-details-price">
                ₪ <?php echo number_format($product['price'], 2); ?>
            </p>

            <p class="product-details-description">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </p>

            <p class="product-stock">
                الكمية المتوفرة:
                <?php echo $product['stock_quantity']; ?>
            </p>

            <div class="product-detail-buttons">

                <a
                    href="actions/add_to_cart.php?id=<?php echo $product['product_id']; ?>"
                    class="btn"
                >
                    أضف إلى السلة
                </a>

                <a href="products.php" class="btn secondary-btn">
                    العودة للمنتجات
                </a>

            </div>

        </div>

    </div>

</section>

<?php include 'includes/footer.php'; ?>