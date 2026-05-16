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

/* Delete Message */
if (isset($_GET['delete'])) {

    $message_id = (int) $_GET['delete'];

    $deleteStmt = $pdo->prepare("
        DELETE FROM contacts
        WHERE message_id = ?
    ");

    $deleteStmt->execute([$message_id]);

    header("Location: manage_contacts.php");
    exit;
}

/* Fetch Messages */
$stmt = $pdo->query("
    SELECT *
    FROM contacts
    ORDER BY message_id DESC
");

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<section class="admin-page">

    <div class="admin-container">

        <h1 class="admin-title">إدارة الرسائل</h1>

        <div class="table-wrapper">

            <table class="admin-table">

                <thead>
                    <tr>
                        
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الموضوع</th>
                        <th>الرسالة</th>
                        <th>تاريخ الإرسال</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($messages) > 0): ?>

                        <?php foreach ($messages as $message): ?>

                            <tr>
                               

                                <td><?php echo htmlspecialchars($message['name']); ?></td>

                                <td><?php echo htmlspecialchars($message['email']); ?></td>

                                <td><?php echo htmlspecialchars($message['subject']); ?></td>

                                <td class="message-text">
                                    <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                </td>

                                <td><?php echo $message['submitted_at']; ?></td>

                                <td>
                                    <?php echo $message['is_read'] ? 'مقروءة' : 'غير مقروءة'; ?>
                                </td>

                                <td>
                                    <a
                                        href="manage_contacts.php?delete=<?php echo $message['message_id']; ?>"
                                        class="delete-btn"
                                        onclick="return confirm('هل أنت متأكد من حذف الرسالة؟');"
                                    >
                                        حذف
                                    </a>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8">لا توجد رسائل حالياً</td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</section>

<?php include '../includes/footer.php'; ?>