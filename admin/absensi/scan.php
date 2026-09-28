<?php
$page_title = 'Scan Absensi';
$role = 'admin';
require_once __DIR__ . '/../../includes/auth.php';
requireAdmin();
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">Scan Absensi</h1>

    <div id="scanner-container" class="relative w-full aspect-square bg-black rounded-2xl overflow-hidden shadow-lg">
        <div id="qr-reader"></div>

        <div id="scanOverlay" class="absolute inset-0 pointer-events-none hidden z-10">
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-56 h-56 rounded-xl border-2 border-cyan-400/90"
                 style="box-shadow: 0 0 0 9999px rgba(0,0,0,0.55), 0 0 20px rgba(34,211,238,0.4);">
                <div class="scan-line"></div>
            </div>
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-56 h-56 pointer-events-none">
                <span class="absolute -top-[3px] -left-[3px] w-7 h-7 border-t-4 border-l-4 border-cyan-400 rounded-tl-xl"></span>
                <span class="absolute -top-[3px] -right-[3px] w-7 h-7 border-t-4 border-r-4 border-cyan-400 rounded-tr-xl"></span>
                <span class="absolute -bottom-[3px] -left-[3px] w-7 h-7 border-b-4 border-l-4 border-cyan-400 rounded-bl-xl"></span>
                <span class="absolute -bottom-[3px] -right-[3px] w-7 h-7 border-b-4 border-r-4 border-cyan-400 rounded-br-xl"></span>
            </div>
        </div>

        <div id="idleOverlay" class="absolute inset-0 flex flex-col items-center justify-center bg-black/60 text-center px-6 z-20">
            <svg class="w-16 h-16 text-cyan-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <p class="text-gray-300 text-sm">Tekan Mulai Scan untuk membuka kamera</p>
        </div>

        <div id="result" class="absolute inset-0 flex items-center justify-center hidden z-30">
            <div id="resultContent" class="mx-6 w-full max-w-xs p-5 rounded-xl text-center text-sm font-semibold backdrop-blur-sm"></div>
        </div>
    </div>

    <div class="flex space-x-2 mt-4">
        <button id="startBtn" class="flex-1 bg-cyan-600 text-white py-3 rounded-lg hover:bg-cyan-700 transition font-semibold">
            Mulai Scan
        </button>
        <button id="stopBtn" class="flex-1 bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition font-semibold hidden">
            Stop
        </button>
    </div>
</div>

<div id="secureWarning" class="hidden max-w-md mx-auto mt-4 bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg text-sm">
    Perhatian: akses halaman ini bukan lewat <strong>localhost</strong> maupun <strong>HTTPS</strong>.
    Sebagian besar browser memblokir kamera di alamat seperti <code>http://192.168.x.x:8000</code>.
    Buka lewat <code>http://localhost:8000</code> dari komputer server.
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
const startBtn = document.getElementById('startBtn');
const stopBtn = document.getElementById('stopBtn');
const result = document.getElementById('result');
const resultContent = document.getElementById('resultContent');
const scanOverlay = document.getElementById('scanOverlay');
const idleOverlay = document.getElementById('idleOverlay');
const secureWarning = document.getElementById('secureWarning');
let scanner = null;
let isProcessing = false;

if (!window.isSecureContext) {
    secureWarning.classList.remove('hidden');
}

startBtn.addEventListener('click', async () => {
    if (isProcessing) return;
    if (!window.isSecureContext) {
        showResult('Kamera hanya bisa dibuka melalui <strong>localhost</strong> atau <strong>HTTPS</strong>.<br>Buka halaman ini dari komputer server via localhost.', 'error', 'idle');
        return;
    }
    try {
        scanner = new Html5Qrcode('qr-reader');
        await scanner.start(
            { facingMode: 'environment' },
            { fps: 10 },
            (decodedText) => {
                processScan(decodedText);
            },
            () => {}
        );
        result.classList.add('hidden');
        idleOverlay.classList.add('hidden');
        scanOverlay.classList.remove('hidden');
        startBtn.classList.add('hidden');
        stopBtn.classList.remove('hidden');
    } catch (err) {
        scanner = null;
        showResult(cameraErrorMessage(err), 'error', 'camera');
    }
});

