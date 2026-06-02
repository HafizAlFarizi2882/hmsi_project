<?php
$pageTitle = "Beranda";
require_once "includes/header.php";
?>

<!-- Hero Section -->
<section class="relative bg-primary overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"/>
        </svg>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 relative z-10">
        <div class="text-center">
            <div class="inline-block mb-4">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto shadow-lg">
                    <span class="text-primary font-bold text-3xl">H</span>
                </div>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">Himpunan Mahasiswa<br>Sistem Informasi</h1>
            <p class="text-xl text-green-200 mb-8 max-w-2xl mx-auto">Bergabunglah bersama kami untuk mengembangkan potensi, memperluas jaringan, dan berkontribusi untuk kemajuan teknologi informasi.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="pages/pendaftaran.php" class="bg-white text-primary hover:bg-lightgreen px-8 py-3 rounded-lg font-semibold text-lg transition shadow-lg">
                    Daftar Sekarang
                </a>
                <a href="pages/tentang.php" class="border-2 border-white text-white hover:bg-white hover:text-primary px-8 py-3 rounded-lg font-semibold text-lg transition">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-textprimary mb-3">Mengapa Bergabung?</h2>
            <p class="text-textsecondary max-w-xl mx-auto">HMSI menawarkan berbagai keuntungan untuk pengembangan diri mahasiswa Sistem Informasi.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-lightgreen rounded-xl p-6 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-textprimary mb-2">Pengembangan Ilmu</h3>
                <p class="text-textsecondary">Akses ke workshop, seminar, dan pelatihan teknologi informasi terkini.</p>
            </div>
            <div class="bg-lightgreen rounded-xl p-6 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-textprimary mb-2">Jaringan Profesional</h3>
                <p class="text-textsecondary">Bangun relasi dengan mahasiswa, alumni, dan profesional di bidang IT.</p>
            </div>
            <div class="bg-lightgreen rounded-xl p-6 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-textprimary mb-2">Inovasi & Kreativitas</h3>
                <p class="text-textsecondary">Wadah untuk mengembangkan ide-ide kreatif dan inovasi teknologi.</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl font-bold text-white mb-1">500+</div>
                <div class="text-green-200">Anggota Aktif</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-white mb-1">50+</div>
                <div class="text-green-200">Event per Tahun</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-white mb-1">10+</div>
                <div class="text-green-200">Tahun Berdiri</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-white mb-1">100%</div>
                <div class="text-green-200">Semangat Kolaborasi</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-textprimary mb-4">Siap Bergabung?</h2>
        <p class="text-textsecondary mb-8 text-lg">Jadilah bagian dari komunitas mahasiswa Sistem Informasi yang berdedikasi untuk mengembangkan teknologi dan inovasi.</p>
        <a href="pages/pendaftaran.php" class="inline-block bg-primary text-white hover:bg-secondary px-8 py-4 rounded-lg font-semibold text-lg transition shadow-lg">
            Daftar Menjadi Anggota
        </a>
    </div>
</section>

<?php require_once "includes/footer.php"; ?>
