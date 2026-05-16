<?php
include 'config/db.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? '';

$sql = "
    SELECT products.*, categories.name AS category_name
    FROM products
    LEFT JOIN categories ON products.category_id = categories.category_id
    WHERE 1
";

$params = [];

if (!empty($search)) {
    $sql .= " AND products.name LIKE ?";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $sql .= " AND products.category_id = ?";
    $params[] = $category;
}

if ($sort === 'low') {
    $sql .= " ORDER BY products.price ASC";
} elseif ($sort === 'high') {
    $sql .= " ORDER BY products.price DESC";
} else {
    $sql .= " ORDER BY products.product_id DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$catStmt = $pdo->query("SELECT * FROM categories");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<h2 class="section-title">All Products</h2>

<form method="GET" class="filter-form">
    <input 
        type="text" 
        name="search" 
        placeholder="Search products..."
        value="<?php echo htmlspecialchars($search); ?>"
    >

    <select name="category">
        <option value="">All Categories</option>

        <?php foreach ($categories as $cat): ?>
            <option 
                value="<?php echo $cat['category_id']; ?>"
                <?php if ($category == $cat['category_id']) echo 'selected'; ?>
            >
                <?php echo htmlspecialchars($cat['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="sort">
        <option value="">Default Sorting</option>
        <option value="low" <?php if ($sort === 'low') echo 'selected'; ?>>
            Price: Low to High
        </option>
        <option value="high" <?php if ($sort === 'high') echo 'selected'; ?>>
            Price: High to Low
        </option>
    </select>

    <button type="submit" class="btn">Filter</button>
</form>

<section class="products-container">

    <?php if (count($products) > 0): ?>

        <?php foreach ($products as $product): ?>

            <div class="product-card">

                <img
                    src="<?php echo htmlspecialchars($product['image_url']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                    class="product-image"
                >

                <div class="product-info">

                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>

                    <p>
                        <?php echo htmlspecialchars(substr($product['description'], 0, 70)); ?>...
                    </p>

                    <p class="price">
                        ₪<?php echo number_format($product['price'], 2); ?>
                    </p>

                    <small>
                        Category: <?php echo htmlspecialchars($product['category_name']); ?>
                    </small>

                    <div class="product-buttons">

                        <a
                            href="product-detail.php?id=<?php echo $product['product_id']; ?>"
                            class="btn"
                        >
                            Details
                        </a>

                        <a
                            href="actions/add_to_cart.php?id=<?php echo $product['product_id']; ?>"
                            class="btn"
                        >
                            Add To Cart
                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p style="text-align:center; width:100%;">
            No products found.
        </p>

    <?php endif; ?>

</section>

<?php include 'includes/footer.php'; ?>