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

$success = '';
$error = '';

$categoriesStmt = $pdo->query("SELECT * FROM categories");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $stock_quantity = trim($_POST['stock_quantity']);
    $image_url = trim($_POST['image_url']);
    $category_id = trim($_POST['category_id']);

    if (
        !empty($name) &&
        !empty($price) &&
        !empty($stock_quantity) &&
        !empty($image_url) &&
        !empty($category_id)
    ) {
        $stmt = $pdo->prepare("
            INSERT INTO products
            (name, description, price, stock_quantity, image_url, category_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $name,
            $description,
            $price,
            $stock_quantity,
            $image_url,
            $category_id
        ]);

        $success = "تمت إضافة المنتج بنجاح";
    } else {
        $error = "يرجى تعبئة جميع الحقول المطلوبة";
    }
}

include '../includes/header.php';
?>

<section class="admin-page">

    <div class="auth-container">
        <div class="auth-card">

            <h2>إضافة منتج جديد</h2>

            <?php if (!empty($success)): ?>
                <div class="success-message">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>اسم المنتج</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>وصف المنتج</label>
                    <textarea name="description"></textarea>
                </div>

                <div class="form-group">
                    <label>السعر</label>
                    <input type="number" name="price" step="0.01" required>
                </div>

                <div class="form-group">
                    <label>الكمية المتوفرة</label>
                    <input type="number" name="stock_quantity" required>
                </div>

                <div class="form-group">
                    <label>مسار صورة المنتج</label>
                    <input
                        type="text"
                        name="image_url"
                        placeholder="../assets/images/product.jpg"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>التصنيف</label>

                    <select name="category_id" required>
                        <option value="">اختر التصنيف</option>

                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['category_id']; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btnSub auth-btn">
                    إضافة المنتج
                </button>

            </form>

        </div>
    </div>

</section>

<?php include '../includes/footer.php'; ?>