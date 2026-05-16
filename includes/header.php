<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$basePath = '';

if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    $basePath = '../';
}

$cartCount = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);

$isAdminPage = in_array($currentPage, [
    'admin_dashboard.php',
    'add_product.php',
    'manage_products.php',
    'manage_contacts.php',
    'manage_orders.php',
    'manage_users.php',
    'edit_product.php'
]);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>EliteShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/style.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

<header class="main-header">
    <nav class="navbar">

        <div class="logo-box">
            <h1 class="logo-title">
                EliteShop <i class="fa-solid fa-store"></i>
            </h1>
            <p class="logo-subtitle">أحدث التقنيات بأفضل الأسعار</p>
        </div>

        <?php if ($isAdminPage): ?>

            <ul class="nav-links">
                <li>
                    <a href="<?php echo $basePath; ?>admin/admin_dashboard.php">
                        لوحة التحكم
                    </a>
                </li>

                <li>
                    <a href="<?php echo $basePath; ?>admin/add_product.php">
                        إضافة منتج
                    </a>
                </li>

                <li>
                    <a href="<?php echo $basePath; ?>admin/manage_products.php">
                        إدارة المنتجات
                    </a>
                </li>

                <li>
                    <a href="<?php echo $basePath; ?>admin/manage_contacts.php">
                        إدارة الرسائل
                    </a>
                </li>

                <li>
                    <a href="<?php echo $basePath; ?>admin/manage_orders.php">
                        إدارة الطلبات
                    </a>
                </li>

                <li>
                    <a href="<?php echo $basePath; ?>admin/manage_users.php">
                        إدارة المستخدمين
                    </a>
                </li>
            </ul>

            <div class="left-nav">
                <span class="user-name">
                    <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Admin'); ?>
                </span>

                <a href="<?php echo $basePath; ?>logout.php" class="logout-link">
                    تسجيل الخروج
                </a>
            </div>

        <?php elseif (isset($_SESSION['user_id'])): ?>

            <ul class="nav-links">
                <li>
                    <a href="<?php echo $basePath; ?>index.php">الرئيسية</a>
                </li>

                <li>
                    <a href="<?php echo $basePath; ?>products.php">المنتجات</a>
                </li>

                <li>
                    <a href="<?php echo $basePath; ?>cart.php">
                        سلة التسوق
                        <span class="cart-count"><?php echo $cartCount; ?></span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo $basePath; ?>contact.php">اتصل بنا</a>
                </li>
            </ul>

            <div class="left-nav">
                <a href="<?php echo $basePath; ?>cart.php" class="cart-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="cart-badge"><?php echo $cartCount; ?></span>
                </a>

                <a href="<?php echo $basePath; ?>profile.php" class="user-name">
                    <?php echo htmlspecialchars($_SESSION['full_name']); ?>
                </a>

                <a href="<?php echo $basePath; ?>logout.php" class="logout-link">
                    تسجيل الخروج
                </a>
            </div>

        <?php else: ?>

            <div class="left-nav guest-nav">
                <a href="<?php echo $basePath; ?>login.php" class="login-link">
                    تسجيل الدخول
                </a>

                <a href="<?php echo $basePath; ?>register.php" class="login-link">
                    إنشاء حساب
                </a>
            </div>

        <?php endif; ?>

    </nav>
</header>

<main>