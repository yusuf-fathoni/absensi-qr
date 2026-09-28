document.getElementById('sidebarToggle')?.addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
});

function confirmDelete(msg) {
    return confirm(msg || 'Apakah Anda yakin ingin menghapus data ini?');
}