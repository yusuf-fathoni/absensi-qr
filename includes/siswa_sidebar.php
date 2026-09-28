<div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-blue-800 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out z-50">
    <div class="p-4 border-b border-blue-700">
        <h1 class="text-lg font-bold">Absensi Siswa</h1>
        <p class="text-blue-300 text-sm">Siswa</p>
    </div>
    <nav class="mt-2">
        <a href="/siswa/dashboard.php" class="flex items-center px-4 py-3 hover:bg-blue-700 <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'bg-blue-700' : '' ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <a href="/siswa/qr_saya.php" class="flex items-center px-4 py-3 hover:bg-blue-700 <?= basename($_SERVER['PHP_SELF']) === 'qr_saya.php' ? 'bg-blue-700' : '' ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            QR Saya
        </a>
        <a href="/siswa/riwayat.php" class="flex items-center px-4 py-3 hover:bg-blue-700 <?= basename($_SERVER['PHP_SELF']) === 'riwayat.php' ? 'bg-blue-700' : '' ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Riwayat Absensi
        </a>
        <a href="/siswa/pengajuan.php" class="flex items-center px-4 py-3 hover:bg-blue-700 <?= basename($_SERVER['PHP_SELF']) === 'pengajuan.php' ? 'bg-blue-700' : '' ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Ajukan Izin/Sakit
        </a>
        <a href="/siswa/profil.php" class="flex items-center px-4 py-3 hover:bg-blue-700 <?= basename($_SERVER['PHP_SELF']) === 'profil.php' ? 'bg-blue-700' : '' ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Profil
        </a>
        <div class="border-t border-blue-700 mt-2 pt-2">
            <a href="/auth/logout.php" class="flex items-center px-4 py-3 hover:bg-blue-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </a>
        </div>
    </nav>
</div>