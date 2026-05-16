<?php
include 'config/db.php';

$query = $pdo->query("SELECT * FROM products LIMIT 5");
$products = $query->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<section class="hero">
    <div class="hero-content">
        <h1>تسوق أحدث الأجهزة والإلكترونيات</h1>
        <p>عروض حصرية وجودة مضمونة - توصيل سريع وأسعار تنافسية</p>
        <a href="products.php" class="btn hero-btn" id="btn">تسوق الآن ←</a>
    </div>
</section>

<section class="home-products">
    <h2 class="section-title">⭐ منتجات مميزة</h2>

    <div class="products-container">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img
                    src="<?php echo htmlspecialchars($product['image_url']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                    class="product-image">

                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="price">
                        ₪ <?php echo number_format($product['price'], 0); ?>
                    </p>
                    <div class="product-buttons">
                        <a
                            href="product-detail.php?id=<?php echo $product['product_id']; ?>"
                            class="btn details-btn">
                            Details
                        </a>

                        <a
                            href="actions/add_to_cart.php?id=<?php echo $product['product_id']; ?>"
                            class="btn add-cart-btn">
                            Add To Cart
                        </a>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>