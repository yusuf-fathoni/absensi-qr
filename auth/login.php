<?php
require_once __DIR__ . '/../includes/session.php';
security_bootstrap();
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi';
    } else {
        $db = getDBConnection();
        $stmt = $db->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            unset($_SESSION['csrf_token']);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: /admin/dashboard.php");
            } else {
                $stmt = $db->prepare("SELECT nama FROM siswa WHERE user_id = ?");
                $stmt->execute([$user['id']]);
                $_SESSION['nama'] = $stmt->fetchColumn() ?: $user['username'];
                header("Location: /siswa/dashboard.php");
            }
            exit();
        } else {
            $error = 'Username atau password salah';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    blue: {
                        50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe',
                        300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1',
                        600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81'
                    },
                    gray: { 50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0' }
                }
            }
        }
    };
    </script>
</head>
<body class="bg-indigo-50 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-2">Absensi Siswa</h1>
        <p class="text-center text-gray-500 mb-6">Silakan login ke akun Anda</p>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                <input type="text" name="username" id="username" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    placeholder="Masukkan username"
                    value="<?= htmlspecialchars($username ?? '') ?>">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    placeholder="Masukkan password">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                Login
            </button>
        </form>
    </div>
</body>
</html>