stopBtn.addEventListener('click', () => {
    stopScanner();
});

async function stopScanner() {
    if (scanner) {
        try {
            await scanner.stop();
            await scanner.clear();
        } catch (e) {}
        scanner = null;
    }
    isProcessing = false;
    backToIdle();
}

function backToIdle() {
    result.classList.add('hidden');
    scanOverlay.classList.add('hidden');
    idleOverlay.classList.remove('hidden');
    startBtn.classList.remove('hidden');
    stopBtn.classList.add('hidden');
}

function cameraErrorMessage(err) {
    const name = err.name || '';
    const raw = (err.message || String(err));
    if (name === 'NotAllowedError' || raw.includes('NotAllowedError') || raw.includes('Permission denied')) {
        return 'Kamera diblokir browser. Klik ikon gembok <strong>di address bar</strong> &rarr; izin Kamera &rarr; <strong>Izinkan</strong>, lalu coba lagi.';
    }
    if (name === 'NotFoundError' || raw.includes('NotFoundError')) {
        return 'Kamera tidak terdeteksi di perangkat ini.';
    }
    if (name === 'NotReadableError' || raw.includes('NotReadableError')) {
        return 'Kamera sedang dipakai aplikasi lain. Tutup aplikasi lain lalu coba lagi.';
    }
    if (name === 'SecurityError' || raw.includes('SecurityError')) {
        return 'Browser memblokir kamera karena halaman tidak aman. Buka lewat <strong>localhost</strong> atau <strong>HTTPS</strong>.';
    }
    return 'Gagal mengakses kamera: ' + raw;
}

function processScan(token) {
    if (isProcessing) return;
    isProcessing = true;
    if (scanner) scanner.stop().catch(() => {});
    sendToken(token, 'camera');
}

function sendToken(token, source) {
    fetch('/admin/absensi/proses_scan.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'token=' + encodeURIComponent(token) + '&csrf_token=<?= csrf_token() ?>'
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showResult(
                'Absensi berhasil<br><strong>' + data.nama + '</strong><br>' + data.kelas + '<br>' + data.status + ' - ' + data.jam,
                'success',
                source
            );
        } else {
            showResult(data.message, 'error', source);
        }
    })
    .catch(() => {
        showResult('Gagal mengirim data', 'error', source);
    });
}

function showResult(msg, type, source) {
    scanOverlay.classList.add('hidden');
    idleOverlay.classList.add('hidden');
    stopBtn.classList.add('hidden');
    result.classList.remove('hidden');
    resultContent.innerHTML = msg;
    resultContent.className = 'mx-6 w-full max-w-xs p-5 rounded-xl text-center text-sm font-semibold backdrop-blur-sm ' +
        (type === 'success' ? 'bg-green-500/90 text-white' : 'bg-red-500/90 text-white');

    const btn = document.createElement('button');
    btn.className = 'mt-3 w-full bg-white/25 hover:bg-white/40 text-white py-2 rounded-lg transition font-semibold';
    if (source === 'camera') {
        btn.textContent = type === 'success' ? 'Scan Lagi' : 'Coba Lagi';
        btn.onclick = async () => {
            if (scanner) {
                try { await scanner.stop(); await scanner.clear(); } catch (e) {}
                scanner = null;
            }
            isProcessing = false;
            result.classList.add('hidden');
            startBtn.click();
        };
    } else {
        btn.textContent = 'Tutup';
        btn.onclick = () => {
            isProcessing = false;
            backToIdle();
        };
    }
    resultContent.appendChild(btn);
}
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
