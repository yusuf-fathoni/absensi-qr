<div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out z-50 flex flex-col">
    <div class="p-4 flex items-center gap-3 border-b border-white/10">
        <span class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h5.25v5.25H3.75V4.5zm0 9.75h5.25v5.25H3.75V14.25zM15 4.5h5.25v5.25H15V4.5zm0 9.75h2.25M19.5 14.25h.75v.75h-.75v-.75zm0 3h.75v.75h-.75v-.75zm-3 0h.75v.75H16.5v-.75zm-1.5-3h.75v.75H15v-.75zM15 17.25h2.25V19.5H15v-2.25z"/>
            </svg>
        </span>
        <div class="min-w-0">
            <h1 class="text-sm font-bold tracking-tight leading-tight">Absensi Siswa</h1>
            <span class="inline-block mt-0.5 bg-white/10 text-blue-100 text-[11px] font-semibold px-2 py-0.5 rounded-full">Admin</span>
        </div>
    </div>

    <nav class="flex-1 flex flex-col overflow-y-auto px-3 py-4">
        <p class="text-[11px] font-semibold tracking-widest text-blue-300/70 uppercase px-3 mb-2">Menu</p>

        <a href="/admin/dashboard.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
            <svg class="w-5 h-5 shrink-0 <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'text-white' : 'text-blue-300' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <a href="/admin/absensi/scan.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition <?= basename($_SERVER['PHP_SELF']) === 'scan.php' ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
            <svg class="w-5 h-5 shrink-0 <?= basename($_SERVER['PHP_SELF']) === 'scan.php' ? 'text-white' : 'text-blue-300' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            Scan Absensi
        </a>
        <a href="/admin/siswa/index.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition <?= strpos($_SERVER['PHP_SELF'], '/siswa/') !== false ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
            <svg class="w-5 h-5 shrink-0 <?= strpos($_SERVER['PHP_SELF'], '/siswa/') !== false ? 'text-white' : 'text-blue-300' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Data Siswa
        </a>
        <a href="/admin/absensi/hari_ini.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition <?= basename($_SERVER['PHP_SELF']) === 'hari_ini.php' ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
            <svg class="w-5 h-5 shrink-0 <?= basename($_SERVER['PHP_SELF']) === 'hari_ini.php' ? 'text-white' : 'text-blue-300' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            Absensi Hari Ini
        </a>
        <a href="/admin/absensi/pengajuan.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition <?= basename($_SERVER['PHP_SELF']) === 'pengajuan.php' ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
            <svg class="w-5 h-5 shrink-0 <?= basename($_SERVER['PHP_SELF']) === 'pengajuan.php' ? 'text-white' : 'text-blue-300' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Pengajuan
        </a>
        <a href="/admin/absensi/rekap.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition <?= basename($_SERVER['PHP_SELF']) === 'rekap.php' ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' ?>">
            <svg class="w-5 h-5 shrink-0 <?= basename($_SERVER['PHP_SELF']) === 'rekap.php' ? 'text-white' : 'text-blue-300' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Rekap Absensi
        </a>

        <div class="mt-auto pt-4 border-t border-white/10">
            <a href="/auth/logout.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-blue-100 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5 shrink-0 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </a>
        </div>
    </nav>
</div>
