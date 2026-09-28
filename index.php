<?php
require_once __DIR__ . '/includes/session.php';
security_bootstrap();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: siswa/dashboard.php");
    }
} else {
    header("Location: auth/login.php");
}
exit();