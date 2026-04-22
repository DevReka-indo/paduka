// resources/js/dark-mode.js

// Jalankan SEBELUM render agar tidak ada flash putih saat dark mode aktif
(function () {
    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (saved === 'dark' || (!saved && prefersDark)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
})();

// Fungsi toggle — dipanggil dari tombol di blade
window.toggleDarkMode = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
};

// Fungsi cek status aktif (untuk ikon tombol)
window.isDarkMode = function () {
    return document.documentElement.classList.contains('dark');
};
