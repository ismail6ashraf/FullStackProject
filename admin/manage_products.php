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

/* Delete Product */
if (isset($_GET['delete'])) {
    $product_id = (int) $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);

    header("Location: manage_products.php");
    exit;
}

/* Fetch Products */
$stmt = $pdo->query("
    SELECT products.*, categories.name AS category_name
    FROM products
    LEFT JOIN categories
    ON products.category_id = categories.category_id
    ORDER BY products.product_id DESC
");

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<section class="admin-page">

    <div class="admin-container">

        <h1 class="admin-title">إدارة المنتجات</h1>

        <div class="admin-buttons">
            <a href="add_product.php" class="btn">
                إضافة منتج جديد
            </a>
        </div>

        <div class="table-wrapper">

            <table class="admin-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الصورة</th>
                        <th>اسم المنتج</th>
                        <th>السعر</th>
                        <th>الكمية</th>
                        <th>التصنيف</th>
                        <th>تاريخ الإضافة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($products as $product): ?>

                        <tr>
                            <td>
                                <?php echo $product['product_id']; ?>
                            </td>

                            <td>
                                <img
                                    src="../<?php echo htmlspecialchars($product['image_url']); ?>"
                                    class="admin-product-img"
                                >
                            </td>

                            <td>
                                <?php echo htmlspecialchars($product['name']); ?>
                            </td>

                            <td>
                                ₪ <?php echo number_format($product['price'], 2); ?>
                            </td>

                            <td>
                                <?php echo $product['stock_quantity']; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($product['category_name']); ?>
                            </td>

                            <td>
                                <?php echo $product['created_at']; ?>
                            </td>

                            <td>
                                <a
                                    href="edit_product.php?id=<?php echo $product['product_id']; ?>"
                                    class="edit-btn"
                                >
                                    تعديل
                                </a>

                                <a
                                    href="manage_products.php?delete=<?php echo $product['product_id']; ?>"
                                    class="delete-btn"
                                    onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟');"
                                >
                                    حذف
                                </a>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</section>

<?php include '../includes/footer.php'; ?>