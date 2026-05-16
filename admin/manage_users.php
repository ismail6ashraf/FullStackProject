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

/* Update User Role */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {

    $user_id = (int) $_POST['user_id'];
    $role = $_POST['role'];

    if (in_array($role, ['customer', 'admin'])) {

        $stmt = $pdo->prepare("
            UPDATE users
            SET role = ?
            WHERE user_id = ?
        ");

        $stmt->execute([$role, $user_id]);
    }

    header("Location: manage_users.php");
    exit;
}

/* Delete User */
if (isset($_GET['delete'])) {

    $user_id = (int) $_GET['delete'];

    if ($user_id != $_SESSION['user_id']) {

        $stmt = $pdo->prepare("
            DELETE FROM users
            WHERE user_id = ?
        ");

        $stmt->execute([$user_id]);
    }

    header("Location: manage_users.php");
    exit;
}

/* Fetch Users */
$stmt = $pdo->query("
    SELECT *
    FROM users
    ORDER BY user_id DESC
");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<section class="admin-page">

    <div class="admin-container">

        <h1 class="admin-title">إدارة المستخدمين</h1>

        <div class="table-wrapper">

            <table class="admin-table">

                <thead>
                    <tr>
                        
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>العنوان</th>
                        <th>نوع الحساب</th>
                        <th>تاريخ التسجيل</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($users as $user): ?>

                        <tr>
                            

                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>

                            <td><?php echo htmlspecialchars($user['email']); ?></td>

                            <td><?php echo htmlspecialchars($user['phone']); ?></td>

                            <td><?php echo htmlspecialchars($user['address']); ?></td>

                            <td>
                                <form method="POST" class="status-form">

                                    <input
                                        type="hidden"
                                        name="user_id"
                                        value="<?php echo $user['user_id']; ?>"
                                    >

                                    <select name="role">
                                        <option value="customer" <?php if ($user['role'] === 'customer') echo 'selected'; ?>>
                                            Customer
                                        </option>

                                        <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>
                                            Admin
                                        </option>
                                    </select>

                                    <button type="submit" class="small-btn">
                                        حفظ
                                    </button>

                                </form>
                            </td>

                            <td><?php echo $user['created_at']; ?></td>

                            <td>
                                <?php if ($user['user_id'] != $_SESSION['user_id']): ?>

                                    <a
                                        href="manage_users.php?delete=<?php echo $user['user_id']; ?>"
                                        class="delete-btn"
                                        onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');"
                                    >
                                        حذف
                                    </a>

                                <?php else: ?>

                                    <span>حسابك الحالي</span>

                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</section>

<?php include '../includes/footer.php'; ?>