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

$productsCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$contactsCount = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();

include '../includes/header.php';
?>

<section class="admin-page">

    <div class="admin-container">

        <h1 class="admin-title">Admin Dashboard</h1>

        <div class="admin-cards">

            <div class="admin-card">
                <div class="admin-icon">
                    <i class="fa-solid fa-box"></i>
                </div>

                <h2><?php echo $productsCount; ?></h2>
                <p>عدد المنتجات</p>
            </div>

            <div class="admin-card">
                <div class="admin-icon">
                    <i class="fa-solid fa-users"></i>
                </div>

                <h2><?php echo $usersCount; ?></h2>
                <p>عدد المستخدمين</p>
            </div>

            <div class="admin-card">
                <div class="admin-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>

                <h2><?php echo $contactsCount; ?></h2>
                <p>عدد الرسائل</p>
            </div>

        </div>

        

    </div>

</section>

<?php include '../includes/footer.php'; ?>