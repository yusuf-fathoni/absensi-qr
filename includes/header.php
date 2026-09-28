<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Absensi Siswa') ?></title>
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
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-indigo-50">
    <div class="flex min-h-screen">
        <?php include __DIR__ . '/' . ($role ?? 'admin') . '_sidebar.php'; ?>
        <div class="flex-1 md:ml-64">
            <div class="bg-white/80 backdrop-blur border-b border-gray-200 px-5 py-3 flex justify-between items-center sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <button id="sidebarToggle" class="md:hidden w-9 h-9 rounded-lg hover:bg-gray-100 text-gray-600 flex items-center justify-center transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <span class="hidden md:block font-semibold text-gray-700 text-sm tracking-tight"><?= htmlspecialchars($page_title ?? '') ?></span>
                </div>
                <div class="flex items-center gap-2.5">
                    <?php $ini = strtoupper(substr($_SESSION['nama'] ?? $_SESSION['username'] ?? 'U', 0, 1)); ?>
                    <span class="w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold flex items-center justify-center shadow-sm"><?= htmlspecialchars($ini) ?></span>
                    <span class="hidden sm:block text-sm font-medium text-gray-600"><?= htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username'] ?? '') ?></span>
                </div>
            </div>
            <div class="p-6 fade-up">