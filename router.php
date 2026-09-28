<?php
// Router untuk PHP built-in server (php -S 0.0.0.0:8000 router.php)
// Menolak akses file sensitif & folder internal.

// Security header untuk semua respons (termasuk 404 dari router ini)
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');

$uriPath = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$basename = basename($uriPath);

// 1) Blokir semua dotfile (.env, .git, .htaccess, dsb)
if ($basename !== '' && $basename[0] === '.') {
    http_response_code(404);
    exit('Not Found');
}

// 2) Blokir ekstensi file sensitif
$sensitiveExt = ['env', 'sql', 'md', 'log', 'ini', 'lock', 'sh', 'yml', 'yaml', 'dist', 'bak'];
$ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
if (in_array($ext, $sensitiveExt, true)) {
    http_response_code(404);
    exit('Not Found');
}

// 3) Blokir berkas proyek non-web
if (in_array(strtolower($basename), ['composer.json', 'composer.lock', 'phpunit.xml', '.gitignore'], true)) {
    http_response_code(404);
    exit('Not Found');
}

// 4) Blokir folder internal (tidak pernah diakses via HTTP oleh aplikasi)
$blockedPrefixes = ['/config/', '/functions/', '/includes/', '/vendor/'];
foreach ($blockedPrefixes as $prefix) {
    if (strpos($uriPath, $prefix) === 0) {
        http_response_code(404);
        exit('Not Found');
    }
}

// 5) Selain itu: biarkan server bawaan PHP melayani (eksekusi .php, sajikan assets)
return false;
