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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
        .fade-up { animation: fadeUp .5s ease-out both; }
        @media (prefers-reduced-motion: reduce) { .fade-up { animation: none; } }
    </style>
</head>
<body class="bg-indigo-50 min-h-screen flex items-center justify-center overflow-hidden p-4">

    <!-- dekorasi latar: gradasi blob lembut -->
    <div class="fixed inset-0 -z-10 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-blue-300/40 blur-3xl"></div>
        <div class="absolute -bottom-40 -right-24 w-[28rem] h-[28rem] rounded-full bg-indigo-400/30 blur-3xl"></div>
        <div class="absolute top-1/3 right-1/4 w-64 h-64 rounded-full bg-purple-300/30 blur-3xl"></div>
    </div>

    <div class="w-full max-w-md fade-up">
        <div class="bg-white rounded-3xl shadow-xl shadow-indigo-900/10 border border-gray-100 p-8 sm:p-10">

            <!-- ikon badge -->
            <div class="mx-auto mb-6 w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-600/30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h5.25v5.25H3.75V4.5zm0 9.75h5.25v5.25H3.75V14.25zM15 4.5h5.25v5.25H15V4.5zm0 9.75h2.25M19.5 14.25h.75v.75h-.75v-.75zm0 3h.75v.75h-.75v-.75zm-3 0h.75v.75H16.5v-.75zm-1.5-3h.75v.75H15v-.75z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17.25h2.25V19.5H15v-2.25z"/>
                </svg>
            </div>

            <div class="text-center mb-8">
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Absensi Siswa</h1>
                <p class="text-gray-500 text-sm mt-1">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            <?php if ($error): ?>
                <div class="flex items-start gap-2.5 bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-xl mb-6">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                    <span class="text-sm font-medium"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <?= csrf_field() ?>

                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="username">Username</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </span>
                        <input type="text" name="username" id="username" required autocomplete="username"
                            class="w-full pl-11 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50/50 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition text-sm"
                            placeholder="Masukkan username"
                            value="<?= htmlspecialchars($username ?? '') ?>">
                    </div>
                </div>

                <div class="mb-7">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">Password</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" required autocomplete="current-password"
                            class="w-full pl-11 pr-11 py-2.5 border border-gray-200 rounded-xl bg-gray-50/50 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition text-sm"
                            placeholder="Masukkan password">
                        <button type="button" id="togglePw" aria-label="Tampilkan password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-1">
                            <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl hover:bg-blue-700 active:scale-[.99] shadow-lg shadow-blue-600/25 transition duration-150 text-sm">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">Sistem Absensi QR &middot; Aplikasi Absensi Siswa</p>
    </div>

    <script>
    const togglePw = document.getElementById('togglePw');
    const pwInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');
    togglePw.addEventListener('click', () => {
        const show = pwInput.type === 'password';
        pwInput.type = show ? 'text' : 'password';
        eyeOpen.classList.toggle('hidden', show);
        eyeClosed.classList.toggle('hidden', !show);
    });
    </script>
</body>
</html>
