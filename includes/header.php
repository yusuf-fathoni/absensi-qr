<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Absensi Siswa') ?></title>
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
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-indigo-50">
    <div class="flex min-h-screen">
        <?php include __DIR__ . '/' . ($role ?? 'admin') . '_sidebar.php'; ?>
        <div class="flex-1 md:ml-64">
            <div class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-10">
                <button id="sidebarToggle" class="md:hidden text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="flex items-center">
                    <span class="text-gray-600">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username'] ?? '') ?></strong></span>
                </div>
            </div>
            <div class="p-6">