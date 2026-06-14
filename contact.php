<?php
include 'config/db.php';

$messageSent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (
        !empty($name) &&
        !empty($email) &&
        !empty($subject) &&
        !empty($message)
    ) {

        $stmt = $pdo->prepare("
            INSERT INTO contacts
            (name, email, subject, message)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $name,
            $email,
            $subject,
            $message
        ]);

        $messageSent = true;
    }
}

include 'includes/header.php';
?>

<section class="contact-page">

    <div class="contact-container">

        <div class="contact-info">

            <h2>تواصل معنا</h2>

            <p>
                إذا كان لديك أي استفسار أو اقتراح لا تتردد بالتواصل معنا.
            </p>

            <div class="info-box">
                <h4>📍 العنوان</h4>
                <p>غزة - فلسطين</p>
            </div>

            <div class="info-box">
                <h4>📞 الهاتف</h4>
                <p>0599140359</p>
            </div>

            <div class="info-box">
                <h4>✉ البريد الإلكتروني</h4>
                <p>ismailashraf@gmail.com</p>
            </div>

            <div class="info-box">
                <h4>🕒 ساعات العمل</h4>
                <p>من 9 صباحًا حتى 9 مساءا</p>
            </div>

        </div>

        <div class="contact-form-box">

            <h2>إرسال رسالة</h2>

            <?php if ($messageSent): ?>

                <div class="success-message">
                    تم إرسال رسالتك بنجاح 🔥
                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label>الاسم الكامل</label>

                    <input
                        type="text"
                        name="name"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>البريد الإلكتروني</label>

                    <input
                        type="email"
                        name="email"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>الموضوع</label>

                    <select name="subject" required>

                        <option value="">
                            اختر الموضوع
                        </option>

                        <option value="Inquiry">
                            استفسار
                        </option>

                        <option value="Complaint">
                            شكوى
                        </option>

                        <option value="Suggestion">
                            اقتراح
                        </option>

                    </select>
                </div>

                <div class="form-group">

                    <label>الرسالة</label>

                    <textarea
                        name="message"
                        required
                    ></textarea>

                </div>

                <button type="submit" class="btnSub">
                    إرسال الرسالة
                </button>

            </form>

        </div>

    </div>

</section>

<?php include 'includes/footer.php'; ?>