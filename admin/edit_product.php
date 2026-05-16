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

if (!isset($_GET['id'])) {
    header("Location: manage_products.php");
    exit;
}

$product_id = (int) $_GET['id'];

/* Fetch Product */
$stmt = $pdo->prepare("
    SELECT * FROM products
    WHERE product_id = ?
");

$stmt->execute([$product_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: manage_products.php");
    exit;
}

/* Fetch Categories */
$categoriesStmt = $pdo->query("SELECT * FROM categories");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

$success = '';

/* Update Product */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $stock_quantity = trim($_POST['stock_quantity']);
    $image_url = trim($_POST['image_url']);
    $category_id = trim($_POST['category_id']);

    $updateStmt = $pdo->prepare("
        UPDATE products
        SET
            name = ?,
            description = ?,
            price = ?,
            stock_quantity = ?,
            image_url = ?,
            category_id = ?
        WHERE product_id = ?
    ");

    $updateStmt->execute([
        $name,
        $description,
        $price,
        $stock_quantity,
        $image_url,
        $category_id,
        $product_id
    ]);

    $success = "تم تحديث المنتج بنجاح";

    /* Refresh Data */
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

include '../includes/header.php';
?>

<section class="admin-page">

    <div class="auth-container">

        <div class="auth-card">

            <h2>تعديل المنتج</h2>

            <?php if (!empty($success)): ?>
                <div class="success-message">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>اسم المنتج</label>

                    <input
                        type="text"
                        name="name"
                        value="<?php echo htmlspecialchars($product['name']); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>وصف المنتج</label>

                    <textarea name="description"><?php
                        echo htmlspecialchars($product['description']);
                    ?></textarea>
                </div>

                <div class="form-group">
                    <label>السعر</label>

                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        value="<?php echo $product['price']; ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>الكمية المتوفرة</label>

                    <input
                        type="number"
                        name="stock_quantity"
                        value="<?php echo $product['stock_quantity']; ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>مسار الصورة</label>

                    <input
                        type="text"
                        name="image_url"
                        value="<?php echo htmlspecialchars($product['image_url']); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>التصنيف</label>

                    <select name="category_id" required>

                        <?php foreach ($categories as $category): ?>

                            <option
                                value="<?php echo $category['category_id']; ?>"
                                <?php
                                if (
                                    $product['category_id']
                                    == $category['category_id']
                                ) {
                                    echo 'selected';
                                }
                                ?>
                            >
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <button type="submit" class="btnSub auth-btn">
                    حفظ التعديلات
                </button>

            </form>

        </div>

    </div>

</section>

<?php include '../includes/footer.php'; ?>